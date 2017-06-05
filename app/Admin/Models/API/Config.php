<?php

class Admin_Models_API_Config
{
	/**
     * 系统配置 DB
     * 
     * @var Admin_Models_DB_Config
     */
	private $_db = null;
	
	/**
     * 文件上传目录
     * 
     * @var string
     */
	private $_upPath = 'upload/admin';
	
	private $_shopConfigFile = 'data/ShopConfig.php';
	
	/**
     * 对象序列化
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = new Admin_Models_DB_Config();
		
	}
	
	/**
     * 取得所有系统设置
     *
     * @return   array
     */
	public function getAllConfig()
	{
		return $this -> _db -> getConfig();
	}
	
	public function makeConfigFile()
	{
		$configs = $this -> _db -> getConfig('WHERE parent_id!=0');
		$content = "<?php\n";
		$content .= "class ShopConfig\n{\n";
		$content .= "	public static function getShopConfig()\n";
		$content .= "	{\n";
		
		if ($configs) {
			foreach ($configs as $key => $config)
			{
				$content .= '		$shopConfig[\'' . $config['name'] . '\']=\'' . $config['value'] . "';		#". $config['title'] ."\n";
			}
		}
		$content .= "		return \$shopConfig;\n";
		$content .= "	}\n";
		$content .= "}\n";
		@file_put_contents(Zend_Registry::get('systemRoot') . '/' . $this -> _shopConfigFile, $content);
	}
	
	/**
     * 取得指定ID的系统设置
     *
     * @param    int    $id
     * @return   array
     */
	public function getConfigById($id)
	{
		$config = $this -> _db -> getConfig(array('config_id' => $id));
		return array_shift($config);
	}
	
	/**
     * 取得指定系统设置ID的子系统设置
     *
     * @param    int    $id
     * @return   array
     */
	public function getChildConfigById($id)
	{
		return $this -> _db -> getConfig(array('parent_id' => (int)$id));
	}
	
	/**
     * 取得指定选项名的系统设置
     *
     * @param    string    $name
     * @return   array
     */
	public function getConfigByName($name)
	{
		$config = $this -> _db -> getConfig(array('name' => $name));
		return array_shift($config);
	}
	
	/**
     * 取得所有系统设置分类
     *
     * @param    void
     * @return   array
     */
	public function getAllCate()
	{
		$configs = $this -> getChildConfigById(0);
		
		if ($configs) {
		    foreach ($configs as $config)
		    {
			    $cate[$config['config_id']] = $config['title'];
		    }
		    return $cate;
		}
	}
	
	/**
     * 添加/编辑系统设置
     *
     * @param    array    $data
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function editConfig($data, $id = null)
	{
	    $filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim());
                     
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
	    
		if ($data['name'] == '') {
			return 'noConfigName';
		}
		
		if ($data['title'] == '') {
			return 'noConfigTitle';
		}
		
		$typeOptionExists = false;
		if ($data['type'] == 'radio' || $data['type'] == 'checkbox' || $data['type'] == 'select') {
			foreach ($data['type_key'] as $key => $value)
			{
				if (strlen($value) > 0 && strlen($data['type_key'][$key]) > 0) {
					$typeOptionExists = true;
					$typeOptions[$value] = $data['type_value'][$key];
				}
			}
			
			if ($typeOptionExists == false) {
				return 'noConfigTypeOptions';
			}
			
			$data['type_options'] = serialize($typeOptions);
		}
		
		$config = $this -> getConfigByName($data['name']);
		
		if ($id == null) {
			
			if ($config) {
			    return 'configExists';
		    }
		    
		    $result = $this -> _db -> addConfig($data);
		} else {
			
			if ($config && $config['config_id'] != $id) {
			    return 'configExists';
		    }
		    
			$exists = $this -> getConfigById($id);
			
			if (!$exists) {
			    return 'configNoExists';
		    }
			
			$result = $this -> _db -> editConfig($data, (int)$id);
		}
		
		if (is_numeric($result) && $result >= 0) {
		    return ($id == null) ? 'addConfigSucess' : 'editConfigSucess';
		} else {
			return 'error';
		}
	}
	
	/**
     * 更新系统设置
     *
     * @param    array    $data
     * @return   array
     */
	public function updateConfig($data)
	{
		$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StringTrim());
        
        $data = Custom_Model_Filter::filterArray($data, $filterChain);
        
        $upload = new Zend_File_Transfer_Adapter_Http();
        Custom_Model_File::makeDir($this -> _upPath . date('/Y/m/d/'));
        $upload -> setDestination($this -> _upPath);
        $upload -> addFilter('LowerCase');
		$files = $upload -> getFileInfo();
		
		foreach ($files as $file => $info)
		{
			if ($data['delete_' . $file] == '1') {
            	$config = $this -> getConfigByName($file);
            	$data[$file] = '';
            	@unlink(realpath($_SERVER['DOCUMENT_ROOT'] . Zend_Controller_Front::getInstance() -> getBaseUrl() . '/' . $config['value']));
            }
            
			if ($upload -> isUploaded($file)) {
				if (!$upload -> isValid($file)) {
				    return 'noValid';
			    }
			    
				$config = $this -> getConfigByName($file);
        		if ($config['value']) {
        			@unlink(realpath($_SERVER['DOCUMENT_ROOT'] . Zend_Controller_Front::getInstance() -> getBaseUrl() . '/' . $config['value']));
        		}
        		$imgPath = $this -> _upPath . date('/Y/m/d/') . md5(microtime() . $upload->getFileName($file) . mt_rand(0,100)) . strtolower(strrchr($upload->getFileName($file), '.'));
        		$upload -> setFilters(array('Rename' => $imgPath));
        		if (!$upload -> receive($file)) {
			        $messages = $upload -> getMessages();
			        return implode("\n", $messages);
		        }
		        
        		$imgUrl = Zend_Controller_Front::getInstance() -> getBaseUrl() . $imgPath;
        		$data[$file] = $imgUrl;
			}
		}
        
        if ($data) {
		    $this -> _db -> updateConfig($data);
        }
	}
	
	/**
     * 删除指定系统配置
     *
     * @param    int    $id
     * @return   void
     */
	public function deleteConfigById($id)
	{
		if ((int)$id > 0) {
			$config = $this -> getConfigById($id);
			if ($config) {
				$result = $this -> _db -> deleteConfig((int)$id);
				if (is_numeric($result) && $result > 0) {
		    	    if ($config['type'] == 'file' && $config['value']) {
				        @unlink(realpath($_SERVER['DOCUMENT_ROOT'] . Zend_Controller_Front::getInstance() -> getBaseUrl() . '/' . $config['value']));
			        }
		            return 'deleteConfigSucess';
		        } else {
			        return 'error';
		        }
			}
		}
	}
	
	/**
     * 取得系统设置并生成表单控件
     *
     * @param    void
     * @return   array
     */
	public function getOptionFrom()
	{
		$options = $this -> getAllConfig();
		if ($options) {
			foreach ($options as $option)
			{
				if ($option['type'] != 'hidden') {
					$result[$option['parent_id']][$option['config_id']] = array('title' => $option['title'],
					                                                            'notice' => $option['notice']);
				}
				switch ($option['type'])
				{
					case 'text':
					    $result[$option['parent_id']][$option['config_id']]['option'] = $this -> buildText(array('name' => $option['name'],
					                                                                                             'value' => $option['value']));
					    break;
					case 'password':
					    $result[$option['parent_id']][$option['config_id']]['option'] = $this -> buildPassword(array('name' => $option['name'],
					                                                                                                 'value' => $option['value']));
					    break;
					case 'textarea':
					    $result[$option['parent_id']][$option['config_id']]['option'] = $this -> buildTextArea(array('name' => $option['name'],
					                                                                                                 'value' => $option['value']));
					    break;
					case 'radio':
					    $result[$option['parent_id']][$option['config_id']]['option'] = $this -> buildRadio(array('name' => $option['name'],
					                                                                                              'checked' => $option['value'],
					                                                                                              'options' => unserialize($option['type_options'])));
					    break;
					case 'checkbox':
					    $result[$option['parent_id']][$option['config_id']]['option'] = $this -> buildCheckbox(array('name' => $option['name'],
					                                                                                                 'checked' => $option['value'],
					                                                                                                 'options' => unserialize($option['type_options'])));
					    break;
					case 'select':
					    $result[$option['parent_id']][$option['config_id']]['option'] = $this -> buildSelect(array('name' => $option['name'],
					                                                                                                 'selected' => $option['value'],
					                                                                                                 'options' => unserialize($option['type_options'])));
					    break;
					case 'file':
					    $result[$option['parent_id']][$option['config_id']]['option'] = $this -> buildFile(array('name' => $option['name'],
					                                                                                             'file' => $option['value'],
					                                                                                             'options' => unserialize($option['type_options'])));
					    break;
				}
				
			}
			
			return $result;
		}
	}
	
	/**
     * 创建文本框
     *
     * @param    array    $data
     * @return   string
     */
	public function buildText(array $data)
	{
		$data['size'] = ($data['size']) ? $data['size'] : "30";
		$data['maxlength'] = ($data['maxlength']) ? "maxlength=\"" . $data['maxlength'] . "\"" : "";
		$result = "<input type=\"text\" name=\"" . $data['name'] . "\" size=\"" . $data['size'] . "\" value=\"" . $data['value'] . "\" " . $data['maxlength'] . " />";
		return $result;
	}
	
	/**
     * 创建密码框
     *
     * @param    array    $data
     * @return   string
     */
	public function buildPassword(array $data)
	{
		$data['size'] = ($data['size']) ? $data['size'] : "30";
		$data['maxlength'] = ($data['maxlength']) ? "maxlength=\"" . $data['maxlength'] . "\"" : "";
		$result = "<input type=\"password\" name=\"" . $data['name'] . "\" size=\"" . $data['size'] . "\" value=\"" . $data['value'] . "\" " . $data['maxlength'] . " />";
		return $result;
	}
	
	/**
     * 创建文本域
     *
     * @param    array    $data
     * @return   string
     */
	public function buildTextArea(array $data)
	{
		$data['width'] = ($data['width']) ? $data['width'] : "400px";
		$data['height'] = ($data['height']) ? $data['height'] : "50px";
		$result = "<textarea name=\"" . $data['name'] . "\" style=\"width: " . $data['width'] . ";height: " . $data['height'] . "\">" . $data['value'] . "</textarea>";
		return $result;
	}
	
	/**
     * 创建单选框
     *
     * @param    array    $data
     * @return   string
     */
	public function buildRadio(array $data)
	{
		$data['separator'] = ($data['separator']) ? $data['separator'] : "<span style='padding-left:5px'></span>";
		
		foreach ($data['options'] as $key => $value)
		{
			$checked = ($data['checked'] == $key) ? "checked" : "";
		    $radio[] = "<input type=\"radio\" name=\"" . $data['name'] . "\" value=\"" . $key . "\" " . $checked . " /> " . $value;	
		}
		
		$result = implode($data['separator'], $radio);
		return $result;
	}
	
	/**
     * 创建复选框
     *
     * @param    array    $data
     * @return   string
     */
	public function buildCheckbox(array $data)
	{
		$data['separator'] = ($data['separator']) ? $data['separator'] : "<span style='padding-left:5px'></span>";
		
		foreach ($data['options'] as $key => $value)
		{
			$checked = ($data['checked'] == $key) ? "checked" : "";
		    $radio[] = "<input type=\"checkbox\" name=\"" . $data['name'] . "[]\" value=\"" . $key . "\" " . $checked . " /> " . $value;	
		}
		
		$result = implode($data['separator'], $radio);
		return $result;
	}
	
	/**
     * 创建下拉列表
     *
     * @param    array    $data
     * @return   string
     */
	public function buildSelect(array $data)
	{
		foreach ($data['options'] as $key => $value)
		{
			$selected = ($data['selected'] == $key) ? "selected" : "";
		    $option[] = "<option value=\"" . $key . "\" " . $selected . ">" . $value . "</option>";	
		}
		
		$result = "<select name=\"" . $data['name'] . "\">" . implode('', $option) . "</select>";
		return $result;
	}
	
	/**
     * 创建文件上传组件
     *
     * @param    array    $data
     * @return   string
     */
	public function buildFile(array $data)
	{
		$data['size'] = ($data['size']) ? $data['size'] : "20";
		$data['file'] = ($data['file']) ? "<a href=\"" . Zend_Controller_Front::getInstance() -> getBaseUrl() . "/" . $data['file'] . "\" target=\"_blank\"><img src=\"/images/admin/picflag.gif\" border=\"0\" title=\"点击查看图片\"></a> <input type=\"checkbox\" name=\"delete_" . $data['name'] . "\" value=\"1\" alt=\"删除\" /> 删除图片" :"";
		$result = "<input type=\"file\" name=\"" . $data['name'] . "\" size=\"" . $data['size'] . "\" />  " . $data['file'];
		return $result;
	}
}