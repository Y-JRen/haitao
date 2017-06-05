<?php
class Admin_MsgController extends Zend_Controller_Action 
{
	const REPLY_SUCCESS = '回复成功!';
	const ADD_SUCCESS = '添加成功!';
	const ADD_FAIL = '添加失败!';
    const REPLY_FAIL = '回复失败！';
    const DEL_SUCCESS = '删除成功！';
    const DEL_FAIL = '删除失败！';
    const NO_CONTENT = '请填写回复内容';

    public function init(){
        $this->_api = new Admin_Models_API_Msg();
        $this->_adminCertification = Admin_Models_API_Auth :: getInstance() -> getAuth();
	}

	/**
     * 站点留言
     *
     * @return void
     */
    public function listsiteAction(){
        $page = (int)$this->_request->getParam('page', 1);
        $type = $this->_request->getParam('type', null);
        if ($type!=null){
        	$where="type=$type";
        }
        $total = $this->_api->getCountSite($where);
        $data = $this->_api->getSite($where,'*',null,$page);
        if(is_array($data) && count($data)>0){
            foreach($data as $k=>$v){
                $data[$k]['add_time'] = date('Y-m-d H:i',$v['add_time']);
                $data[$k]['reply_time'] = date('Y-m-d H:i',$v['reply_time']);
                switch($data[$k]['type']){
                    case 0:
                        $data[$k]['type'] = '留言';
                        break;
                    case 1:
                        $data[$k]['type'] = '投诉';
                        break;
                    case 2:
                        $data[$k]['type'] = '询问';
                        break;
                    case 3:
                        $data[$k]['type'] = '售后';
                        break;
                    case 4:
                        $data[$k]['type'] = '求购';
                        break;
                    case 5:
                        $data[$k]['type'] = '留言板';
                        break;
					case 7:
                        $data[$k]['type'] = '专家问答';
                        break;
                }
                
                $userIDArray[] = $v['user_id'];
            }
            
            $orderAPI = new Admin_Models_API_Order();
            $orderData = $orderAPI -> getOrderBatch(" and a.user_id in (".implode(',', $userIDArray).")");
            if ($orderData) {
                foreach ($orderData as $order) {
                    $orderInfo[$order['user_id']]++;
                }
                foreach($data as $k => $v) {
                    $data[$k]['order_count'] = $orderInfo[$v['user_id']];
                }
            }
        }
        $this->view->data = $data;
        $this -> view -> param = $this -> _request -> getParams();
        $pageNav = new Custom_Model_PageNav($total,null,'ajax_search');
        $this->view->pageNav=$pageNav->getNavigation();
    }

	/**
     * 商品留言
     *
     * @return void
     */
    public function listgoodsAction(){
        $total = $this->_api->getCountGoods($this -> _request -> getParams());
        $page = (int)$this->_request->getParam('page', 1);
        $data = $this->_api->getGoods($this -> _request -> getParams(),'*',null,$page);
        if(is_array($data)){
            foreach($data as $k=>$v){
                $data[$k]['add_time'] = date('Y-m-d H:i',$v['add_time']);
                $data[$k]['reply_time'] = date('Y-m-d H:i',$v['reply_time']);
                switch($data[$k]['type']){
                    case 1:
                        $data[$k]['type'] = '评论';
                        break;
                    case 2:
                        $data[$k]['type'] = '咨询';
                        break;
                }
            }
        }
        $this->view->data = $data;
        $this->view->type =(int)$this->_request->getParam('type', 1);
        $this -> view -> param = $this -> _request -> getParams();
        $pageNav = new Custom_Model_PageNav($total);
        $this->view->pageNav=$pageNav->getNavigation();
    }
	/**
     * 商品留言列表
     *
     * @return void
     */
    public function listgoodscommentAction(){
        $total = $this->_api->getCountGoods($this -> _request -> getParams());
        $page = (int)$this->_request->getParam('page', 1);
        $data = $this->_api->getGoods($this -> _request -> getParams(),'*',null,$page);
        if(is_array($data)){
            foreach($data as $k=>$v){
                $data[$k]['add_time'] = date('Y-m-d H:i',$v['add_time']);
                $data[$k]['reply_time'] = date('Y-m-d H:i',$v['reply_time']);
            }
        }
        $this->view->data = $data;
        $this -> view -> param = $this -> _request -> getParams();
        $pageNav = new Custom_Model_PageNav($total,null,'goods_comment');
        $this->view->pageNav=$pageNav->getNavigation();
    }

	/**
     * 删除站点留言
     *
     * @return void
     */
    public function delsiteAction(){
        $this->_api->delsite($this->_request->getParam('id', null));
        exit;
    }
    public function delgoodsAction(){
        $this->_api->delgoods($this->_request->getParam('id', null));
        exit;
    }
	/**
     * 站点留言回复
     *
     * @return void
     */

    public function sitereplyformAction(){
        $id = $this->_request->getParam('id', null);
        $this->view->msg=$this->_api->getSiteMsgByID($id);
        $this->view->id=$id;
    }

	/**
     * 审核商品留言
     *
     * @return void
     */
    public function goodsreplyformAction(){
        $this->view->type =(int)$this->_request->getParam('type', 1);
        $id = $this->_request->getParam('id', null);
        $msg = $this->_api->getGoodsMsgByID($id);
        $msg['add_time'] = date('Y-m-d H:i:s', $msg['add_time']);
        $this->view->msg=$msg;
        $this->view->id=$id;
    }

	/**
     * 添加商品留言
     *
     * @return void
     */
    public function goodscommentaddAction(){
        $goods_id = $this->_request->getParam('goods_id', null);
        if($goods_id){
	        $post = $this->_request->getPost();
	        if($post){
	        	$data = array(
	                'title'=>$post['title'],
	                'content'=>$post['content'],
	                'goods_id'=>$post['goods_id'],
	                'goods_name'=>$post['goods_name'],
	                'user_name'=>$post['user_name'],
	                'cnt1'=>$post['cnt1'],
	                'cnt2'=>$post['cnt2'],
	                'status'=> 1,
	                'add_time'=> strtotime($post['add_time']),
	                'admin_id'=>$this->_adminCertification['admin_id'],
	                'admin'=>$this->_adminCertification['admin_name'],
	                'ip' => $_SERVER['REMOTE_ADDR'],
					'is_hot'=>$post['is_hot'],
	                );
	            if($this->_api->goodsCommentAdd($data)){
	        	    Custom_Model_Message::showMessage(self::ADD_SUCCESS);
	        	}else{
	        		Custom_Model_Message::showMessage(self::ADD_FAIL);
	        	}
	        }
	        $this -> view -> param = $this -> _request -> getParams();
        }else{
        	exit('error');
        }
    }

	/**
     * 站点留言回复
     *
     * @return void
     */
    public function sitereplyAction(){
        $id = intval($this->_request->getParam('id', null));
        $post = $this->_request->getPost();
        if($post){
            $data = array(
                'content'=>$post['content'],
                'reply'=>$post['reply'],
				'is_hot'=>$post['is_hot'],
                'status'=>$post['status'],
                'admin_id'=>$this->_adminCertification['admin_id'],
                'admin'=>$this->_adminCertification['admin_name'],
                'reply_time'=>time());
            $this->_api->siteReply($id, $data);
        }
        Custom_Model_Message::showMessage(self::REPLY_SUCCESS, 'event', 1250, "Gurl()");
    }

	/**
     * 商品留言回复
     *
     * @return void
     */

    public function goodsreplyAction(){
        $id = intval($this->_request->getParam('id', null));
        $post = $this->_request->getPost();
        if($post){
            $data = array(
                'user_name'=>$post['user_name'],
                'add_time' => strtotime($post['add_time']),
                'content'=>$post['content'],
                'reply'=>$post['reply'],
                'status'=>$post['status'],
                'cnt1'=>$post['cnt1'],
                'cnt2'=>$post['cnt2'],
				'is_hot'=>$post['is_hot'],
                'dietitian'=>$post['dietitian']?$post['dietitian']:0,
                'admin_id'=>$this->_adminCertification['admin_id'],
                'admin'=>$this->_adminCertification['admin_name'],
                'reply_time'=>time());
            $this->_api->goodsReply($id, $data);
        }
        Custom_Model_Message::showMessage(self::REPLY_SUCCESS, 'event', 1250, "Gurl()");
    }

	/**
     * 审核商品留言
     *
     * @return void
     */
    public function checkGoodsMsgAction(){
        $val = (int)$this -> _request -> getParam('val', 0);
        $datas = $this->_request->getParams();
        if (is_array($datas['ids']) && count($datas['ids']) > 0 && $val) {
			foreach($datas['ids'] as $id){
			    $data = array(
                'status'=>$val,
                'admin_id'=>$this->_adminCertification['admin_id'],
                'admin'=>$this->_adminCertification['admin_name'],
                'reply_time'=>time());
	    		$this->_api->goodsReply($id, $data);
			}
		}
		Custom_Model_Message::showMessage('操作成功', 'event', 1250, "Gurl('refresh')");
    }
    public function ajaxModifyGoodsAction(){
        $id = intval($this->_request->getParam('id', null));
        $name = $this->_request->getParam('name', null);
        $value = $this->_request->getParam('value', null);
        $this->_api->editGoodsMsg($id,array($name=>$value));
        exit;
    }
	
    /**
     * 投诉管理
     *
     */
    public function listcomplaintAction(){
        $page = (int)$this -> _request -> getParam('page', 1);
        $rs = $this -> _api -> getComplaint($this -> _request -> getParams(), '*', null, $page, 20);
        $this -> view -> datas = $rs['datas'];
        $this -> view -> type = (int)$this->_request->getParam('type', 1);
        $this -> view -> param = $this -> _request -> getParams();
        $pageNav = new Custom_Model_PageNav($rs['tot'], 20);
        $this->view->pageNav=$pageNav->getNavigation();
    }

    /**
     * 投诉详情s
     *
     */
    public function complaintDetailAction(){
        $id = (int)$this -> _request -> getParam('id', 0);
        if($id < 0){ exit('参数错误'); }
        //得到投诉记录
        $rs = $this -> _api -> getComplaint(array('id'=>$id));
        if($rs['tot'] <1){ exit('参数错误'); }
        $this -> view -> detail = array_shift($rs['datas']);
        //是否阅读了
        if(!$this -> view -> detail['is_read']){
            $this -> _api -> updateComplaint(array('is_read'=>1), $id);
        }
    }

    /**
     * ajax 更新 is_solved状态
     *
     */
    public function setSolvedAction(){
        $this -> _helper -> viewRenderer -> setNoRender();
        $id = (int)$this -> _request -> getParam('id', 0);
        $st = (int)$this -> _request -> getParam('st', 0);
        if($st!=0 && $st!=1 && $st!=2){ $st = 0; }
        $rs = $this -> _api -> updateComplaint(array('is_solved'=>$st, 'solved_time'=>time()), $id);
        if($rs['status'] == 'err'){echo $rs['err_msg'];}
        else{echo 'ok';}
        exit;
    }

    /**
     * ajax 增加投诉备注
     *
     */
    public function addComplaintRemarkAction(){
        $this -> _helper -> viewRenderer -> setNoRender();
        $id = (int)$this -> _request -> getParam('id', 0);
        $rmk = $this -> _request -> getParam('rmk');
        $rmk = trim($rmk); if($rmk == ''){ echo '请输入备注内容a'; exit; }
        //取出原来的reply内容
        $rs = $this -> _api -> getComplaint(array('id'=>$id));
        if($rs['tot'] <1){ echo '参数错误'; exit; }
        $detail = array_shift($rs['datas']); $reply_remark = $detail['reply_remark'];
        $reply_remark .= '('.date("Y-m-d H:i:s", time()).') ^ '.$this->_adminCertification['admin_name'].' ^ '.$rmk."<br>";
        //
        $rs = $this -> _api -> updateComplaint(array('reply_remark'=>$reply_remark), $id);
        if($rs['status'] == 'err'){echo $rs['err_msg'];}
        else{echo 'ok';}
        exit;
    }
}