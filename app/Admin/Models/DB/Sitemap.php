<?php

class Admin_Models_DB_Sitemap
{
	/**
     * Zend_Db
     * 
     * @var    Zend_Db
     */
	private $_db = null;
	
	/**
     * 商品分类表
     * 
     * @var    string
     */
	private $_goodsCatTable = 'shop_goods_cat';
	
	/**
     * 商品表
     * 
     * @var    string
     */
	private $_goods = 'shop_goods';

	/**
     * 文章表
     * 
     * @var    string
     */
	private $_article = 'shop_article';

    /**
     * 搭配表
     * 
     * @var    string
     */
	private $_match = 'shop_mix_match';
	
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
     * 取得商品分类信息
     *
     * @param    string   $where
     * @param    int      $page
     * @param    int      $pageSize
     * @return   array
     */
	public function getGoodsCatsList()
	{
		$sql = 'SELECT cat_id FROM `' . $this -> _goodsCatTable . '` WHERE cat_status=0  and display = 1 ORDER BY cat_id ASC';
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 取得商品信息
     *
     * @param    string   $where
     * @return   array
     */
	public function getGoodsList()
	{
		$sql = 'SELECT goods_id,cat_id FROM `' . $this -> _goods . '` WHERE onsale=0 ORDER BY goods_id DESC';
		return $this -> _db -> fetchAll($sql);
	}
	
	/**
     * 取得文章列表
     *
     * @param    string   $where
     * @return   array
     */
	public function getArticleList()
	{		
		$sql = 'SELECT article_id FROM `' . $this -> _article . '` WHERE is_view=1 ORDER BY article_id DESC';
		return $this -> _db -> fetchAll($sql);
	}
}