<?php
class Shop_Models_API_EmailTemplate
{
	/**
     * 邮件模板 DB
     * 
     * @var Shop_Models_DB_EmailTemplate
     */
	protected $_db = null;
	
	/**
     * 对象初始化
     *
     * @param  void
     * @return void
     */
	public function __construct()
	{
		$this -> _db = new Shop_Models_DB_EmailTemplate();
	}
	
	/**
     * 获取指定ID的邮件模板
     *
     * @param    int     $id
     * @param    array   $values
     * @return   array
     */
	public function getTemplateById($id, $values = null)
	{
		$template = $this -> _db -> getTemplate(array('template_id' => $id));
		$values && $template['value'] = $this -> parseTemplate(stripslashes($template['value']), $values);
		return $template;
	}
	
	/**
     * 获取指定名称的邮件模板
     *
     * @param    string   $name
     * @param    array    $values
     * @return   array
     */
	public function getTemplateByName($name, $values = null)
	{
		$template = $this -> _db -> getTemplate(array('name' => $name));
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