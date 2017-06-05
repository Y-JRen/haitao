<?php
/**
 * @see sphnix search
 */
require_once 'Custom/Model/sphinxapi.php';

class Custom_Model_Sphinx
{
	public function getProductResultFromSphinx($keywords,$limitStart,$limitEnd,$indexQuery = 'goods'){
		$cl = new SphinxClient ();
		$cl->SetServer ( search_server, 9312);
		//以下设置用于返回数组形式的结果
		$cl->SetArrayResult ( true );
		/*
		//ID的过滤
		$cl->SetIDRange(3,4);
		//sql_attr_uint等类型的属性字段，需要使用setFilter过滤，类似SQL的WHERE group_id=2
		$cl->setFilter('group_id',array(2));
		//sql_attr_uint等类型的属性字段，也可以设置过滤范围，类似SQL的WHERE group_id2>=6 AND group_id2<=8
		$cl->SetFilterRange('group_id2',6,8);
		*/
		$cl->SetMatchMode (SPH_MATCH_EXTENDED2);//设置模式
		$cl->SetRankingMode ( SPH_RANK_PROXIMITY_BM25 );//设置评分模式
		$cl->SetFieldWeights (array('goods_name'=>10,'goods_sn'=>6,'meta_title'=>2,'meta_keywords'=>2,'meta_description'=>2,'description'=>1,'brief'=>1,'introduction'=>1));//设置字段的权重，如果area命中，那么权重算2
		$cl->SetSortMode ('SPH_SORT_EXPR','@weight');//按照权重排序
		//取从头开始的前20条数据，0,20类似SQl语句的LIMIT 0,20
		$cl->SetLimits($limitStart,$limitEnd);
		//如果需要搜索指定全文字段的内容，可以使用扩展匹配模式：
		//$cl->SetMatchMode(SPH_MATCH_EXTENDED);
		//在做索引时，没有进行 sql_attr_类型 设置的字段，可以作为“搜索字符串”，进行全文搜索
		$res = $cl->Query ($keywords, $indexQuery);    //"*"表示在所有索引里面同时搜索，"索引名称（例如test或者test,test2）"则表示搜索指定的
		return $res;
	}
	
	
}