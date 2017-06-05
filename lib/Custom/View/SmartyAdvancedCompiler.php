<?php


/**
 * @see Smarty_Compiler
 */
require_once 'Smarty/Smarty_Compiler.class.php';



class Custom_View_SmartyAdvancedCompiler extends Smarty_Compiler
{
	/**
     * Zend_View
     * @var    object
     */
    private $_zendView;

    /**
     * 对象初始化
     *
     * @param    void
     * @param    void
     */
	public function __construct()
	{
		parent::__construct();
		$this -> _zendView = new Zend_View();
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
						
						if (strpos('.', $key) != -1) {
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