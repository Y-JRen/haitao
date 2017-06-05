<?php
abstract class  SmartyWidget
{

    /**
     * @var null|\Smarty
     */
    protected $smarty;
    /**
     * @var string
     */
    protected $oldSmartyTplPath ;

    /**
     * @static
     * @param array $params
     * @return mixed
     */
    public static function  factory($params = array())
    {
        /*
        * 使用了php5.3的后期静态绑定 版本限制！
        $className = get_called_class();
        return new $className($params);
        */
        $className = $params['class'];
        $smarty = isset($params['smarty']) ? $params['smarty'] : null;
        unset($params['class'], $params['smarty']);
        //实例化类
        $widget = new $className($smarty);
        $properties = $params;
        foreach ($properties as $name => $value) {
            $widget->$name = $value;
        }
        $widget->init();
        return $widget;
    }

//------------------------------------------------------------------------------------------------


    /**
     * @var array view paths for different types of widgets
     * ----------------
     * 静态变量做缓存
     * ----------------
     */
    private static $_viewPaths;

    /**
     * @param null $smarty
     */
    public function __construct($smarty = null)
    {
        if ($smarty == null) {
            require(SYSROOT . '/lib/Smarty/Smarty.class.php');
            $this->smarty = new Smarty();
            $this->smarty->caching = true;
            //其他相关配置 跟主视图tpl中smarty的配置可以也可以不一致
            //比如模板编译目录 插件目录 smarty的配置路径等
        } else {
            //其实必要时可以选择clone当前smarty对象进行这样被类中的所有配置修改不会影响原始smarty实例
            // 如果你在widget中进行了修改 那么接下来的使用会受前面设置的影响（比如新的模板路径 编译，插件配置路径等）
            $this->smarty = $smarty;
        }
    }


    /**
     *初始化Widget类 这时widget其声明的公共变量已经被赋值了
     *
     */
    public function init()
    {
    }

    /**
     * 执行 widget.
     *-------------------------------------------------------------------------
     *   捕获某个widget的输出  种用法使得widget可以嵌套使用
     *      ob_start();
     *        ob_implicit_flush(false);
     *            $widget = MyWidget::factory($className,$properties);
     *      $widget->run();
     *        return ob_get_clean();
     *---------------------------------------------------------------------------
     */
    public function run()
    {
    }

    /**
     * Returns the directory containing the view files for this widget.
     * The default implementation returns the 'views' subdirectory of the directory containing the widget class file.
     * @return string the directory containing the view files for this widget.
     */
    public function getViewPath()
    {
        $className = get_class($this);
        if (isset(self::$_viewPaths[$className])) {
            return self::$_viewPaths[$className];
        } else
        {
          $class = new ReflectionClass($className);
          return self::$_viewPaths[$className] = dirname($class->getFileName()).DIRECTORY_SEPARATOR . 'html';
        }
    }

    /**
     *返回视图文件路径
     * 由于修改自yii 它可以用多种模板 后缀的配置是在viewRender类中进行的
     * 这里没有必要 简化起见 总是需要带后缀 或者你弄个配置文件 可以配置
     * 模板后缀 使用时只是用模板名字
     * --------------------------------
     * $extension = getFromConfig('smartyTplSuffix');
     *---------------------------------
     * @param $viewName
     * @return bool|string
     */
    public function getViewFile($viewName)
    {

        $extension = '';
        $viewFile = $this->getViewPath() . DIRECTORY_SEPARATOR . $viewName;  
        if (is_file($viewFile . $extension)) {
            return $viewFile . $extension;
        } else {
            return false;
        }
    }

    /**
     * Renders a view.
     *
     * The named view refers to a PHP script (resolved via {@link getViewFile})
     * that is included by this method. If $data is an associative array,
     * it will be extracted as PHP variables and made available to the script.
     *
     * @param string $view name of the view to be rendered. See {@link getViewFile} for details
     * about how the view script is resolved.
     * @param array $data data to be extracted into PHP variables and made available to the view script
     * @param boolean $return whether the rendering result should be returned instead of being displayed to end users
     * @return string the rendering result. Null if the rendering result is not required.
     * @throws CException if the view does not exist
     * @see getViewFile
     */
    public function render($view, $data = null, $return = false)
    {
        if (($viewFile = $this->getViewFile($view)) !== false) {        
            return $this->renderFile($viewFile, $data, $return);
        } else {
            throw new Exception(strtr('{widget} cannot find the view "{view}".',
                array('{widget}' => get_class($this), '{view}' => $view)));

        }
    }

   /**
    * @param $sourceFile
    * @param null $data
    * @param bool $return
    * @return string
    * @throws Exception
    */
    public function renderFile($sourceFile, $data = null, $return = false)
    {
        // 当前类的属性可以通过{this.property}访问
        $data['this'] = $this;

        //检查视图文件是否存在
        if (!is_file($sourceFile) || ($file = realpath($sourceFile)) === false) {
            throw new Exception(strtr('View file "{file}" does not exist.', array('{file}' => $sourceFile)));
        }
        
        //静态目录
        $this -> smarty -> assign('imgBaseUrl', Zend_Registry::get('config') -> view -> imgBaseUrl);
        $this -> smarty -> assign('newsBaseUrl', Zend_Registry::get('config') -> view -> newsBaseUrl);    
        $this -> smarty -> assign('_static_',Zend_Registry::get('config') -> view ->_static_);
        $this->smarty->assign('sys_version',Zend_Registry::get('config') -> view ->version);
        
        //assign data
        $this->smarty->assign($data);

        //render or return
        if ($return) {

            //保存原始的模板路径 用完后恢复之
            $this->oldSmartyTplPath = $this->smarty->template_dir;
            //需要复写模板路径
            $this->smarty->template_dir = '';

            $content = $this->smarty->fetch($sourceFile);
            //恢复smarty模板路径
            $this->smarty->template_dir = $this->oldSmartyTplPath;
            return $content;
        } else {
            //保存原始的模板路径 用完后恢复之
            $this->oldSmartyTplPath = $this->smarty->template_dir;
            //需要重置模板路径
            $this->smarty->template_dir = '';
            $this->smarty->display($sourceFile);
            //恢复smarty模板路径
            $this->smarty->template_dir = $this->oldSmartyTplPath;
        }
    }

    /**
     * Renders a view file.
     * This method includes the view file as a PHP script
     * and captures the display result if required.
     * @param string $_viewFile_ view file
     * @param array $_data_ data to be extracted and made available to the view file
     * @param boolean $_return_ whether the rendering result should be returned as a string
     * @return string the rendering result. Null if the rendering result is not required.
     */
    public function renderInternal($_viewFile_, $_data_ = null, $_return_ = false)
    {
        // we use special variable names here to avoid conflict when extracting data
        if (is_array($_data_))
            extract($_data_, EXTR_PREFIX_SAME, 'data');
        else
            $data = $_data_;
        if ($_return_) {
            ob_start();
            ob_implicit_flush(false);
            require($_viewFile_);
            return ob_get_clean();
        }
        else
            require($_viewFile_);
    }
}