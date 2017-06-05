<?php

class Admin_Models_DB_Privilege
{
	/**
     * Zend_Db
     * 
     * @var    Zend_Db
     */
	private $_db = null;
	
	/**
     * 权限表名
     * 
     * @var    string
     */
	private $_table = 'privilege';
	
	/**
     * 对象初始化
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = Zend_Registry::get('db');
	}
	
	/**
     * 更新权限列表
     *
     * @param    array    $data
     * @return   int      lastInsertId
     */
	public function editPrivilege($data)
	{
		if ($this -> getPrivilege($data['mod'], $data['ctl'], $data['act'])) {
			$set = array ('title' => $data['title']);

			$where = $this -> _db -> quoteInto('`mod` = ?', $data['mod']);
		    
		    if ($data['ctl']) {
			    $where .= $this -> _db -> quoteInto(' AND `ctl` = ?', $data['ctl']);
		    } else {
				$where .= " AND `ctl` IS NULL";
			}
		    
		    if ($data['act']) {
			    $where .= $this -> _db -> quoteInto(' AND `act` = ?', $data['act']);
		    } else {
				$where .= " AND `act` IS NULL";
			}
			
			return $this -> _db -> update($this -> _table, $set, $where);
		} else {
			$row = array (
                          'mod' => $data['mod'],
                          'ctl' => $data['ctl'],
                          'act' => $data['act'],
                          'title' => $data['title']
                          );
			$this -> _db -> insert($this -> _table, $row);
		    return $this -> _db -> lastInsertId();
		}
	}
	
	/**
     * 删除权限列表
     *
     * @param    array    $privileges
     * @return   int      lastInsertId
     */
	public function deletePrivilege($privileges)
	{
		foreach ($privileges as $key => $value)
		{
			$where = "`mod`='" . $value['mod'] . "'";
			
			if ($value['ctl']) {
				$where .= " AND `ctl`='" . $value['ctl'] . "'";
			} else {
				$where .= " AND `ctl` IS NULL";
			}
			
			if ($value['act']) {
				$where .= " AND `act`='" . $value['act'] . "'";
			} else {
				$where .= " AND `act` IS NULL";
			}
			
			$this -> _db -> delete($this -> _table, $where);
		}
		 return true;
	}
	
	/**
     * 取得所有有效权限.
     *
     * @param    void
     * @return   array
     */
	public function getAllPrivilege()
	{
			return $this -> _db -> fetchAll('SELECT * FROM `' . $this -> _table . '`');
	}
	
	/**
     * 取得指定权限
     *
     * @param    string    $mod
     * @param    string    $ctl
     * @param    string    $act
     * @param    string    $privilegeId
     * @return   int
     */
	public function getPrivilege($mod, $ctl = null, $act = null, $privilegeId = null)
	{
		if (!$privilegeId) {
		    $where = 'WHERE 1=1';
		    $where .= $this -> _db -> quoteInto(' AND `mod` = ?', $mod);
		
		    if ($ctl) {
			    $where .= $this -> _db -> quoteInto(' AND `ctl` = ?', $ctl);
		    }
		
		    if ($act) {
			    $where .= $this -> _db -> quoteInto(' AND `act` = ?', $act);
		    }
		} else {
			$where = $this -> _db -> quoteInto('WHERE `privilege_id` IN(?)', is_array($privilegeId) ? $privilegeId : explode(',', $privilegeId));
		}
		
		$sql = 'SELECT * FROM `' . $this -> _table . '` ' . $where . "ORDER BY ctl,act";
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 取得指定权限ID
     *
     * @param    string    $mod
     * @param    string    $ctl
     * @param    string    $act
     * @param    string    $privilegeId
     * @return   int
     */
	public function getPrivilegeId($mod, $ctl = null, $act = null, $privilegeId = null)
	{
		if (!$privilegeId) {
		    $where = 'WHERE 1=1';
		    $where .= $this -> _db -> quoteInto(' AND `mod` = ?', $mod);
		
		    if ($ctl) {
			    $where .= $this -> _db -> quoteInto(' AND `ctl` = ?', $ctl);
		    }
		
		    if ($act) {
			    $where .= $this -> _db -> quoteInto(' AND `act` = ?', $act);
		    }
		} else {
			$where = $this -> _db -> quoteInto('WHERE `privilege_id` IN(?)', is_array($privilegeId) ? $privilegeId : explode(',', $privilegeId));
		}
		
		$sql = 'SELECT privilege_id FROM `' . $this -> _table . '` ' . $where;
		return $this -> _db -> fetchCol($sql);
	}
	
	/**
     * 取得指定唯一权限
     *
     * @param    string    $mod
     * @param    string    $ctl
     * @param    string    $act
     * @return   int
     */
	public function getOnePrivilege($mod = null, $ctl = null, $act = null)
	{
		if ($mod && !$ctl && !$act) {
		    $where = "WHERE `mod`='" . $mod . "' AND `ctl` IS NULL AND `act` IS NULL";
		} elseif ($mod && $ctl && !$act) {
		    $where = "WHERE `mod`='" . $mod . "' AND `ctl`='" . $ctl . "' AND `act` IS NULL";
		} elseif ($mod && $ctl && $act) {
		    $where = "WHERE `mod`='" . $mod . "' AND `ctl`='" . $ctl . "' AND `act`='" . $act . "'";
		}
		
		$sql = 'SELECT * FROM `' . $this -> _table . '` ' . $where;
		return $this -> _db -> fetchRow($sql);
	}
}