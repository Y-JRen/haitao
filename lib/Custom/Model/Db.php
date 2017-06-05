<?php

class Custom_Model_Db
{
	/**
     * 只读数据库对象
     *
     * @var Zend_Db
     */
    private $_rdb = null;
    
    /**
     * 只写数据库对象
     *
     * @var Zend_Db
     */
    private $_wdb = null;
    
    /**
     * 缓存对象
     *
     * @var Custom_Model_Cache
     */
    private $_cache = null;
    
    /**
     * 对象初始化
     *
     * @return Shop_Models_API_Auth
     */
    public function __construct($databaseConfig)
    {
        $this -> _wdb = $this -> getAdapter($databaseConfig);
    }
    
    public function select(){
        return $this->_wdb->select();
    }
    
    /**
     * 取得对象实例
     *
     * @param    Zend_Config    $databaseConfig
     * @return   object
     */
    private function getAdapter($databaseConfig)
    {
    	$db = new Zend_Db_Adapter_Pdo_Mysql($databaseConfig -> params -> toArray());
    	
        if ($databaseConfig -> adapter == 'pdo_mysql' && $databaseConfig -> charset) {
        	$db -> query("SET NAMES '".$databaseConfig -> charset."', sql_mode=''");
        }
        Zend_Db_Table::setDefaultAdapter($db);
        return $db;
    }
    
    /**
     * 生成tags
     *
     * @param    string    $sql
     * @return   array
     */
    private function getTags($sql)
    {
    	$analyzed_sql = PMA_SQP_analyze(PMA_SQP_parse($sql));
            
        if ($analyzed_sql[0]['table_ref']) {
            foreach ($analyzed_sql[0]['table_ref'] as $table) {
            	$tags[] = $table['table_true_name'];
            }
        }
        return ($tags) ? $tags : array();
    }
    
    /**
     * 插入数据
     *
     * @param    mixed    $table
     * @param    array    $bind
     * @return   int
     */
    public function insert($table, array $bind)
    {
    	//$this -> _cache -> cleanTag(array($table));
    	return $this -> _wdb -> insert($table, $bind);
    }
    
    /**
     * 更新数据
     *
     * @param    mixed    $table
     * @param    array    $bind
     * @param    mixed    $where
     * @return   int
     */
    public function update($table, array $bind, $where = '')
    {
    	//$this -> _cache -> cleanTag(array($table));
    	return $this -> _wdb -> update($table, $bind, $where);
    }
    
    /**
     * 删除数据
     *
     * @param    mixed    $table
     * @param    mixed    $where
     * @return   int
     */
    public function delete($table, $where = '')
    {
    	//$this -> _cache -> cleanTag(array($table));
    	return $this -> _wdb -> delete($table, $where);
    }
    
    /**
     * 执行SQl
     *
     * @param  string  $sql
     * @param  mixed   $table
     * @return Zend_Db_Statement_Interface
     */
    public function execute($sql, $table = null)
    {
    	//$table && $table = is_array($table) ? $table : array($table);
    	//$this -> _cache -> cleanTag($table);
    	return $this -> _wdb -> query($sql);
    }
    
    /**
     * 取得最后插入ID
     * 
     * @param    string    $tableName
     * @param    string    $primaryKey
     * @return   string
     */
    public function lastInsertId($tableName = null, $primaryKey = null)
    {
        return $this -> _wdb -> lastInsertId($tableName, $primaryKey);
    }
    
    /**
     * 取得所有查询结果
     * 
     * @param string|Zend_Db_Select $sql
     * @param mixed                 $bind
     * @param mixed                 $fetchMode
     * @return array
     */
    public function fetchAll($sql, $bind = array(), $fetchMode = null)
    {
    	//$data = $this -> _cache -> load(md5('fetchAll__' . $sql));
    	
    	//if ($data === false) {
    		$data = $this -> _wdb -> fetchAll($sql, $bind, $fetchMode);
    	//	$data === false && $data = '';
    	//    $this -> _cache -> save($data, $this -> getTags($sql));
    	//}
    	return $data;
    }

    /**
     * 取得查询结果的第一行并作为枚举数组返回
     * 
     * @param string|Zend_Db_Select $sql
     * @param mixed                 $bind
     * @param mixed                 $fetchMode
     * @return array
     */
    public function fetchRow($sql, $bind = array(), $fetchMode = null)
    {
    	//$data = $this -> _cache -> load(md5('fetchRow__' . $sql));
    	
    	//if ($data === false) {
    		$data = $this -> _wdb -> fetchRow($sql, $bind, $fetchMode);
    	//	$data === false && $data = '';
    	//    $this -> _cache -> save($data, $this -> getTags($sql));
    	//}
    	return $data;
    }

    /**
     * 取得查询结果的第一行并作为关联数组返回
     * 
     * @param string|Zend_Db_Select $sql
     * @param mixed                 $bind
     * @return string
     */
    public function fetchAssoc($sql, $bind = array())
    {
    	//$data = $this -> _cache -> load(md5('fetchAssoc__' . $sql));
    	
    	//if ($data === false) {
    		$data = $this -> _wdb -> fetchAssoc($sql, $bind);
    	//	$data === false && $data = '';
    	//    $this -> _cache -> save($data, $this -> getTags($sql));
    	//}
    	return $data;
    }

    /**
     * 取得查询结果的第一列
     * 
     * @param string|Zend_Db_Select $sql
     * @param mixed                 $bind
     * @return array
     */
    public function fetchCol($sql, $bind = array())
    {
    	//$data = $this -> _cache -> load(md5('fetchCol__' . $sql));
    	
    	//if ($data === false) {
    		$data = $this -> _wdb -> fetchCol($sql, $bind);
    	//	$data === false && $data = '';
    	//    $this -> _cache -> save($data, $this -> getTags($sql));
    	//}
        return $data;
    }

    /**
     * 取得查询结果并作为关联数组返回
     * 
     * @param string|Zend_Db_Select $sql
     * @param mixed                 $bind
     * @return string
     */
    public function fetchPairs($sql, $bind = array())
    {
    	//$data = $this -> _cache -> load(md5('fetchPairs__' . $sql));
    	
    	//if ($data === false) {
    		$data = $this -> _wdb -> fetchPairs($sql, $bind);
    	//	$data === false && $data = '';
    	//    $this -> _cache -> save($data, $this -> getTags($sql));
    	//}
        return $data;
    }

    /**
     * 取得查询结果的第一行第一列
     *
     * @param string|Zend_Db_Select $sql
     * @param mixed                 $bind.
     * @return string
     * 
     */
    public function fetchOne($sql, $bind = array())
    {
    	//$data = $this -> _cache -> load(md5('fetchOne__' . $sql));
    	
    	//if ($data === false) {
    		$data = $this -> _wdb -> fetchOne($sql, $bind);
    	//	$data === false && $data = '';
    	//    $this -> _cache -> save($data, $this -> getTags($sql));
    	//}
        return $data;
    }
    
    /**
     * 添加引号
     * 
     * @param    mixed    $value
     * @param    mixed    $type
     * @return   mixed
     */
    public function quote($value, $type = null)
    {
        return $this -> _wdb -> quote($value, $type);
    }

    /**
     * 占位符方式添加引号
     * 
     * @param    string    $text
     * @param    mixed     $value
     * @param    string    $type
     * @param    integer   $count
     * @return   string
     */
    public function quoteInto($text, $value, $type = null, $count = null)
    {
        return $this -> _wdb -> quoteInto($text, $value, $type, $count);
    }
    
    /**
     * 返回数据库对象
     * 
     * @return   object
     */
    public function getDB()
    {
    	return $this -> _wdb;
    }
}