<?php
class Custom_Model_Memcached
{
    private $_memcacheAPI;
    private $_configName = 'internal_config';
    private $_tag;
	private $_option;

    public function __construct($tag = null)
    {
        $this -> option = Zend_Registry::get('config') -> cache -> backend -> memcached -> toArray();
        $this -> _memcacheAPI = new Zend_Cache_Backend_Memcached($this -> option);
        $this -> _tag = $tag ? $tag.'_' : '';
    }
    
    public function set($name, $value, $expire = 0)
    {
        $name = $this -> _tag.$name;
        if ($expire) {
            $this -> _memcacheAPI -> save($value, $name, array(), $expire);
        }
        else {
            $this -> _memcacheAPI -> save($value, $name);
        }
        $config = $this -> getConfig();
        $config[$name]['time'] = time();
        $config[$name]['expire'] = $expire;
        $this -> setConfig($config);
        return true;
    }
    
    public function hit($name)
    {
        $name = $this -> _tag.$name;
        $config = $this -> getConfig();
        if ($config[$name]) {
            if ($config[$name]['expire'] && (($config[$name]['time'] + $config[$name]['expire']) < time())) {
                unset($config[$name]);
                $this -> setConfig($config);
                return false;
            }
        }
        else    return false;
        
        return true;
    }
    
    public function get($name)
    {
        $name = $this -> _tag.$name;
        return $this -> _memcacheAPI -> load($name);
    }
    
    public function remove($name)
    {
        $name = $this -> _tag.$name;
        $this -> _memcacheAPI -> remove($name);
        
        $config = $this -> getConfig();
        unset($config[$name]);
        $this -> setConfig($config);
		return true;
    }
    
    public function clean($tag = null)
    {
        if (!$tag) {
            $tag = $this -> _tag;
        }
        $config = $this -> getConfig();
        if (!$config)   return false;
        
        foreach ($config as $item => $value) {
            if (substr($item, 0, strlen($tag)) == $tag) {
                $this -> _memcacheAPI -> remove($item);
                unset($config[$item]);
            }
        }
        
        $this -> setConfig($config);
		return true;
    }
    
    public function cleanAll($tag = null)
    {
        if (!$tag) {
            $tag = $this -> _tag;
        }
        $config = $this -> getConfig();
        if (!$config)   return false;
        
        foreach ($config as $item => $value) {
            if (substr($item, 0, strlen($tag)) == $tag) {
                $this -> _memcacheAPI -> remove($item);
                unset($config[$item]);
            }
        }
        $this -> setConfig($config);
		return true;

    }
    
    public function fetchAll($tag = null)
    {
        if (!$tag) {
            $tag = $this -> _tag;
        }
        $config = $this -> getConfig();
        if (!$config)   return false;
        
        foreach ($config as $item => $value) {
            if (substr($item, 0, strlen($tag)) == $tag) {
                if (!$value['expire']) {
                    $overdue = 0;
                }
                else {
                    if ($value['time'] + $value['expire'] < time()) {
                        $overdue = 1;
                    }
                    else {
                        $overdue = 0;
                    }
                }
                $result[] = array('path' => urldecode(substr($item, strlen($tag), strlen($item))),
                                  'add_time' => $value['time'],
                                  'value' => $this -> _memcacheAPI -> load($item),
                                  'overdue' => $overdue,
                                 );
            }
        }
        return $result;
    }
    private function getConfig()
    {
        return unserialize($this -> _memcacheAPI -> load($this -> _configName));
    }
    private function setConfig($config)
    {
        $this -> _memcacheAPI -> save(serialize($config), $this -> _configName);
    }
}
