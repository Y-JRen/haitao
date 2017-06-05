<?php

/**
 * @see Smarty
 */
require_once 'Smarty/Smarty.class.php';

/**
 * @see Smarty_Compiler
 */
require_once 'Smarty/Smarty_Compiler.class.php';

/**
 * 视图对象
 */
class Custom_View_Smarty extends Zend_View_Abstract
{
	/**
     * smarty 扩展对象
     * @var    Custom_View_SmartyAdvanced
     */
	protected $_smarty = null;

    /**
     * 对象初始化
     *
     * @param    array    $config
     */
	public function __construct($config = array())
	{
		$this -> _smarty = new Custom_View_SmartyAdvanced();
		   
        foreach ($config as $key => $value) {
            $this -> _smarty -> $key = $value;
        }

		parent::__construct();
		$this -> addHelperPath('../lib/Custom/View/Helper', 'Custom_View_Helper');

		if ($config['template_dir']) {
		    $this -> addHelperPath(dirname($config['template_dir']) . '/helpers', ucfirst(strtolower($this -> _modulesPath)) . '_View_Helper');
		}
	}

    /**
     * 取得 smarty 扩展对象
     *
     * @param    void
     * @return   object
     */
	public function getEngine()
	{
		return $this -> _smarty;
	}

    /**
     * 设置视图变量
     *
     * @param    string    $key
     * @param    mixed     $val
     * @return void
     */
	public function __set($key, $val)
	{
		$this -> _smarty -> assign($key, $val);
	}

    /**
     * 取得视图变量
     *
     * @param  string $key
     * @return string
     */
	public function __get($key)
	{
		$var = $this -> _smarty -> get_template_vars($key);
		if($var === null)
			return parent::__get($key);

		return $var;
	}

    /**
     * 检查变量是否存在
     *
     * @param  string $key
     * @return boolean
     */
	public function __isset($key)
	{
		$var = $this -> _smarty -> get_template_vars($key);
		if($var)
			return true;

		return false;
	}

    /**
     * 清除变量
     *
     * @param string $key
     * @return void
     */
	public function __unset($key)
	{
		$this -> _smarty -> clear_assign($key);
	}

    /**
     * 给视图变量赋值
     *
     * @see    __set()
     * @param  string|array    $spec
     * @param  mixed           $value
     */
	public function assign($spec,$value = null)
	{
		if($value === null)
			$this -> _smarty -> assign($spec);
		else
			$this -> _smarty -> assign($spec, $value);
	}

    /**
     * 取得所有变量
     *
     * @param  void
     * @return array
     */
	public function getVars()
	{
		return $this -> _smarty -> get_template_vars();
	}

    /**
     * 清理所有变量
     *
     * @param  void
     * @return void
     */
	public function clearVars()
	{
		$this -> _smarty -> clear_all_assign();
	}

    /**
     * 输出内容
     *
     * @return mixed
     */
	protected function _run()
	{
		$this -> strictVars(true);
		$this -> _smarty -> assign_by_ref('this', $this);
		$this -> _smarty -> assign('imgBaseUrl', Zend_Registry::get('config') -> view -> imgBaseUrl);
		$this -> _smarty -> assign('newsBaseUrl', Zend_Registry::get('config') -> view -> newsBaseUrl);	
		//静态目录
		$this -> _smarty -> assign('_static_',Zend_Registry::get('config') -> view ->_static_);
		$this->_smarty->assign('sys_version',SYS_VERSION);
		
		$templateDirs = $this->getScriptPaths();
		$file = substr(func_get_arg(0), strlen($templateDirs[0]));
		$this -> _smarty -> template_dir = $templateDirs[0];
		$this -> _smarty -> compile_id = $templateDirs[0];
		echo $this -> _smarty -> fetch($file);
	}
}


class Custom_View_SmartyAdvanced extends Smarty
{
	/**
     * 占位符
     * @var    array
     */
	private $_placeHolders = array();

	/**
     * Zend_View
     * @var    object
     */
	private $_zendView = null;

    /**
     * 对象初始化
     *
     * @param    void
     * @param    void
     */
	public function __construct()
	{
		parent::__construct();
		$this -> compiler_class = 'Custom_View_SmartyAdvancedCompiler';
	}

    /**
     * get Zend_View object.
     *
     * @param    Zend_View_Interface    $view
     * @return   void
     */
	public function setZendView(Zend_View_Interface $view)
	{
		$this -> _zendView = $view;
	}

    /**
     * call Zend_View_Helper.
     *
     * @param    string    $name
     * @param    array     $args
     * @return   mixed
     */
	public function callViewHelper($name,$args)
	{
		$helper = $this -> _zendView -> getHelper($name);
		$result = call_user_func_array(array($helper, $name), $args);
		if (is_array($result) && $result['assign']) {
    	    foreach ($result['assign'] as $k => $v) {
    	        $this -> assign($k, $v);
    	    }
    	}else{
    	    return $result;
    	}
	}

    /**
     * 取得占位符
     *
     * @param    string    $name
     * @return   string
     */
	public function getPlaceHolder($name)
	{
		if(isset($this -> _placeHolders[$name]))
			return $this -> _placeHolders[$name];
		else
			return '';
	}

    /**
     * 创建占位符
     *
     * @param    string    $name
     * @param    string    $content
     * @return   void
     */
	public function createPlaceHolder($name,$content)
	{
		$this -> _placeHolders[$name] = $content;
	}

}


class Custom_View_SmartyAdvancedCompiler extends Smarty_Compiler
{
	/**
     * Zend_View
     * @var    object
     */
    //private $_zendView;

    /**
     * 对象初始化
     *
     * @param    void
     * @param    void
     */
	public function __construct()
	{
		parent::__construct();
		//$this -> _zendView = new Zend_View();
	}

	/**
     * 编译自定义标签
     *
     * @param    string    $tag_command
     * @param    string    $tag_args
     * @param    string    $output
     * @return   boolean
     */
	function _compile_compiler_tag($tagCommand, $tagArgs, &$output)
	{
		$found = parent::_compile_compiler_tag($tagCommand, $tagArgs, $output);
		if (!$found) {
			try {
				//$helper = $this->_zendView->getHelper($tagCommand);
				$helperArgs = array();
				if ($tagArgs !== null) {
					$params = explode(' ', $tagArgs);
					foreach($params as $p)
					{
						list($key, $value) = explode('=', $p, 2);
						$section = '';

						if (strstr($key,'.') && strpos('.', $key) != -1) {
							list($key, $section) = explode('.', $key);
							$arrayKey = 'array';
						}

						$value = $this->_parse_var_props($value);
						if ($section == '') {
							if (array_key_exists($key, $helperArgs)) {
								if (is_array($helperArgs[$key])) {
									$helperArgs[$key][] = $value;
								} else {
									$helperArgs[$key] = array($helperArgs[$key], $value);
								}
							} else {
								$helperArgs[$key] = $value;
							}
						} else {
							if (!is_array($helperArgs[$arrayKey])) {
								$helperArgs[$arrayKey] = array();
							}
							$helperArgs[$arrayKey][$section] = $value;
						}
					}
				}
				$output = "<?php echo \$this -> callViewHelper('$tagCommand', array(".$this->_createParameterCode($helperArgs)."));?>";
				$found = true;
			} catch(Zend_View_Exception $e) {
				$found = false;
			}
		}

		return $found;
	}

    /**
     * 创建 Zend_View 标签
     *
     * @param    array    $params
     * @return   string
     */
	private function _createParameterCode($params)
	{
		$code = '';

		$i = 1;
		$pCount = count($params);
		foreach($params as $key => $value)
		{
			if (is_array($value)) {
				$code .= 'array(';
				foreach($value as $subKey => $subValue)
				{
					if ($key == 'array') {
					    $code .= '\'' . $subKey . '\'=>' . $subValue . ',';
					}
					else {
						$code .= $subValue . ',';
					}
				}
				$code .= ')';
			} else {
				$code .= $value;
			}

			if ($i != $pCount) {
				$code .= ',';
			}

			$i++;
		}
		return $code;
	}
}