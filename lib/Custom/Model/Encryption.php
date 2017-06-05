<?php

class Custom_Model_Encryption
{
    /**
     * 对象
     *
     * @var Custom_Model_Encryption
     */
    private static $_instance = null;
    
	/**
     * 数据加密解密对象
     *
     * @var Custom_Model_Cryption_*
     */
    private static $_loaded = array();
    
    /**
     * 获取对象
     *
     * @param    void
     * @return   void
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    
    /**
     * 数据加密
     *
     * @param    string    $data    the data that you want to encrypt
     * @param    string    $type    the encryption type
     * @return   void
     */
    public function encrypt($data, $type = null)
    {
    	if (!$type) {
    		return md5($data);
    	}
    	$object = $this -> getClass($type);
    	
    	if ($object && method_exists($object, 'encrypt')) {
    		return $object -> encrypt($data);
    	}
    }
    
    /**
     * 数据解密
     *
     * @param    string    $data    the data that you want to decrypt
     * @param    string    $type    the encryption type
     * @return   void
     */
    public function decrypt($data, $type = null)
    {
    	$object = $this -> getClass($type);
    	
    	if ($object && method_exists($object, 'decrypt')) {
    		return $object -> decrypt($data);
    	}
    }
    
    /**
     * 取得加密解密类
     *
     * @param    string    $type
     * @return   object
     */
	private function getClass($type)
	{
		if (class_exists('Custom_Model_Cryption_' . ucfirst($type))) {
			$class = 'Custom_Model_Cryption_' . ucfirst($type);
			
			if (array_key_exists($class, self::$_loaded)) {
				return self::$_loaded[$class];
			} else {
		    	self::$_loaded[$class] = new $class();
		    	return self::$_loaded[$class];
			}
		}
	}
}