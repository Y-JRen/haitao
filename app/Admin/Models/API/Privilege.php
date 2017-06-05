<?php

class Admin_Models_API_Privilege
{
	/**
     * Cache文件
     * 
     * @var string
     */
    private $_cacheFile = 'cache.privilege';
    
	/**
     * 权限管理 DB
     * 
     * @var Admin_Models_DB_Privilege
     */
	private $_db = null;
	
	/**
     * 已无效的权限
     */
	const NO_USED = '[已无效]';
	
	/**
     * 未启用的权限
     */
	const UN_USED = '[未启用]';
	
	/**
     * 对象初始化
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$front = Zend_Controller_Front::getInstance();
		$this -> _module = $front -> getRequest() -> getModuleName();
		$this -> _cacheFile = realpath(Zend_Registry::get('config') -> sytem_cache -> dir) . '/' . $this -> _cacheFile;
		$this -> _db = new Admin_Models_DB_Privilege();
	}
	
	/**
     * 取得权限控制文件信息
     *
     * @param    bool    $cache
     * @return   array
     */
	public function getFilePrivilege($cache = true)
	{
		if ($cache == true) {
			!file_exists($this -> _cacheFile) && $this -> cachePrivilegeFile();
			return Zend_Json::decode(file_get_contents($this -> _cacheFile));
		}
		
		$module = $this -> _module;
		$modulePath = Zend_Registry::get('systemRoot') . '/app/' . ucfirst($module) . '/Controllers';
		$dir = new DirectoryIterator($modulePath);
		$i = 0;
		$filePrivilege[$i]['mod'] = $filePrivilege[$i]['title'] = $module;
		
		
		foreach ($dir as $file)
        {
            if ($file -> isFile()) {
            	$files[$file -> getFilename()] = $modulePath  . '/' . $file -> getFilename();
            }
        }
        
        ksort($files);
        foreach($files as $k => $filePath){
        	$i++;
    	    $content = file_get_contents($filePath);
	        $filePrivilege[$i]['mod'] = $filePrivilege[$i]['title'] = $module;
	        
	        if (preg_match('/class\s+([^\s]*)Controller/is', $content, $classMatch)) {
		        $class = trim(array_pop(explode('_', $classMatch[1])));
		        $filePrivilege[$i]['ctl'] = $filePrivilege[$i]['title'] = $class;
                
                if (preg_match_all('/function\s+([^(]*)Action\(/is', $content, $actionMatch)) {
                	
                    foreach($actionMatch[1] as $action)
                    {
                    	$i++;
                    	$action = trim($action);
                    	$filePrivilege[$i]['mod'] = $module;
                    	$filePrivilege[$i]['ctl'] = $class;
                    	$filePrivilege[$i]['act'] = $filePrivilege[$i]['title'] = $action;
                    }
                }
	        }
        }
        
        return $filePrivilege;
	}
	
	/**
     * 取得有效权限
     *
     * @param    string    $mod
     * @param    string    $ctl
     * @param    string    $act
     * @param    int       $privilegeId
     * @return   array
     */
	public function getDatabasePrivilege($mod = null, $ctl = null, $act = null, $privilegeId = null)
	{
		($privilegeId) ? $mod = $ctl = $act = null : '';
		$databasePrivileges = $this -> _db -> getPrivilege($mod, $ctl, $act, $privilegeId);
        return $databasePrivileges;
	}
	
	/**
     * 取得权限并转化为JSON
     *
     * @param    string   $type
     * @param    array    $checkedIdArray
     * @return   string
     */
	public function getJsonPrivilege($type, $paramArray = null)
	{
		if ($type == 'file') {
			$privileges = $this -> getFilePrivilege();
		} elseif ($type == 'database') {
			$mod = ($paramArray['mod']) ? $paramArray['mod'] : $this -> _module;
			$privileges = $this -> getDatabasePrivilege($mod, $paramArray['ctl'], $paramArray['act'], $paramArray['id']);
			
			if ($paramArray['id']) {
				//如果指定权限ID,则取得该ID相关权限树
				$privileges = $this -> getTreePrivilege($privileges);
			}
		}
		$checkedIdArray = $paramArray['selectId'] ? explode(',', $paramArray['selectId']) : array();
		
		if ($privileges) {
			$i = 0;
			
			foreach ($privileges as $key => $value)
			{
				$i++;
				if ($value['ctl'] && $value['act']) {
					$privilegeArray[$value['mod']]['data'][$value['ctl']]['data'][$value['act']]['title'] = array('id' => ($value['privilege_id']) ? $value['privilege_id'] : $i, 'title' => $value['title']);
				} elseif ($value['ctl']) {
					$privilegeArray[$value['mod']]['data'][$value['ctl']]['title'] = array('id' => ($value['privilege_id']) ? $value['privilege_id'] : $i, 'title' => $value['title']);
				} else {
					$privilegeArray[$value['mod']]['title'] = array('id' => ($value['privilege_id']) ? $value['privilege_id'] : $i, 'title' => $value['title']);
				}
			}
			
			$privilege = array('id' => 0, 'item' => array(array()));
			$i = 0;
			
			foreach ($privilegeArray as $mod => $privilegeMod)
			{
				if ($privilegeMod['title']) {
				    $privilege['item'][0] = array('id' => $privilegeMod['title']['id'], 'text' => $privilegeMod['title']['title'], 'tooltip' => $mod, 'open' => 1, 'item'=>array(), 'checked' => in_array($privilegeMod['title']['id'], $checkedIdArray) ? true : false);
				}
				if ($privilegeMod['data']) {
					
					foreach ($privilegeMod['data'] as $ctl => $privilegeCtl)
					{
						if ($privilegeCtl['title']) {
							$privilege['item'][0]['item'][$i] = array('id' => $privilegeCtl['title']['id'], 'text' => $privilegeCtl['title']['title'], 'tooltip' => $ctl, 'checked' => in_array($privilegeCtl['title']['id'], $checkedIdArray) ? true : false);
						}
						if ($privilegeCtl['data']) {
							$j = 0;
							
							foreach ($privilegeCtl['data'] as $act => $privilegeAct)
							{
								if ($privilegeAct['title']) {
							        $privilege['item'][0]['item'][$i]['item'][$j] = array('id' => $privilegeAct['title']['id'], 'text' => $privilegeAct['title']['title'], 'tooltip' => $act, 'checked' => in_array($privilegeAct['title']['id'], $checkedIdArray) ? true : false);
						            $j++;
						        }
							}
						}
						$i++;
					}
				}
			}
		}
        return Zend_Json::encode($privilege);
	}
	
	/**
     * 取得混合权限信息并转化为JSON
     *
     * @return   string
     */
	public function getJsonMixedPrivilege()
	{
		$filePrivileges = $this -> getFilePrivilege(false);
		$databasePrivileges = $this -> getDatabasePrivilege($this -> _module);
        
		foreach ($filePrivileges as $key => $value)
		{
			if ($value['ctl'] && $value['act']) {
				$filePrivilege[$value['mod']][$value['ctl']][$value['act']] = '';
				$filePrivilegeTitle[$value['mod']][$value['ctl']][$value['act']]['title'] = $value['title'];
			} elseif ($value['ctl']) {
			    if (!$filePrivilege[$value['mod']][$value['ctl']]) {
				    $filePrivilege[$value['mod']][$value['ctl']] = array();
				}
				$filePrivilegeTitle[$value['mod']][$value['ctl']]['title'] = $value['title'];
			} else {
			    if (!$filePrivilege[$value['mod']]) {
			        $filePrivilege[$value['mod']] = array();
			    }
				
				$filePrivilegeTitle[$value['mod']]['title'] = $value['title'];
			}
		}

		foreach ($databasePrivileges as $key => $value)
		{
			if ($value['ctl'] && $value['act']) {
				$databasePrivilege[$value['mod']][$value['ctl']][$value['act']] = '';
				$databasePrivilegeTitle[$value['mod']][$value['ctl']][$value['act']]['title'] = $value['title'];
			} elseif ($value['ctl']) {
				$databasePrivilege[$value['mod']][$value['ctl']] = array();
				$databasePrivilegeTitle[$value['mod']][$value['ctl']]['title'] = $value['title'];
			} else {
				$databasePrivilege[$value['mod']] = array();
				$databasePrivilegeTitle[$value['mod']]['title'] = $value['title'];
			}
		}
		
		$mixedPrivileges = $this -> arrayMergeKey($filePrivilege, $databasePrivilege);
		
		if ($filePrivileges) {
			$i = 0;
			
		    foreach ($mixedPrivileges as $mod => $privilegeMod)
		    {
		    	$i++;
		    	if ($databasePrivilegeTitle[$mod]['title'] && $filePrivilegeTitle[$mod]['title']) {
		    		$title = $databasePrivilegeTitle[$mod]['title'];
		    	} elseif ($databasePrivilegeTitle[$mod]['title']) {
		    		$title = $databasePrivilegeTitle[$mod]['title'] . ' '. self::NO_USED;
		    	} elseif ($filePrivilegeTitle[$mod]['title']) {
		    		$title = $filePrivilegeTitle[$mod]['title'] . ' '. self::UN_USED;
		    	}
			    $privilegeArray[$mod]['title'] = array('id' => $i, 'title' => $title);
			    
			    foreach ($privilegeMod as $ctl => $privilegeCtl)
			    {
			    	$i++;
			    	if ($databasePrivilegeTitle[$mod][$ctl]['title'] && $filePrivilegeTitle[$mod][$ctl]['title']) {
		    		    $title = $databasePrivilegeTitle[$mod][$ctl]['title'];
		    	    } elseif ($databasePrivilegeTitle[$mod][$ctl]['title']) {
		    		    $title = $databasePrivilegeTitle[$mod][$ctl]['title'] . ' '. self::NO_USED;
		    	    } elseif ($filePrivilegeTitle[$mod][$ctl]['title']) {
		    		    $title = $filePrivilegeTitle[$mod][$ctl]['title'] . ' '. self::UN_USED;
		    	    }
			    	$privilegeArray[$mod]['data'][$ctl]['title'] = array('id' => $i, 'title' => $title);
			    	
			    	foreach ($privilegeCtl as $act => $privilegeAct)
			    	{
			    		$i++;
			    		if ($databasePrivilegeTitle[$mod][$ctl][$act]['title'] && $filePrivilegeTitle[$mod][$ctl][$act]['title']) {
		    		        $title = $databasePrivilegeTitle[$mod][$ctl][$act]['title'];
		    	        } elseif ($databasePrivilegeTitle[$mod][$ctl][$act]['title']) {
		    		        $title = $databasePrivilegeTitle[$mod][$ctl][$act]['title'] . ' '. self::NO_USED;
		    	        } elseif ($filePrivilegeTitle[$mod][$ctl][$act]['title']) {
		    		        $title = $filePrivilegeTitle[$mod][$ctl][$act]['title'] . ' '. self::UN_USED;
		    	        }
			    		$privilegeArray[$mod]['data'][$ctl]['data'][$act]['title'] = array('id' => $i, 'title' => $title);
			    	}
			    }
		    }
		}
		
		$privilege = array('id' => 0, 'item' => array(array()));
		$i = 0;
		
		foreach ($privilegeArray as $mod => $privilegeMod)
		{
			if ($privilegeMod['title']) {
				 $privilege['item'][0] = array('id' => $privilegeMod['title']['id'], 'text' => $privilegeMod['title']['title'], 'tooltip' => $mod, 'open' => 1, 'item'=>array());
			}
			if ($privilegeMod['data']) {
				
				foreach ($privilegeMod['data'] as $ctl => $privilegeCtl)
				{
					if ($privilegeCtl['title']) {
						$privilege['item'][0]['item'][$i] = array('id' => $privilegeCtl['title']['id'], 'text' => $privilegeCtl['title']['title'], 'tooltip' => $ctl);
					}
					if ($privilegeCtl['data']) {
						$j = 0;
						
						foreach ($privilegeCtl['data'] as $act => $privilegeAct)
						{
							if ($privilegeAct['title']) {
							    $privilege['item'][0]['item'][$i]['item'][$j] = array('id' => $privilegeAct['title']['id'], 'text' => $privilegeAct['title']['title'], 'tooltip' => $act);
						        $j++;
						    }
						}
					}
					$i++;
				}
			}
		}
		
        return Zend_Json::encode($privilege);
	}
	
	/**
     * 按键值递归合并数组
     *
     * @param    array    $arr1
     * @param    array    $arr2
     * @return   array
     */
	private function arrayMergeKey($arr1, $arr2) {
		
        foreach($arr2 as $k => $v) {
            if (!array_key_exists($k, $arr1)) {
                $arr1[$k] = $v;
            }
            else {
                if (is_array($v)) {
                    $arr1[$k] = $this -> arrayMergeKey($arr1[$k], $arr2[$k]);
                }
            }
        }
        
        return $arr1;
    }
	
	/**
     * 添加编辑权限列表
     *
     * @param    array    $data
     * @return   string
     */
	public function editPrivilege($data)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        
        if ($data['id'] == '') {
			return 'noPrivilegeSelect';
		}
		
		$result = $this -> _db -> editPrivilege($data);
		$this -> cachePrivilegeDb();
		
		if (is_numeric($result) && $result > 0) {
		    return 'editPrivilegeSucess';
		} else {
			return 'error';
		}
	}
	
	/**
     * 删除编辑权限列表
     *
     * @param    string    $string
     * @return   string
     */
	public function deletePrivilege($string)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim())
                     -> addFilter(new Zend_Filter_StripTags());
                     
        $string = Custom_Model_Filter::filterArray($string, $filterChain);
        
        $urlStringArray = explode(',', $string);
        foreach ($urlStringArray as $key => $urlString)
        {
            $privilege = explode('|', $urlString);
            foreach ($privilege as $j => $value)
			{
				if ($j == 0) {
					$type = 'mod';
				} elseif ($j == 1) {
					$type = 'ctl';
				} elseif ($j == 2) {
					$type = 'act';
				}
				$privileges[$key][$type] = $value;
			}
        }
		
		$result = $this -> _db -> deletePrivilege($privileges);
		
		$this -> clearDbPrivilege();
		
		if ($result == true) {
		    return 'deletePrivilegeSucess';
		} else {
			return 'error';
		}
	}
	
	/**
     * 生成权限文件缓存文件
     *
     * @param    void
     * @return   void
     */
	public function cachePrivilegeFile()
	{
    	$jsonFilePrivilege = Zend_Json::encode($this -> getFilePrivilege(false));
    	return file_put_contents($this -> _cacheFile, $jsonFilePrivilege);
	}
	
	/**
     * 生成数据库权限缓存文件
     *
     * @return   void
     */
	public function cachePrivilegeDb()
	{
		$allPrivilege = $this -> _db -> getAllPrivilege();
		
		foreach ($allPrivilege as $key => $p)
		{
			if($p['act']){
			    $k = $p['mod'].'-'.$p['ctl'].'-'.$p['act'];
			    $privilegeArray[$k] = $p['privilege_id'];
			}
		}
		$jsonFilePrivilege = Zend_Json::encode($privilegeArray);
		return file_put_contents($this -> _cacheFile . '.db', $jsonFilePrivilege);
	}
	
	/**
     * 清理权限ID
     *
     * @param    string    $privilege
     * @param    int       $groupId
     * @return   string
     */
	public function clearPrivilege($privilege, $groupId = null)
	{
		$privilege = explode(',', $privilege);
		
		$allprivilege = $this -> _db -> getPrivilege($this -> _module);
		
		foreach ($allprivilege as $privileges)
		{
			if ($privileges['act']) {
				$privilegeAct[] = $privileges['privilege_id'];
			}
		}
		$privilegeAct && $privilege = array_intersect($privilege, $privilegeAct);
		
		if ($groupId > 0) {
			$adminDb = new Admin_Models_DB_Admin();
			$adminPrivilegeArray = $adminDb -> getAdmin(array('A.group_id' => $groupId));
			
			foreach ($adminPrivilegeArray as $orgAdminPrivilege)
			{
				$adminPrivilege = array_intersect($privilege, explode(',', $orgAdminPrivilege['privilege']));
				
				if ($adminPrivilege && $adminPrivilege != explode(',', $orgAdminPrivilege['privilege'])) {
				    $adminDb -> updateAdmin(array('privilege' => implode(',', $adminPrivilege)), $orgAdminPrivilege['admin_id']);
			    }
			    
			    unset($adminPrivilege, $orgAdminPrivilege);
			}
		}
		
		/*
		foreach($privilege as $key => $privilegeId)
		{
			$modFlag = $ctlFlag = true;
			
			if (!$privilegeData['ctl'] && !$privilegeData['act']) {
				$allprivilege = $this -> _db -> getPrivilegeId($privilegeData['mod']);
				foreach ($allprivilege as $key => $privilegeId)
				{
					if (!in_array($privilegeId, $privilege)) {
						$privilege = array_diff($privilege, array($privilegeData['privilege_id']));
						$modFlag = false;
						break;
					}
					
					if ($privilegeData['privilege_id'] != $privilegeId) {
					    $privilegeModId[$key] = $privilegeId;
					}
				}
				
				$modFlag && $privilege = array_diff($privilege, $privilegeModId);
			} elseif (!$privilegeData['act']) {
				$allprivilege = $this -> _db -> getPrivilegeId($privilegeData['mod'], $privilegeData['ctl']);
				
				foreach ($allprivilege as $key => $privilegeId)
				{
					if (!in_array($privilegeId, $privilege)) {
						$privilege = array_diff($privilege, array($privilegeData['privilege_id']));
						$ctlFlag = false;
						break;
					}
					
					if ($privilegeData['privilege_id'] != $privilegeId) {
					    $privilegeCtlId[$key] = $privilegeId;
					}
				}
				
				$ctlFlag && $privilege = array_diff($privilege, $privilegeCtlId);
			}
		}
		*/
		
        return implode(',', $privilege);
	}
	
	/**
     * 清理数据库权限
     *
     * @param    void
     * @return   void
     */
	public function clearDbPrivilege()
	{
		$allprivilegeId = $this -> _db -> getPrivilegeId($this -> _module);
		
		$groupDb = new Admin_Models_DB_AdminGroup();
		$adminDb = new Admin_Models_DB_Admin();
		
		$groupPrivilegeArray = $groupDb -> getGroup();
		
		foreach ($groupPrivilegeArray as $orgGroupPrivilege)
		{
			$groupPrivilege = array_intersect($allprivilegeId, explode(',', $orgGroupPrivilege['privilege']));
			
			if ($groupPrivilege && $groupPrivilege != explode(',', $orgGroupPrivilege['privilege'])) {
				$orgGroupPrivilege['group_id'] > 0 && $groupDb -> updateGroup(array('privilege' => implode(',', $groupPrivilege)), $orgGroupPrivilege['group_id']);
			}
			
			$adminPrivilegeArray = $adminDb -> getAdmin(array('A.group_id' => $orgGroupPrivilege['group_id']));
			
			foreach ($adminPrivilegeArray as $orgAdminPrivilege)
			{
				$adminPrivilege = array_intersect($groupPrivilege, explode(',', $orgAdminPrivilege['privilege']));
				
				if ($adminPrivilege && $adminPrivilege != explode(',', $orgAdminPrivilege['privilege'])) {
				    $orgAdminPrivilege['admin_id'] > 0 && $adminDb -> updateAdmin(array('privilege' => implode(',', $adminPrivilege)), $orgAdminPrivilege['admin_id']);
			    }
			    
			    unset($adminPrivilege, $orgAdminPrivilege);
			}
			
			unset($groupPrivilege, $orgGroupPrivilege, $adminPrivilegeArray);
		}
	}
	
	/**
     * 取得权限相关权限树
     *
     * @param    array    $privilege
     * @return   array
     */
	public function getTreePrivilege($privileges)
	{
		$allprivileges = $this -> _db -> getPrivilege($this -> _module);
				
		foreach ($allprivileges as $value)
		{
			$privilegesOrgArray[$value['mod'] . '_' . $value['ctl'] . '_' . $value['act']] = $value;
			unset($value);
		}
				
		unset($value);
				
		foreach ($privileges as $value)
		{
			if ($value['ctl'] && $value['act']) {
				$ctl[$value['ctl']] = $value['ctl'];
			} elseif ($value['ctl'] && !$value['act']) {
				$ctlSingle[$value['ctl']] = $value['ctl'];
			}
					
			$privilegesOrg[$value['mod'] . '_' . $value['ctl'] . '_' . $value['act']] = $value;
				unset($value);
		}
				
		if ($ctl) {
			foreach ($ctl as $value)
			{
				$privilegesOrg[$this -> _module . '_' . $value . '_'] = $privilegesOrgArray[$this -> _module . '_' . $value . '_'];
				unset($value);
			}
		}
				
		if ($ctlSingle) {
			foreach ($ctlSingle as $value)
			{
				foreach ($allprivileges as $values)
				{
					if (strpos($values['mod'] . '_' . $values['ctl'] . '_' . $values['act'], $this -> _module . '_' . $value . '_') !== false) {
						$privilegesOrg[$this -> _module . '_' . $values['ctl'] . '_' . $values['act']] = $privilegesOrgArray[$values['mod'] . '_' . $values['ctl'] . '_' . $values['act']];
					}
				}
			}
		}
				
		$privilegesOrg[$this -> _module . '__'] = $privilegesOrgArray[$this -> _module . '__'];
		$privileges = array_values($privilegesOrg);
		return $privileges;
	}
}