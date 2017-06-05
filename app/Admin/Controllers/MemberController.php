<?php
class Admin_MemberController extends Zend_Controller_Action {
	/**
	 * 会员管理 API
	 *
	 * @var Admin_Models_API_Customer
	 */
	private $_member = null;

	/**
	 * 会员等级
	 *
	 * @var array
	 */
	private $_memberRanks = null;

	/**
	 * 性别
	 *
	 * @var array
	 */
	private $_sex = array(1 => '男', 2 => '女');

	/**
	 * 变更账户类别
	 *
	 * @var array
	 */
	private $_accountType = array('money' => '账户余额', 'point' => '积分', 'experience' => '经验值');

	/**
	 * 未填写用户名
	 */
	const NO_USERNAME = '请填写用户名!';

	/**
	 * 密码不一致
	 */
	const NO_SAME_PASSWORD = '输入的密码不一致!';

	/**
	 * 密码为空
	 */
	const NO_PASSWORD = '密码及重复密码不能为空!';

	/**
	 * 添加会员成功
	 */
	const ADD_MEMBER_SUCESS = '添加会员成功!';

	/**
	 * 编辑会员信息成功
	 */
	const EDIT_MEMBER_SUCESS = '编辑会员成功!';

	/**
	 * 会员已存在
	 */
	const MEMBER_EXISTS = '该会员已存在!';

	/**
	 * 会员不存在
	 */
	const MEMBER_NO_EXISTS = '该会员不存在!';

	/**
	 * 编辑账户余额成功
	 */
	const EDIT_MONEY_SUCESS = '编辑账户余额成功!';

	/**
	 * 编辑经验值成功
	 */
	const EDIT_EXPERIENCE_SUCESS = '编辑经验值成功!';

	/**
	 * 编辑积分成功
	 */
	const EDIT_POINT_SUCESS = '编辑积分成功!';

	/**
	 * 收货地址多于限制个数
	 */
	const TOO_MANY_ADDRESS = '您最多只能有规定个数的收货地址!';

	private $_page_size = '20';

	public $mdl_name = 'member';

	/**
	 * 对象初始化
	 *
	 * @return   void
	 */
	public function init() {
		$this -> _member = new Admin_Models_API_Member();
		$this -> _member_rank = new Admin_Models_API_MemberRank();
		$this -> _auth = Admin_Models_API_Auth::getInstance() -> getAuth();
		$this -> view -> params = $this -> _request -> getParams();

		$ranks = $this -> _member_rank -> getAllRank();
		$member_all_ranks = array();
		$member_ranks = array();
		foreach ($ranks as $key => $value) {
			$member_ranks[$value['rank_id']] = $value;
			$member_all_ranks[$value['rank_id']] = $value['rank_name'];
		}

		$this -> _memberRanks = $member_ranks;
		$this -> _memberAllRanks = $member_all_ranks;


	}
	/**
	 * 会员列表
	 *
	 * @return void
	 */
	public function indexAction() {
		$page = (int)$this -> _request -> getParam('page', 1);
		$memberMessages = $this -> _member -> getMemberBySearch($this -> _request -> getParams(), $page);
		$total = $this -> _member -> getMemberCountBySearch($this -> _request -> getParams());
		foreach ($memberMessages as $num => $memberMessage) {
			$memberMessages[$num]['add_time'] = ($memberMessages[$num]['add_time'] > 0) ? date('Y-m-d', $memberMessages[$num]['add_time']) : '';
			$memberMessages[$num]['last_login'] = ($memberMessages[$num]['last_login'] > 0) ? date('Y-m-d', $memberMessages[$num]['last_login']) : '';
			$memberMessages[$num]['status'] = $this -> _member -> ajaxStatus($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('status'), $memberMessages[$num]['user_id'], $memberMessages[$num]['status']);
		}
		$this -> view -> member_list = $memberMessages;
		$this -> view -> member_ranks = $this -> _memberAllRanks;
		$this -> view -> param = $this -> _request -> getParams();

		$pageNav = new Custom_Model_PageNav($total, null, 'ajax_search');
		$this -> view -> pageNav = $pageNav -> getNavigation();
	}

	/**
	 * 积分变动历史
	 *
	 * @return void
	 */
	public function pointlistAction() {
		$page = (int)$this -> _request -> getParam('page', 1);
		$pointlist = $this -> _member -> getAllPoint($this -> _request -> getParams(), $page, 20);
		$this -> view -> pointlist = $pointlist['point'];
		$this -> view -> param = $this -> _request -> getParams();
		$pageNav = new Custom_Model_PageNav($pointlist['total'], null, 'ajax_search');
		$this -> view -> pageNav = $pageNav -> getNavigation();
	}

	/**
	 * 经验值变动历史
	 *
	 * @return void
	 */
	public function experienceListAction() {
		$page = (int)$this -> _request -> getParam('page', 1);

		$params = $this -> _request -> getParams();
		$count = $this -> _member -> getExperienceCount($params);

		$infos = array();
		if ($count > 0) {
			$limit = ($page - 1) * $this -> _page_size . ',' . $this -> _page_size;
			$infos = $this -> _member -> getExperienceList($params, $limit);
		}

		$pageNav = new Custom_Model_PageNav($count, $this -> _page_size, 'ajax_search');
		$this -> view -> pageNav = $pageNav -> getNavigation();
		$this -> view -> infos = $infos;
		$this -> view -> params = $params;
	}

	/**
	 * 积分变动频率
	 *
	 * @return void
	 */
	public function pointFrequencyAction() {
		$page = (int)$this -> _request -> getParam('page', 1);
		$pointlist = $this -> _member -> getPointFrequency($this -> _request -> getParams(), $page, 20);
		$this -> view -> pointlist = $pointlist['point'];
		$this -> view -> param = $this -> _request -> getParams();
		$pageNav = new Custom_Model_PageNav($pointlist['total'], null, 'ajax_search');
		$this -> view -> pageNav = $pageNav -> getNavigation();
	}

	/**
	 * 账户余额变动历史
	 *
	 * @return void
	 */
	public function moneylistAction() {
		$page = (int)$this -> _request -> getParam('page', 1);
		$moneylist = $this -> _member -> getAllMoney($this -> _request -> getParams(), $page, 20);
		$this -> view -> moneylist = $moneylist['money'];
		$this -> view -> param = $this -> _request -> getParams();
		$pageNav = new Custom_Model_PageNav($moneylist['total'], null, 'ajax_search');
		$this -> view -> pageNav = $pageNav -> getNavigation();

	}

	/**
	 * 会员礼金券信息查询
	 *
	 * @return void
	 */
	public function couponAction() {
		$page = (int)$this -> _request -> getParam('page', 1);
		$couponList = $this -> _member -> getCouponList($this -> _request -> getParams(), $page, 20);
		if ($couponList['total'] > 0) {
			foreach ($couponList['list'] as $num => $coupon) {
				if ($coupon['card_type'] == 3) {
					if ($coupon['coupon_price'] <= 0) {
						$couponList['list'][$num]['card_price'] = '全额抵扣';
					}
				} else if ($coupon['card_type'] == 4) {
					$couponList['list'][$num]['card_price'] = ($coupon['coupon_price'] / 10) . '折';
				}
			}
		}
		$this -> view -> coupon_list = $couponList['list'];
		$this -> view -> param = $this -> _request -> getParams();
		$this -> view -> curtime = date('Y-m-d');
		$pageNav = new Custom_Model_PageNav($couponList['total'], null, 'ajax_search');
		$this -> view -> pageNav = $pageNav -> getNavigation();
	}

	/**
	 * 礼金券查询
	 *
	 * @return void
	 */
	public function couponQueryAction() {
		$param = $this -> _request -> getParams();
		if ($param['card_sn'] !== null) {
			$card_sn = strtolower($param['card_sn']);
			$card_password = strtolower($param['card_password']);
			if ($card_sn) {
				if ($card_password) {
					$sn = Custom_Model_Encryption::getInstance() -> decrypt(array('sn' => $card_sn, 'pwd' => $card_password), 'coupon');
				} else {
					$sn = base_convert(substr($card_sn, 3), 26, 10);
				}
				if ($sn) {
					$couponAPI = new Admin_Models_DB_Coupon();
					$couponLog = array_shift($couponAPI -> getLog(" and range_from <= {$sn} and range_end >= {$sn}"));
					if ($couponLog) {
						$couponMember = $couponAPI -> getCouponList(" and A.log_id = {$couponLog['log_id']} and A.card_sn = '{$card_sn}'");
						$couponMember = $couponMember['list'][0];
						if ($couponMember) {
							$couponMember['add_time'] = date('Y-m-d H:i:s', $couponMember['add_time']);
							$orderInfo = $this -> _member -> getOrderBatchGoodsByCardSN($card_sn);
							$this -> view -> orderInfo = $orderInfo;
						}
						if ($couponLog['card_type'] == 3) {
							$goods_info = unserialize($couponLog['goods_info']);
							$goods_ids = array();
							$goods_api = new Admin_Models_API_Goods();
							foreach ($goods_info as $key => $value) {
								$goods_sn[] = "'" . $key . "'";
							}
							$goods_data = $goods_api -> get('a.goods_sn in (' . implode(',', $goods_sn) . ')', 'a.goods_sn,a.goods_name');

							if ($goods_data) {
								for ($i = 0; $i < count($goods_data); $i++) {
									$goods_name[$goods_data[$i]['goods_sn']] = $goods_data[$i]['goods_name'];
								}
							}
						}

						$this -> view -> data = $couponLog;
						$this -> view -> couponMember = $couponMember;
						$this -> view -> goods_info = $goods_info;
						$this -> view -> goods_name = $goods_name;
					} else {
						$this -> view -> error = 'no_card_log';
					}
				} else {
					$this -> view -> error = 'no_card';
				}
			} else {
				$this -> view -> error = 'no_card_sn';
			}
		}

		$this -> view -> param = $param;
	}

	/**
	 * 删除管理员
	 *
	 * @return void
	 */
	public function setCouponAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$card_sn = trim($this -> _request -> getParam('card_sn'));
		if ($card_sn) {
			$result = $this -> _member -> setCoupon($card_sn);
			switch ($result) {
				default :
					exit('ok');
			}
		} else {
			exit('error!');
		}
	}

	/**
	 * 礼品卡查询
	 *
	 * @return void
	 */
	public function giftQueryAction() {
		$param = $this -> _request -> getParams();
		if ($param['card_sn'] !== null) {
			$card_sn = strtolower($param['card_sn']);
			$card_password = strtolower($param['card_password']);
			if ($card_sn) {
				$where = array('card_sn' => $card_sn);
				if ($card_password) {
					$where['card_pwd'] = $card_password;
				}

				$cardAPI = new Admin_Models_API_GiftCard();
				$datas = $cardAPI -> getCardlist($where);
				$card = $datas['content'][0];
				if ($card) {
					$this -> view -> data = $card;
					$this -> view -> orderInfo = $cardAPI -> getCardOrderInfo($card_sn);
				} else {
					$this -> view -> error = 'no_card';
				}
			} else {
				$this -> view -> error = 'no_card_sn';
			}
		}

		$this -> view -> param = $param;
	}
	/**
	 * 会员礼品卡信息查询
	 *
	 * @return void
	 */
	public function giftCardAction() {
		$page = (int)$this -> _request -> getParam('page', 1);
		$search = $this -> _request -> getParams();
		$search['username!='] = '';
		$giftCardList = $this -> _member -> getGiftCardList($search, $page, 20);
		$this -> view -> gift_card_list = $giftCardList['list'];
		$this -> view -> param = $search;
		$this -> view -> curtime = date('Y-m-d');
		$pageNav = new Custom_Model_PageNav($giftCardList['total'], null, 'ajax_search');
		$this -> view -> pageNav = $pageNav -> getNavigation();
	}

	/**
	 * 电话下单
	 *
	 * @return void
	 */
	public function phoneAction() {
		$this -> _auth = Admin_Models_API_Auth::getInstance() -> getAuth();
		$this -> view -> operator_id = $this -> _auth['admin_id'];
		$page = (int)$this -> _request -> getParam('page', 1);
		$memberMessages = $this -> _member -> getMemberBySearch($this -> _request -> getParams(), $page);
		$total = $this -> _member -> getMemberCountBySearch($this -> _request -> getParams());
		foreach ($memberMessages as $num => $memberMessage) {
			$memberMessages[$num]['add_time'] = ($memberMessages[$num]['add_time'] > 0) ? date('Y-m-d', $memberMessages[$num]['add_time']) : '';
			$memberMessages[$num]['last_login'] = ($memberMessages[$num]['last_login'] > 0) ? date('Y-m-d', $memberMessages[$num]['last_login']) : '';
			$memberMessages[$num]['status'] = $this -> _member -> ajaxStatus($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('status'), $memberMessages[$num]['user_id'], $memberMessages[$num]['status']);
			$memberMessages[$num]['utype'] = 'member';
			$memberMessages[$num]['auth_code'] = Custom_Model_Encryption::getInstance() -> encrypt($memberMessages[$num], 'UserAuth');
		}
		$this -> view -> member_list = $memberMessages;
		$this -> view -> member_ranks = $this -> _memberAllRanks;
		$this -> view -> param = $this -> _request -> getParams();

		$pageNav = new Custom_Model_PageNav($total, null, 'ajax_search');
		$this -> view -> pageNav = $pageNav -> getNavigation();
	}

	/**
	 * 添加会员
	 *
	 * @return void
	 */
	public function addAction() {
		if ($this -> _request -> isPost()) {
			$this -> _helper -> viewRenderer -> setNoRender();
			$result = $this -> _member -> editMember($this -> _request -> getPost());
			switch ($result) {
				case 'noUserName' :
					Custom_Model_Message::showMessage(self::NO_USERNAME);
					break;
				case 'noSamePassword' :
					Custom_Model_Message::showMessage(self::NO_SAME_PASSWORD);
					break;
				case 'noPassword' :
					Custom_Model_Message::showMessage(self::NO_PASSWORD);
					break;
				case 'tooManyAddress' :
					Custom_Model_Message::showMessage(self::TOO_MANY_ADDRESS);
					break;
				case 'memberExists' :
					Custom_Model_Message::showMessage(self::MEMBER_EXISTS);
					break;
				case 'addMemberSucess' :
					Custom_Model_Message::showMessage(self::ADD_MEMBER_SUCESS, 'event', 1250, "Gurl()");
					break;
				case 'error' :
					Custom_Model_Message::showMessage('error!', 'event', 1250, "Gurl()");
			}
		} else {
			$this -> view -> action = 'add';
			$this -> view -> title = '添加会员';
			$this -> view -> memberRanks = $this -> _memberAllRanks;
			$this -> view -> sexRadios = $this -> _sex;
			$this -> view -> member = array('birthday' => '--');
			$this -> view -> province = $this -> _member -> getChildAreaById(1);
			$this -> render('edit');
		}
	}

	/**
	 * 快速注册会员
	 *
	 * @return void
	 */
	public function quickRegAction() {
		if ($this -> _request -> isPost()) {
			$this -> _helper -> viewRenderer -> setNoRender();
			$post = $this -> _request -> getPost();
			$password = md5(trim($post['user_name']));
			$reg = array('user_name' => $post['user_name'], 'password' => $post['user_name'], 'confirm_password' => $post['user_name'], 'rank_id' => '1');

			$result = $this -> _member -> editMember($reg);
			switch ($result) {
				case 'memberExists' :
					Custom_Model_Message::showMessage(self::MEMBER_EXISTS);
					break;
				case 'addMemberSucess' :
					Custom_Model_Message::showMessage(self::ADD_MEMBER_SUCESS, $this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('phone') . '/do/search/user_name/' . $post['user_name']);
					break;
				case 'error' :
					Custom_Model_Message::showMessage('error!', 'event', 1250, "Gurl()");
			}
		}
	}

	/**
	 * 编辑会员
	 *
	 * @return void
	 */
	public function editAction() {
		$id = (int)$this -> _request -> getParam('id', null);
		if ($id > 0) {

			if ($this -> _request -> isPost()) {
				$this -> _helper -> viewRenderer -> setNoRender();
				$result = $this -> _member -> editMember($this -> _request -> getPost(), $id);

				switch ($result) {
					case 'noUserName' :
						Custom_Model_Message::showMessage(self::NO_USERNAME);
						break;
					case 'noSamePassword' :
						Custom_Model_Message::showMessage(self::NO_SAME_PASSWORD);
						break;
					case 'noPassword' :
						Custom_Model_Message::showMessage(self::NO_PASSWORD);
						break;
					case 'tooManyAddress' :
						Custom_Model_Message::showMessage(self::TOO_MANY_ADDRESS);
						break;
					case 'memberNoExists' :
						Custom_Model_Message::showMessage(self::MEMBER_NO_EXISTS);
						break;
					case 'memberExists' :
						Custom_Model_Message::showMessage(self::MEMBER_EXISTS);
						break;
					case 'editMemberSucess' :
						Custom_Model_Message::showMessage(self::EDIT_MEMBER_SUCESS, 'event', 1250, 'Gurl()');
						break;
					case 'error' :
						Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
				}
			} else {
				$this -> view -> action = 'edit';
				$this -> view -> title = '修改会员';
				$this -> view -> changePassword = '不改请留空';
				$member = $this -> _member -> getMemberByUserId($id);
				$this -> view -> member = $member;
				$this -> view -> memberRanks = $this -> _memberAllRanks;
				$this -> view -> sexRadios = $this -> _sex;
				$this -> view -> memberAddress = $this -> _member -> getAddressByMemberId($member['member_id']);
				$this -> view -> province = $this -> _member -> getChildAreaById(1);
			}
		} else {
			Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
		}
	}

	/**
	 * 查看会员
	 *
	 * @return void
	 */
	public function viewAction() {
		$id = (int)$this -> _request -> getParam('id', null);
		if ($id > 0) {
			$this -> view -> title = '查看会员';
			$member = $this -> _member -> getMemberByUserId($id);
			$member['sex'] = $this -> _sex[$member['sex']];
			$member['rank'] = $this -> _memberAllRanks[$member['rank_id']];
			$this -> view -> member = $member;
			$this -> view -> memberAddress = $this -> _member -> getAddressByMemberId($member['member_id']);
		} else {
			Custom_Model_Message::showMessage('error!', 'event', 1250, 'Gurl()');
		}
	}

	/**
	 * 取得配送区域
	 *
	 * @return void
	 */
	public function areaAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$id = $this -> _request -> getParam('id', null);
		$area = $this -> _member -> getChildAreaById($id);

		if ($area) {
			exit(Zend_Json::encode($area));
		}
	}

	/**
	 * 删除会员
	 *
	 * @return void
	 */
	public function deleteAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$id = (int)$this -> _request -> getParam('id', 0);

		if ($id) {
			$result = $this -> _member -> deleteMemberByUserId($id);

			switch ($result) {
				case 'deleteSucess' :
					break;
				case 'error' :
					exit('error!');
			}
		} else {
			exit('error!');
		}
	}

	/**
	 * 会员状态更改
	 *
	 * @return void
	 */
	public function statusAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$id = (int)$this -> _request -> getParam('id', 0);
		$status = (int)$this -> _request -> getParam('status', 0);

		if ($id > 0) {
			$this -> _member -> changeStatus($id, $status);
		} else {
			Custom_Model_Message::showMessage('error!');
		}

		echo $this -> _member -> ajaxStatus($this -> getFrontController() -> getBaseUrl() . $this -> _helper -> url('status'), $id, $status);
		exit ;
	}

	/**
	 * 检查会员是否存在
	 *
	 * @return void
	 */
	public function checkAction() {
		$this -> _helper -> viewRenderer -> setNoRender();
		$username = $this -> _request -> getParam('val', null);
		if (!empty($val)) {
			$result = $this -> _member -> getMemberByUserName($username);
			if (!empty($result)) {
				exit(self::MEMBER_EXISTS);
			}
		}
	}

	/**
	 * 账户变动
	 *
	 * @return void
	 */
	public function accountAction() {
		$type = $this -> _request -> getParam('type', null);
		$id = (int)$this -> _request -> getParam('id', 0);

		if ($type && $id > 0) {

			if ($this -> _request -> isPost()) {
				$this -> _helper -> viewRenderer -> setNoRender();
				$result = $this -> _member -> editAccount($id, $type, $this -> _request -> getPost());

				if ($result == true) {
					$member = $this -> _member -> getMemberByMemberId($id);
					echo "<script>parent.$('" . $type . "Value').innerHTML = '" . $member[$type] . "';</script>";

					switch ($type) {
						case 'money' :
							$message = self::EDIT_MONEY_SUCESS;
							break;
						case 'point' :
							$message = self::EDIT_POINT_SUCESS;
							break;
						case 'experience' :
							$message = self::EDIT_EXPERIENCE_SUCESS;
					}
					Custom_Model_Message::showMessage($message, 'event', 1250, 'closeWin()');
				} else {
					Custom_Model_Message::showMessage('error');
				}
			} else {
				$this -> view -> action = 'account';
				$this -> view -> accountType = $type;
				$this -> view -> accountName = $this -> _accountType[$type];
				$member = $this -> _member -> getMemberByMemberId($id);
				$this -> view -> accountValue = $member[$type];
				$this -> view -> member = $member;
			}
		}
	}

	/**
	 * 账户变动历史记录
	 *
	 * @return void
	 */
	public function accountListAction() {
		$type = $this -> _request -> getParam('type', null);
		$id = (int)$this -> _request -> getParam('id', 0);

		if ($type && $id > 0) {
			$page = (int)$this -> _request -> getParam('page', 1);
			$accounts = $this -> _member -> getAccount($id, $type, $page, 10);
			$total = $this -> _member -> getAccountCount($id, $type);
			$this -> view -> accountName = $this -> _accountType[$type];
			$this -> view -> accounts = $accounts;
			$pageNav = new Custom_Model_PageNav($total, 10, 'account-list');
			$this -> view -> pageNav = $pageNav -> getNavigation();
		}
	}


	/**
	 * 导出用户基本信息
	 *
	 * @return   void
	 */
	public function exportUserAction() {
		Zend_Controller_Front::getInstance() -> unRegisterPlugin(Custom_Controller_Plugin_Layout);
		$this -> _helper -> viewRenderer -> setNoRender();
		$data = $this -> _member -> getExportUser($this -> _request -> getParams());
		exit ;
	}

	/**
	 * 改变会员等级
	 *
	 * @return   void
	 */
	public function chgrankAction() {
		$id = (int)$this -> _request -> getParam('id', 0);
		exit($this -> _memberRanks[$id]['discount']);
	}



}

