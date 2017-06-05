<?php
class Custom_Model_Redis 
{
    private $_redisAPI;
    private $_configName = 'internal_config';
    private $_tag;
    
    public function __construct($tag = null)
    {
        $this -> _redisAPI = new Redis();
        $this -> _redisAPI -> connect(REDIS_SERVER, REDIS_PORT);
        
        $this -> _tag = $tag ? $tag.'_' : '';
    }
    
    public function set($name, $value, $expire = 0)
    {
        $name = $this -> _tag.$name;
        if ($expire) {
            $this -> _redisAPI -> setex($name, $expire + 10, $value);
        }
        else {
            $this -> _redisAPI -> set($name, $value);
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
        return $this -> _redisAPI -> get($name);
    }
    
    public function remove($name)
    {
        $name = $this -> _tag.$name;
        $this -> _redisAPI -> delete($name);
        
        $config = $this -> getConfig();
        unset($config[$name]);
        $this -> setConfig($config);
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
                $this -> _redisAPI -> delete($item);
                unset($config[$item]);
            }
        }
        
        $this -> setConfig($config);
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
                $this -> _redisAPI -> delete($item);
                unset($config[$item]);
            }
        }
        
        $this -> setConfig($config);
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
                                  'value' => $this -> _redisAPI -> get($item),
                                  'overdue' => $overdue,
                                 );
            }
        }
        return $result;
    }
    
    private function getConfig()
    {
        return unserialize($this -> _redisAPI -> get($this -> _configName));
    }
    private function setConfig($config)
    {
        $this -> _redisAPI -> set($this -> _configName, serialize($config));
    }
}
