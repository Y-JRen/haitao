<?php

class Custom_Model_PageNavJS
{
	/**
     * 显示的分页个数
     * 
     * @var    int
     */
    private $_navigationItemCount = 5;
    
    /**
     * 每页条目数
     * 
     * @var    int
     */
    private $_pageSize = null;
    
    /**
     * 对齐方式
     * 
     * @var    string
     */
    private $_align = "right";
    
    /**
     * 数据总数
     * 
     * @var    int
     */
    private $_itemCount = null;
    
    /**
     * 分页总数
     * 
     * @var    int
     */
    private $_pageCount = null;
    
    /**
     * 当前分页
     * 
     * @var    int
     */
    private $_currentPage = null;
    
    /**
     * Front controller object
     * 
     * @var    Zend_Controller_Front
     */
    private $_front = null;
    
    /**
     * 分页参数名称
     * 
     * @var    string
     */
    private $_PageParaName = "page";
    
    /**
     * 首页标识
     * 
     * @var    string
     */
    private $_firstPageString = "首页";
    
    /**
     * 下一页标识
     * 
     * @var    string
     */
    private $_nextPageString = "下一页";
    
    /**
     * 上一页标识
     * 
     * @var    string
     */
    private $_previousPageString = "上一页";
    
    /**
     * 尾页标识
     * 
     * @var    string
     */
    private $_lastPageString = "尾页";
    
    /**
     * 页数之间标识
     * 
     * @var    string
     */
    private $_splitString = "  ";
    
    /**
     * 对象初始化
     *
     * @param    int    $itemCount    数据总数
     * @param    int    $pageSize     每页条目数
     * @param    int    $div          返回区域
     * @return   void
     */
    public function __construct($itemCount = null, $pageSize = null, $div = null)
    {
        $this -> _itemCount = (int)$itemCount;
        $this -> _pageSize = ((int)$pageSize > 0) ? (int)$pageSize : Zend_Registry::get('config') -> view -> page_size;
        $this -> _div = $div;
        $this -> _front = Zend_Controller_Front::getInstance();

        $this -> _pageCount = ceil($itemCount / $this -> _pageSize);
        $this->_currentPage = $page = $this -> _front -> getRequest() -> getParam($this -> _PageParaName);
        if (empty($page) || ($page < 1) || (!is_numeric($page))) {
            $this -> _currentPage = 1;
        } else {
            if ($page > $this -> _pageCount) {
                $this -> _currentPage = $this -> _pageCount;
            }
        }
    }

    /**
     * 取得当前分页
     *
     * @param    void
     * @return   int
     */
    public function getCurrentPage()
    {
        return $this -> _currentPage;
    }
     
    /**
     * 取得分页标识
     *
     * @param    void
     * @return   string
     */
    public function getNavigation()
    {
        $navigation = '<div  class="pagesize">';
        
        // 当前分页显示个数
        $pageCote = ceil($this -> _currentPage / ($this -> _navigationItemCount - 1)) - 1; 
        // 分页显示总数
        $pageCoteCount = ceil($this -> _pageCount / ($this -> _navigationItemCount - 1));
        // 当前分页首个条目位置
        $pageStart = $pageCote * ($this -> _navigationItemCount -1) + 1;
        $pageStart = $pageStart > 0 ? $pageStart : 1;
        // 当前分页尾部条目位置
        $pageEnd = $pageStart + $this -> _navigationItemCount - 1;
        
        if ($this->_pageCount < $pageEnd) {
            $pageEnd = $this -> _pageCount;
        }
        
        $navigation .= "总共：{$this -> _itemCount}条　{$this -> _pageCount}页\n";
        
        // 首页
        if ($pageCote > 0) {
            $navigation .= "<a href='javascript:fGo()' onclick='splitPage(\"" . $this -> createHref(1) . "\",\"" . $this -> _div . "\")'>" . $this -> _firstPageString . "</a> ";
        }
        
        // 上一页
        if ($this -> _currentPage > 1) {
            $navigation .= "<a href='javascript:fGo()' onclick='splitPage(\"" . $this -> createHref($this -> _currentPage-1) . "\",\"" . $this -> _div . "\")'>" . $this -> _previousPageString . "</a> ";
        }
        
        // 中间页
        while ($pageStart <= $pageEnd)
        {
            if ($pageStart == $this -> _currentPage) {
                $navigation .= "<strong>" . $pageStart . "</strong>" . $this -> _splitString;
            } else {
                $navigation .= "<a href='javascript:fGo()' onclick='splitPage(\"" . $this -> createHref($pageStart) . "\",\"" . $this -> _div . "\")'>" . $pageStart . "</a>" . $this -> _splitString;
            }
            $pageStart++;
        }
        
        // 下一页
        if ($this -> _currentPage < $this -> _pageCount) {
            $navigation .= "<a href='javascript:fGo()' onclick='splitPage(\"" . $this -> createHref($this -> _currentPage + 1) . "\",\"" . $this -> _div . "\")'>" . $this -> _nextPageString . "</a> ";
        }
        
        // 尾页
        if ($pageCote < $pageCoteCount-1) {
            $navigation .= "<a href='javascript:fGo()' onclick='splitPage(\"" . $this -> createHref($this -> _pageCount) . "\",\"" . $this -> _div . "\")'>" . $this -> _lastPageString. "</a> ";
        }
        
        // 输入框
        $navigation .= "跳转到<input type='text' size='3' onchange='splitPage(\"" . $this -> createHref() . "\" + this.value,\"" . $this -> _div . "\")'>";
        
        // 下拉列表
        $navigation .= "  <select onchange='splitPage(\"" . $this -> createHref() . "\" + this.options[this.selectedIndex].value,\"" . $this -> _div . "\")'>";
        $navigation .= "<option value='1'>1</option>";
        $min = min($this -> _pageCount - 1, $this -> _currentPage + 3);
        
        for ($i = $this -> _currentPage - 3; $i <= $min; $i++)
        {
            if ($i < 2) {
                continue;
            }
            
            $navigation .= "<option value='$i'";
            $navigation .= $this -> _currentPage == $i ? " selected='true'" : '';
            $navigation .= ">$i</option>";
        }
        
        if ($this -> _pageCount > 1) {
            $navigation .= "<option value='" . $this -> _pageCount . "'";
            $navigation .= $this -> _currentPage == $this -> _pageCount ? " selected='true'" : '';
            $navigation .= ">" . $this -> _pageCount . "</option>";
        }
        
        $navigation .= '</select>';
        $navigation .= "</div>";
        return $navigation;
    }

    /**
     * 取得分页标识
     *
     * @param    void
     * @return   string
     */
    public function getPageNavigation($onClick ='')
    {
    	
    	if ($this ->_pageCount<2) {
    		return '';
    	}
    	
        $navigation = '<div class="pagesize">';
        
        // 当前分页显示个数
        $pageCote = ceil($this -> _currentPage / ($this -> _navigationItemCount - 1)) - 1; 
        // 分页显示总数
        $pageCoteCount = ceil($this -> _pageCount / ($this -> _navigationItemCount - 1));
        // 当前分页首个条目位置
        $pageStart = $pageCote * ($this -> _navigationItemCount -1) + 1;
        $pageStart = $pageStart > 0 ? $pageStart : 1;
        // 当前分页尾部条目位置
        $pageEnd = $pageStart + $this -> _navigationItemCount - 1;
        
        if ($this->_pageCount < $pageEnd) {
            $pageEnd = $this -> _pageCount;
        }
       // $navigation .= "总共：{$this -> _itemCount}条　{$this -> _pageCount}页\n";
        
        // 首页
        if ($pageCote > 0) {
            $JS = str_replace('%%page%%', 1, $onClick);
            $navigation .= "<a href='javascript:fGo()' onclick=\"{$JS}\">" . $this -> _firstPageString . "</a> ";
        }
        
        // 上一页
        if ($this -> _currentPage > 1) {
            $JS = str_replace('%%page%%', $this -> _currentPage-1, $onClick);
            $navigation .= "<a href='javascript:fGo()' onclick=\"{$JS}\">" . $this -> _previousPageString . "</a> ";
        }
        // 中间页
        while ($pageStart <= $pageEnd)
        {
            if ($pageStart == $this -> _currentPage) {
                $navigation .= "<strong>" . $pageStart . "</strong>" . $this -> _splitString;
            } else {
                $JS = str_replace('%%page%%', $pageStart, $onClick);
                $navigation .= "<a href='javascript:fGo()' onclick=\"{$JS}\">" . $pageStart . "</a>" . $this -> _splitString;
            }
            $pageStart++;
        }
        // 下一页
        if ($this -> _currentPage < $this -> _pageCount) {
            $JS = str_replace('%%page%%', $this -> _currentPage + 1, $onClick);
            $navigation .= "<a href=\"javascript:fGo()\" onclick=\"{$JS}\">" . $this -> _nextPageString . "</a> ";
        }
        
        // 尾页
        if ($pageCote < $pageCoteCount-1) {
            $JS = str_replace('%%page%%', $this -> _pageCount, $onClick);
            $navigation .= "<a href='javascript:fGo()' onclick=\"{$JS }\">" . $this -> _lastPageString. "</a> ";
        }
        
        // 下拉列表
        $JS = str_replace('%%page%%', 'this.options[this.selectedIndex].value', $onClick);
        $navigation .= "<select onchange='{$JS}'>";
        $navigation .= "<option value='1'>1</option>";
        $min = min($this -> _pageCount - 1, $this -> _currentPage + 3);
        
        for ($i = $this -> _currentPage - 3; $i <= $min; $i++)
        {
            if ($i < 2) {
                continue;
            }
            
            $navigation .= "<option value='$i'";
            $navigation .= $this -> _currentPage == $i ? " selected='true'" : '';
            $navigation .= ">$i</option>";
        }
        
        if ($this -> _pageCount > 1) {
            $navigation .= "<option value='" . $this -> _pageCount . "'";
            $navigation .= $this -> _currentPage == $this -> _pageCount ? " selected='true'" : '';
            $navigation .= ">" . $this -> _pageCount . "</option>";
        }
        
        $navigation .= '</select>';
        $navigation .= "</div>";
        return $navigation;
    }

    /**
     * 取得分页个数
     *
     * @param    void
     * @return   int
     */
    public function getNavigationItemCount()
    {
        return $this -> _navigationItemCount;
    }
    
    /**
     * 设置分页个数
     *
     * @param    int
     * @return   void
     */
    public function setNavigationItemCoun($navigationCount)
    {
        if (is_numeric($navigationCount)) {
            $this -> _navigationItemCount = $navigationCount;
        }
    }
    
    /**
     * 设置首页标识
     *
     * @param    string
     * @return   void
     */
    public function setFirstPageString($firstPageString)
    {
        $this -> _firstPageString = $firstPageString;
    }
    
    /**
     * 设置上一页标识
     *
     * @param    string
     * @return   void
     */
    public function setPreviousPageString($previousPageString)
    {
        $this -> _previousPageString = $previousPageString;
    }

    /**
     * 设置下一页标识
     *
     * @param    string
     * @return   void
     */
    public function setNextPageString($nextPageString)
    {
        $this -> _nextPageString = $nextPageString;
    }

    /**
     * 设置尾页标识
     *
     * @param    string
     * @return   void
     */
    public function setLastPageString($lastPageString)
    {
        $this -> _lastPageString = $lastPageString;
    }

    /**
     * 设置对齐方式
     *
     * @param    string
     * @return   void
     */
    public function setAlign($align)
    {
        $align = strtolower($align);
        if($align == "center")
        {
            $this -> _align = "center";
        }elseif($align == "right")
        {
            $this -> _align = "right";
        }else
        {
            $this -> _align = "left";
        }
    }
    
    /**
     * 设置分页参数名称
     *
     * @param    string
     * @return   void
     */
    public function setPageParamName($pageParamName)
    {
        $this -> _PageParaName = $pageParamName;
    }

    /**
     * 取得分页参数名称
     *
     * @param    void
     * @return   string
     */
    public function getPageParamName()
    {
        return $this -> _PageParaName;
    }
     
    /**
     * 设置连接
     *
     * @param    string
     * @return   string
     */
    private function createHref($targetPage = null)
    {
        $params = $this -> _front -> getRequest() -> getParams();
        $module = $params["module"];
        $controller = $params["controller"];
        $action = $params["action"];

        $module = $module == 'shop' ? '' : '/'.$module;
        $targetUrl = $module . "/" . $controller . "/" . $action;
        foreach ($params as $key => $value)
        {
            if ($key != "controller" && $key != "module" && $key != "action" && $key != $this -> _PageParaName && $key!="" && $value!="") {
                $targetUrl .= "/" . urlencode($key) . "/" . urlencode($value);
            }
        }
        
        if (isset($targetPage)) {
            $targetUrl .= "/" . $this -> _PageParaName . "/" . $targetPage;
        } else {
            $targetUrl .= "/" . $this->_PageParaName . "/";
        }
        
        return $targetUrl;
    }
}