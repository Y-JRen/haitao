<?php

class Admin_Models_API_EmailTemplate
{
	/**
     * 邮件模板管理 DB
     * 
     * @var Admin_Models_DB_EmailTemplate
     */
	private $_db = null;
	
	/**
     * 对象初始化
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = new Admin_Models_DB_EmailTemplate();
	}
	
	/**
     * 获取所有邮件模板列表
     *
     * @param    array  $where
     * @param    int    $page
     * @param    int    $pageSize
     * @return   array
     */
	public function getAllTemplate($page = null, $pageSize = null)
	{
		$template = $this -> _db -> getTemplate(null, $page, $pageSize);
		is_array($template) && $template = Custom_Model_DeepTreat::filterArray($template, 'stripslashes');
		return $template;
	}
	
	/**
     * 获取指定ID的邮件模板
     *
     * @param    int    $id
     * @return   array
     */
	public function getTemplateById($id)
	{
		$template = $this -> _db -> getTemplate(array('template_id' => $id));
		is_array($template) && $template = Custom_Model_DeepTreat::filterArray($template, 'stripslashes');
		
		if ($template) {
			return array_shift($template);
		}
	}
	
	/**
     * 获取指定名称的邮件模板
     *
     * @param    string    $name
     * @return   array
     */
	public function getTemplateByName($name)
	{
		$template = $this -> _db -> getTemplate(array('name' => $name));
		is_array($template) && $template = Custom_Model_DeepTreat::filterArray($template, 'stripslashes');
		
		if ($template) {
			return array_shift($template);
		}
	}
	
	/**
     * 取得邮件模板总数
     *
     * @return   int
     */
	public function getAllTemplateCount()
	{
		return $this -> _db -> getTemplateCount();
	}
	
	/**
     * 添加/编辑邮件模板
     *
     * @param    array    $data
     * @param    int      $id
     * @return   int      lastInsertId
     */
	public function editTemplate($data, $id = null)
	{
		if ($data['name'] == '') {
			return 'noTemplateName';
		}
		
		if ($data['title'] == '') {
			return 'noTemplateTitle';
		}
		
		$data['update_time'] = time();
		
		$template = $this -> getTemplateByName($data['name']);
		
		if ($id === null) {
			
			if ($template) {
			    return 'templateExists';
		    }
		    
		    $result = $this -> _db -> addTemplate($data);
		} else {
			
			if ($template && $template['template_id'] != $id) {
			    return 'templateExists';
		    }
		    
			$exists = $this -> getTemplateById($id);
			
			if (!$exists) {
			    return 'templateNoExists';
		    }
		    
			$result = $this -> _db -> updateTemplate($data, (int)$id);
		}
		
		if (is_numeric($result) && $result >= 0) {
		    return ($id === null) ? 'addTemplateSucess' : 'editTemplateSucess';
		} else {
			return 'error';
		}
	}
	
	/**
     * 删除指定ID的邮件模板
     *
     * @param    int    $id
     * @return   void
     */
	public function deleteTemplateById($id)
	{
		if ((int)$id > 0) {
		    $result = $this -> _db -> deleteTemplate((int)$id);
		    if (is_numeric($result) && $result > 0) {
		        return 'deleteTemplateSucess';
		    } else {
			    return 'error';
		    }
		}
	}
	/**
     * 获取指定名称的邮件模板并解析
     *
     * @param    string   $name
     * @param    array    $values
     * @return   array
     */
	public function getEmailTemplateByName($name,$values = null)
	{
		$template = $this -> _db -> getTemplateInfo(array('name' => $name));
        $values && $template['value'] = $this -> parseTemplate(stripslashes($template['value']), $values);
		return $template;
	}
	/**
     * 解析邮件模板
     *
     * @param    string    $content
     * $param    array     $values
     * @return   array
     */
	private function parseTemplate($content, $values)
	{
		is_array($values) && extract($values);
		$content = preg_replace('/\{\{\s*\$([^}]*)\s*\}\}/', '${' . "\${1}" . '}', $content);
		$content = preg_replace('/"/', '\"', $content);
		eval("\$content = \"$content\";");
		return $content;
	}
}