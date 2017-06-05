<?php

class Admin_MemberRankController extends Zend_Controller_Action
{
	/**
     * 会员等级管理 API
     * @var Admin_Models_API_MemberRank
     */
    private $_rank = null;
    
    /**
     * 是否特殊会员等级
     * 
     * @var array
     */
    private $_isSpecial = array('否', '是');
    
    /**
     * 是否显示价格
     * 
     * @var array
     */
    private $_showPrice = array('否', '是');
    
    /**
     * 未填写会员等级名称
     */
	const NO_RANK_NAME = '请填写会员等级名称!';
	
	/**
     * 添加会员等级成功
     */
	const ADD_RANK_SUCESS = '添加会员等级成功!';
	
	/**
     * 编辑会员等级成功
     */
	const EDIT_RANK_SUCESS = '编辑会员等级成功!';
	
	/**
     * 会员等级已存在
     */
	const RANK_EXISTS = '该会员等级已存在!';
	
	/**
     * 会员等级不存在
     */
	const RANK_NO_EXISTS = '该会员等级不存在!';
	
	/**
     * 对象初始化
     *
     * @return   void
     */
	public function init() 
	{
		$this -> _rank = new Admin_Models_API_MemberRank();
		$this -> _cat = new Admin_Models_API_Category();
	}
    
    /**
     * 会员等级列表
     *
     * @return void
     */
    public function indexAction()
    {
        $rankMessages = $this -> _rank -> getAllRank();
        
        if (is_array($rankMessages)) {
        	
        	$isSpecial = $this -> _isSpecial;
        	
        	foreach ($rankMessages as $num => $rankMessage)
            {
            	$discount = unserialize($rankMessages[$num]['discount']);
            	$rankMessages[$num]['discount'] = $discount['discount'];
            	unset($discount);
            	$rankMessages[$num]['is_special'] = ($rankMessages[$num]['is_special'] == '1') ? $isSpecial[1] : $isSpecial[0];
        	    $rankMessages[$num]['show_price'] = $this -> _rank -> ajaxShowPrice($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('show-price'), $rankMessages[$num]['rank_id'], $rankMessages[$num]['show_price']);
            }
        }
        
        $this -> view -> rankList = $rankMessages;
        
    }
    
    /**
     * 添加会员等级
     *
     * @return void
     */
    public function addAction()
    {
        if ($this -> _request -> isPost()) {
        	$this -> _helper -> viewRenderer -> setNoRender();
        	$result = $this -> _rank -> editRank($this -> _request -> getPost());
        	switch ($result) {
        		case 'addRankSucess':
        		    Custom_Model_Message::showMessage(self::ADD_RANK_SUCESS, '', 1250, 'Gurl()');
        		    break;
        		case 'noRankName':
        		    Custom_Model_Message::showMessage(self::NO_RANK_NAME);
        		    break;
        		case 'rankExists':
        		    Custom_Model_Message::showMessage(self::RANK_EXISTS);
        		    break;
        		case 'error':
        		    Custom_Model_Message::showMessage('error!');
        	}
        } else {
        	$this -> view -> action = 'add';
        	$this -> view -> title = '添加会员等级';
        	$this -> view -> specialOptions = $this -> _isSpecial;
        	$this -> view -> showPriceOptions = $this -> _showPrice;
        	$rank['is_special'] = 0;
        	$rank['show_price'] = 0;
        	$this -> view -> rank = $rank;
        	$this -> view -> goodsCat = $this -> _cat -> buildText(array('name' => 'catDiscount'));
        	$this -> render('edit');
        }
    }
    
    /**
     * 编辑会员等级
     *
     * @return void
     */
    public function editAction()
    {
        $id = (int)$this -> _request -> getParam('id', null);
        if ($id > 0) {
            if ($this -> _request -> isPost()) {
            	$this -> _helper -> viewRenderer -> setNoRender();
                $result = $this -> _rank -> editRank($this -> _request -> getPost(), $id);
                switch ($result) {
                	case 'editRankSucess':
        		        Custom_Model_Message::showMessage(self::EDIT_RANK_SUCESS, '', 1250, 'Gurl()');
        		        break;
        		    case 'noRankName':
        		        Custom_Model_Message::showMessage(self::NO_RANK_NAME);
        		        break;
        		    case 'rankNoExists':
        		        Custom_Model_Message::showMessage(self::RANK_NO_EXISTS);
        		        break;
        		    case 'rankExists':
        		        Custom_Model_Message::showMessage(self::RANK_EXISTS);
        		        break;
        		    case 'error':
        		        Custom_Model_Message::showMessage('error!');
        	    }
            } else {
                $this -> view -> action = 'edit';
                $this -> view -> title = '修改管理员组';
                $rank = $this -> _rank -> getRankById($id);
                $rank['discount'] = unserialize($rank['discount']);
    	
                if ($rank['discount']['goodsDiscount']) {
                	$this -> view -> discountGoods = $this -> _rank -> getDiscountGoods($rank['discount']['goodsDiscount']);
                }
		        $this -> view -> rank = $rank;
                $this -> view -> specialOptions = $this -> _isSpecial;
        	    $this -> view -> showPriceOptions = $this -> _showPrice;
        	    $this -> view -> goodsCat = $this -> _cat -> buildText(array('name' => 'catDiscount', 'value' => $rank['discount']['catDiscount']));
            }
        }else{
            Custom_Model_Message::showMessage('error!');
        }
    }
    
    /**
     * 删除会员等级
     *
     * @return void
     */
    public function deleteAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $id = (int)$this -> _request -> getParam('id', 0);
        if ($id > 0) {
            $result = $this -> _rank -> deleteRankById($id);
            switch ($result) {
            	case 'deleteSucess':
        		    break;
        		case 'error':
        		    exit('error!');
            }
        } else {
            exit('error!');
        }
    }
    
    /**
     * 更改是否显示价格
     *
     * @return void
     */
    public function showPriceAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$id = (int)$this -> _request -> getParam('id', 0);
    	$showPrice = (int)$this -> _request -> getParam('status', 0);
    	
    	if ($id > 0) {
	        $this -> _rank -> showPrice($id, $showPrice);
        }else{
            Custom_Model_Message::showMessage('error!');
        }
        echo $this -> _rank -> ajaxShowPrice($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('show-price'), $id, $showPrice);
    }
    
    /**
     * 更改特殊商品折扣
     *
     * @return void
     */
    public function selectGoodsAction()
    {
        $page = (int)$this -> _request -> getParam('page', 1);
        $goodsMessage = $this -> _rank -> getGoods($page, 20, $this -> _request -> getParams());
        $pageNav = new Custom_Model_PageNav($goodsMessage['total'], 20, 'ajax_search');
        $this -> view -> goodsMessage = $goodsMessage['content'];
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $this -> view -> param = $this -> _request -> getParams();
        $this -> view -> catSelect = $this -> _cat -> buildProductSelect(array('name' => 'cat_id'));
    }
}

