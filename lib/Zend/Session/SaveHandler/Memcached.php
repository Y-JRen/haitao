<?php

/**
 * @see Zend_Session
 */
// require_once 'Zend/Session.php';

/**
 * @see Zend_Config
 */
// require_once 'Zend/Config.php';

class Zend_Session_SaveHandler_Memcached implements Zend_Session_SaveHandler_Interface
{
    const LIFETIME               = 'lifetime';
    const OVERRIDE_LIFETIME      = 'overrideLifetime';
    const MEMCACHED              = 'backend';
    protected $_lifetime         = false;
    protected $_overrideLifetime = false;
    protected $_sessionSavePath;
    protected $_sessionName;
    protected $_memcached;
    
    /**
     * Constructor
     *
     * $config is an instance of Zend_Config or an array of key/value pairs containing configuration options for
     * Zend_Session_SaveHandler_Memcached . These are the configuration options for
     * Zend_Session_SaveHandler_Memcached:
     *
     *
     *      sessionId       => The id of the current session
     *      sessionName     => The name of the current session
     *      sessionSavePath => The save path of the current session
     *
     * modified            => (string) Session last modification time column
     *
     * lifetime          => (integer) Session lifetime (optional; default: ini_get('session.gc_maxlifetime'))
     *
     * overrideLifetime => (boolean) Whether or not the lifetime of an existing session should be overridden
     *      (optional; default: false)
     *
     * @param Zend_Config|array $config      User-provided configuration
     * @return void
     * @throws Zend_Session_SaveHandler_Exception
     */
    public function __construct($config)
    {
        if (!is_array($config)) {
            /**
             * @see Zend_Session_SaveHandler_Exception
             */
            // require_once 'Zend/Session/SaveHandler/Exception.php';

            throw new Zend_Session_SaveHandler_Exception(
                '$config must be an instance of Zend_Config or array of key/value pairs containing '
              . 'configuration options for Zend_Session_SaveHandler_Memcached .');
        }
        foreach ($config as $key => $value) {
            switch ($key) {
                case self::MEMCACHED:
                    $this->createMemcached($value);
                    break;
                case self::LIFETIME:
                    $sysTime = @ini_get('session.gc_maxlifetime');
                    $this->setLifetime(($value > $sysTime) ? $sysTime : $value);
                    break;
                case self::OVERRIDE_LIFETIME:
                    $this->setOverrideLifetime($value);
                    break;
                default:
                    // unrecognized options passed to parent::__construct()
                    break 2;
            }
            unset($config[$key]);
        }
    }
   
    /**
     * create memcached
     * 
     * @param  $config
     * @return void
     */
    public function createMemcached($config){
      $mc = new Memcache;
      $mc->addServer($config['host'], $config['port'], $config['persistent']);
     // $mc->connect($config['host'], $config['port']);
      $this->_memcached = $mc;
    }
    
    /**
     * destruct sesion
     * 
     * @return void
     */
    public function __destruct()
    {
        Zend_Session::writeClose();
    }
    
    /**
     * Set session lifetime and optional whether or not the lifetime of an existing session should be overridden
     *
     * $lifetime === false resets lifetime to session.gc_maxlifetime
     *
     * @param int $lifetime
     * @param boolean $overrideLifetime (optional)
     * @return Zend_Session_SaveHandler_Memcached
     */
    public function setLifetime($lifetime, $overrideLifetime = null)
    {
        if ($lifetime < 0) {
            /**
             * @see Zend_Session_SaveHandler_Exception
             */
            // require_once 'Zend/Session/SaveHandler/Exception.php';
            throw new Zend_Session_SaveHandler_Exception();
        } else if (empty($lifetime)) {
            $this->_lifetime = (int) ini_get('session.gc_maxlifetime');
        } else {
            $this->_lifetime = (int) $lifetime;
        }

        if ($overrideLifetime != null) {
            $this->setOverrideLifetime($overrideLifetime);
        }
        return $this;
    }
    
    /**
     * Retrieve session lifetime
     *
     * @return int
     */
    public function getLifetime()
    {
        return $this->_lifetime;
    }
    
    /**
     * Set whether or not the lifetime of an existing session should be overridden
     *
     * @param boolean $overrideLifetime
     * @return Zend_Session_SaveHandler_Memcached
     */
    public function setOverrideLifetime($overrideLifetime)
    {
        $this->_overrideLifetime = (boolean) $overrideLifetime;
        return $this;
    }
    
    /**
     * Retrieve whether or not the lifetime of an existing session should be overridden
     *
     * @return boolean
     */
    public function getOverrideLifetime()
    {
        return $this->_overrideLifetime;
    }
    
    /**
     * Open Session
     *
     * @param string $save_path
     * @param string $name
     * @return boolean
     */
    public function open($save_path, $name)
    {
        $this->_sessionSavePath = $save_path;
        $this->_sessionName     = $name;
        return true;
    }
    
    /**
     * Close session
     *
     * @return boolean
     */
    public function close()
    {
        return true;
    }
    
    /**
     * Read session data
     *
     * @param string $id
     * @return string
     */
    public function read($id)
    {
        $return = '';
        $value = $this->_memcached->get($id);                        //获取数据

        if ($value) {
            if ($this->_getExpirationTime($value) > time()) {
                $return = $value['data'];
            } else {
                $this->destroy($id);
            }
        }
        return $return;
    }
    
    /**
     * Write session data
     *
     * @param string $id
     * @param string $data
     * @return boolean
     */
    public function write($id, $data)
    {
        $return = false;
        $insertDate = array('modified' => time(),
                              'data'     => (string) $data);
        $value = $this->_memcached->get($id);                        //获取数据

        if ($value) {
            $insertDate['lifetime'] = $this->_getLifetime($value);
            
            if ($this->_memcached->replace($id,$insertDate)) {
                $return = true;
            }
        } else {
            $insertDate['lifetime'] = $this->_lifetime;

            if ($this->_memcached->add($id, $insertDate,false,$this->_lifetime)) {
                $return = true;
            }
        }
        
        return $return;
    }
    
    /**
     * Destroy session
     *
     * @param string $id
     * @return boolean
     */
    public function destroy($id)
    {
        $return = false;

        if ($this->_memcached->delete($id)) {
            $return = true;
        }
        return $return;
    }
    
    /**
     * Garbage Collection
     *
     * @param int $maxlifetime
     * @return true
     */
    public function gc($maxlifetime)
    {
        return true;
    }
    
    /**
     * Retrieve session lifetime considering Zend_Session_SaveHandler_DbTable::OVERRIDE_LIFETIME
     *
     * @param  array  $value
     * @return int
     */
    protected function _getLifetime($value)
    {
        $return = $this->_lifetime;

        if (!$this->_overrideLifetime) {
            $return = (int) $value['lifetime'];
        }
        return $return;
    }
    
    /**
     * Retrieve session expiration time
     *
     * @param  array  $value
     * @return int
     */
    protected function _getExpirationTime($value)
    {
        return (int) $value['modified'] + $this->_getLifetime($value);
    }
}