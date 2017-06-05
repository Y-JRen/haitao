<?php

class Shop_Models_API_Card
{
	/**
     * 卡 DB
     * 
     * @var Shop_Models_DB_Card
     */
	private $_db = null;
	
	/**
     * 用户认证名字空间名称
     * 
     * @var string
     */
	private $_userCertificationName = 'Card';
	
	/**
     * 用户认证名字空间
     * 
     * @var Zend_Session_Namespace
     */
	private $_certificationNamespace = null;
	
	
	/**
     * 对象初始化
     * 
     * @return void
     */
	public function __construct()
    {
        $this -> _certificationNamespace = new Zend_Session_Namespace($this -> _userCertificationName);
        $this -> _db = new Shop_Models_DB_Card();
	}
	
    /**
     * 取得卡认证信息
     *
     * @param    void
     * @return   array
     */
    public function getCardMsg()
    {
     	if (!$this -> _certificationNamespace -> cardCertification) {
			return false;
		}
		return $this -> _certificationNamespace -> cardCertification;
    }
    
    /**
     * 清除卡认证信息
     *
     * @param    void
     * @return   array
     */
    public function clearCardMsg()
    {
     	unset($this -> _certificationNamespace -> cardCertification);
     	unset($this -> _certificationNamespace -> useMsg);
    }
    
    /**
     * 取礼品卡信息
     *
     * @param    int    $sn
     * @return   void
     */
    public function getGift($where = null)
    {
        $data = @array_shift($this -> _db -> getGift($where));
        $data['able_price'] = $data['card_real_price'] - $data['occupy_price'];
        return $data;
    }


    /**
     * 取得卡使用信息
     *
     * @param    void
     * @return   array
     */
    public function setCardMsg($products)
    {
     	$cards = $this -> getCardMsg();
     	$useMsg['coupon'] = $useMsg['ncoupon'] = $useMsg['gift'] = $useMsg['virtual'] = 'noUse';
     	if ($cards) {
     		foreach ($cards as $key => $card)
     		{
     			if (!$this -> filterCard($card, $products)) {
     				$this -> deleteCard($card['type']);
     			}
     		}
     		$this -> getCardMsg() && $useMsg['coupon'] = $useMsg['ncoupon'] = $useMsg['gift'] = $useMsg['virtual'] = 'used';
     	}
     	$canUse = false;
     	if (!$canUse && $products['data']) {
     		foreach ($products['data'] as $key => $goods)
     		{
     			if (!$offerProduct || ($offerProduct && !isset($offerProduct[$goods['product_id']]))) {
     				if ($goods['is_gift'] == 0) {//0 能使用卡 1不能使用 ;is_gift 单独购买是否能使用礼品卡
     				    $canUse = true;
     			        break;
     			    }
     			}
     		}
     	}
     	if ($canUse == false) {
     		$useMsg['coupon'] = 'noAuth';
     		$cards && $cards['card_type'] == 1 && $this -> deleteCard('coupon');
     		$cards && $cards['card_type'] == 1 && $this -> deleteCard('old');
     	}
     	if ($cards) {
     		$products['card'] = $cards;
     	}
     	$useMsg && $this -> _certificationNamespace -> useMsg = $useMsg;
        return $products;
    }
    
    /**
     * 认证卡号和密码
     *
     * @param    array    $card
     * @param    array    $products
     * @return   void
     */
    public function filterCard($card, $products)
    {
    	if (substr($card['card_sn'], 0, 1) == 'c') {
            $sn = Custom_Model_Encryption::getInstance() -> decrypt(array('sn' => $card['card_sn'], 'pwd' => $card['card_pwd']), 'coupon');
     		if ($sn > 0) {
     			$data = @array_shift($this -> _db -> getCoupon(array('card_sn' => $card['card_sn'], 'card_pwd' => $card['card_pwd'])));
     			$_auth = Shop_Models_API_Auth :: getInstance() -> getAuth();
     			if (!$data || ($data['status'] == 0 && ($data['user_id'] == $_auth['user_id'] || $data['is_repeat'] == 1 ))) {
     				$coupon = $this -> getCouponConfigBySn($sn);
     				if ($coupon) {
     					if ($coupon['status'] == 1) {
     						return false;
     					}
     					if (($coupon['card_type'] ==1) || ($coupon['card_type'] == 3) || ($coupon['card_type'] == 4 || ($coupon['card_type'] == 5))) { 
     					    if ( $coupon['min_amount'] > 0 && $coupon['min_amount'] > $products['amount'] ) {
     						    return false;
     						}
     					} 
     					if ($coupon['end_date'] < date('Y-m-d')) {
     			    		return false;
     			    	}

						if ($coupon['start_date'] >  date('Y-m-d')) {
							return false;
						}

     			    	if (($coupon['card_type'] != 3) && ($coupon['card_type'] != 4) && ($coupon['card_type'] != 5)) {
     			    	    if ($coupon['card_price'] < $card['card_price']) {
     			    	        return false;
     			    	    }
     			    	    if ($card['card_price'] > $products['amount']) {
         						return false;
         					}
     			    	}
     			    	
     			    	//如果只有团购商品，不允许使用礼券
                        if ($products['data']) {
                            $onlyTuan = true;
                            foreach ($products['data'] as $v) {
                                if ( !$v['tuan_id'] ) {
                                    $onlyTuan = false;
                                    break;   
                                }
                            }
                            if ( $onlyTuan ) {
                                return false;
                            }
                        }
     			    	
     			    	if ($coupon['card_type'] == 3 || $coupon['card_type'] == 5) {    //商品抵扣/组合商品抵扣，重新计算抵扣卡金额
     			    	    if ($_COOKIE['gift'] || $_COOKIE['order_gift'] || $_COOKIE['order_buy_gift']) {
                                    return  false;
                            }

                            $card['card_price'] = 0;
                            $goods_info = unserialize($coupon['goods_info']);
                            $tmp = 0;
                            
                            if ( $coupon['card_type'] == 3 ) {
                                $field1 = 'data';
                                $field2 = 'product_sn';
                                $field3 = 'price';
                            }
                            else if ( $coupon['card_type'] == 5 ) {
                                $field1 = 'group_goods_summary';
                                $field2 = 'group_sn';
                                $field3 = 'group_price';
                            }
                            
                            foreach ($products[$field1] as $v) {
                                if ($goods_info[$v[$field2]]) { //如果存在可以抵扣的商品才能抵扣
                                    if ($v['number'] > $goods_info[$v[$field2]]) {
                                        if ($coupon['price_recount']) {
                                            $goods_num = floor($v['number'] / $goods_info[$v[$field2]]);
                                        }
                                        else {
                                            $goods_num = $goods_info[$v[$field2]];
                                        }
                                    }
                                    else {
                                        $goods_num = $v['number'];
                                    }
                                    
                                    if ($coupon['card_price'] == 0.00) { //全额抵扣
                                        $card['card_price'] += $v[$field3] * $goods_num;
                                    }
                                    else {
                                        $card['card_price'] += $coupon['card_price'] * $goods_num;
                                    }
                                    $tmp = 1;
                                }
                            }
                            if ( $tmp == 0 )    return false;
                            else {
                                $this -> _certificationNamespace -> cardCertification[$card['card_sn']] = $card;
                            }
     			    	}
     			    	if ($coupon['card_type'] == 4) {        //订单金额折扣卡
     			    	    $card['card_price'] = $products['amount'] - round($products['amount'] * $coupon['card_price'] / 100, 0);
     			    	    $this -> _certificationNamespace -> cardCertification[$card['card_sn']] = $card;
     			    	}
     			    	
     			    	//购买指定商品
         			    if ( ($coupon['card_type'] == 0) || ($coupon['card_type'] == 1) || ($coupon['card_type'] == 4) ) {
         			        $goods_info = unserialize($coupon['goods_info']);
         			        if ($goods_info['allGoods']) {
         			            $hasGoods = false;
         			            unset($goodsDiscount);unset($goods_id);
         			            parse_str($goods_info['allGoods']);
         			            foreach ($goodsDiscount as $key => $value) {
         			                $goods_id[] = $key;
         			            }
                                
                                if ( $products['data'] ) {
                                    foreach ($products['data'] as $v) {
                                        if ( in_array($v['goods_id'], $goods_id) ) {
                                            $hasGoods = true;
                                            break;
                                        }
                                    }
                                }
         			        }
         			    	if ( ($hasGoods === false)) {
         			    	    return false;
         			    	}
         			    }
     			    	
     				} else {
     					return false;
     				}
     			} else {
     				return false;
     			}
     		} else {
     			return false;
     		}
     		return true;
    	} elseif (substr($card['card_sn'], 0, 1) == 'g' || substr($card['card_sn'], 0, 3) == '999') {
    		if (substr($card['card_sn'], 0, 3) == '999' || Custom_Model_Encryption::getInstance() -> decrypt(substr($card['card_sn'], 1), 'giftCard')) {
    	    	$data = $this -> getGift(array('card_sn' => $card['card_sn'], 'card_pwd' => $card['card_pwd'], 'status' => 0));
     			if ($data) {
     			    if ($data['end_date'] < date('Y-m-d')  || $data['card_price'] < $card['card_price']) {
     			    	return  false;
     			    }
     			} else {
     			    return false;
     			}
    	    } else {
     			return false;
     		}
     		return true;
    	} elseif (substr($card['card_sn'], 0, 1) == 'v') {
    		if (Custom_Model_Encryption::getInstance() -> decrypt(substr($card['card_sn'], 1), 'giftCard')) {
    	    	$data = array_shift($this -> _db -> getVirtual(array('card_id' => intval(substr($card['card_sn'], 2, -1)), 'card_pwd' => $card['card_pwd'], 'status' => 0)));
     			    
     			if ($data) {
     			    if ($data['card_price'] < $card['card_price']) {
				    	return false;
					}
     			} else {
     			    return false;
     			}
    	    } else {
     			return false;
     		}
     		return true;
     	} elseif (substr($card['card_sn'], 0, 1) == 's') {
     	    $data = $this -> checkGoodsCard($card['card_sn'], $card['card_pwd']);
     	    if (is_array($data)) {
                $goodsInfo = unserialize($data['goods_info']);
                $hasGoods = false;
                if ( $products['data'] ) {
                    foreach ($products['data'] as $v) {
                        if (in_array($v['product_sn'], $goodsInfo)) {
                            $hasGoods = true;
                            break;
                        }
                    }
                }
                if (!$hasGoods) {
                    return false;
                }
     	    }
     	    else {
     	        return false;
     	    }
     	    return true;
    	} elseif (substr($card['card_sn'], 0, 1) == 'u') {
            return true;
        }else {
			if ($data) {
				if ($data['card_type'] < $card['card_price'] || $data['end_date'] < time() || $data['start_date'] > date('Y-m-d') || ($data['is_normal'] ==1 && 150 > $products['amount'])) {
				    return false;
				}
     		} else {
				return false;
			}
			return true;
    	}
    }
	
	/**
     * 认证卡号和密码
     *
     * @param    string    $cardSn
     * @param    string    $cardPassword
     * @param    string    $price          //订单总额
     * @param    string    $fare           //运费
     * @return   void
     */
    public function checkCard($cardSn, $cardPassword, $price = null, $fare = null, $param = null)
    {   
        $cardSn = trim($cardSn);
        $cardPassword = trim($cardPassword);
    	$filterChain = new Zend_Filter();
        $filterChain -> addFilter(new Zend_Filter_StripTags());
        
        $cardSn = $filterChain -> filter($cardSn);
        $cardPassword = $filterChain -> filter($cardPassword);
        
        if (strpos($cardSn, '-') > 0) {
        	$orgSn = $cardSn;
        	$sn = explode('-', $cardSn);
        	$cardSn = $sn[0];
        }
        $cardSn = strtolower($cardSn);
        
        if ($_SESSION['price_point']) {
            return 'usePoint';
        }
    	if ($cardSn{0} == 'c') {
    		//新版礼券
    		if ($this -> _certificationNamespace -> useMsg['coupon'] == 'noUse' || $this -> _certificationNamespace -> useMsg['ncoupon'] == 'noUse') {
            	$sn = Custom_Model_Encryption::getInstance() -> decrypt(array('sn' => $cardSn, 'pwd' => $cardPassword), 'coupon');
     			if ($sn > 0) {
     			    $cardPassword = substr($cardPassword, 0, 8);
     			    $data = array_shift($this -> _db -> getCoupon(array('card_sn' => $cardSn, 'card_pwd' => $cardPassword)));
     			    
     			    $_auth = Shop_Models_API_Auth :: getInstance() -> getAuth();
     			    if (!$data || (($data['status'] == 0 && ($data['user_id'] == $_auth['user_id']) || $data['is_repeat'] == 1 ))) {
     			    	$coupon = $this -> getCouponConfigBySn($sn);
     			    	if ($coupon) {
                            $cartApi = new Shop_Models_API_Cart();
                            $product = $cartApi -> getCartProduct();
                            
     			    		if ($coupon['card_type'] == 2) {    //已取消该类型卡
                                if ($_COOKIE['gift'] || $_COOKIE['order_gift'] || $_COOKIE['order_buy_gift']) {
                                    return  'canNotUseWidthGift'; 
                                }
                                
                                if ($product['data']) {
                                    $tmp = 0;
                                    foreach ($product['data'] as $v) {
                                        if ($v['product_id'] == $coupon['parent_id']){//如果存在可以抵扣的商品才能抵扣
                                            $data['card_price'] = $v['price'];
                                            $tmp = 1;
                                            break;
                                        }
                                    }
                                }
                                if (!$tmp) {
                                    return  'canNotUse';
                                }
                            }
                            else if ($coupon['card_type'] == 3 || $coupon['card_type'] == 5) {   //商品抵扣卡/组合商品抵扣卡
                                if ($_COOKIE['gift'] || $_COOKIE['order_gift'] || $_COOKIE['order_buy_gift']) {
                                    return  'canNotUseWidthGift'; 
                                }
                                
                                if ( $coupon['card_type'] == 3 ) {
                                    $field1 = 'data';
                                    $field2 = 'product_sn';
                                    $field3 = 'price';
                                }
                                else if ( $coupon['card_type'] == 5 ) {
                                    $field1 = 'group_goods_summary';
                                    $field2 = 'group_sn';
                                    $field3 = 'group_price';
                                }
                                
                                if ($product[$field1]) {
                                    $goods_info = unserialize($coupon['goods_info']);
                                    $fare = $fare ? $fare : 0;
                                    if ($coupon['min_amount'] > 0){
                                        if ($coupon['min_amount'] > ($price-$fare)) {   //订单最小金额
                                            return 'minAmount';
                                        }
                                    }
                                    $tmp = 0;
                                    $data['card_price'] = 0;
                                    
                                    foreach ($product[$field1] as $v) {
                                        if ($goods_info[$v[$field2]]) { //如果存在可以抵扣的商品才能抵扣
                                            if ($v['number'] > $goods_info[$v[$field2]]) {
                                                if ($coupon['price_recount']) {
                                                    $goods_num = floor($v['number'] / $goods_info[$v[$field2]]);
                                                }
                                                else {
                                                    $goods_num = $goods_info[$v[$field2]];
                                                }
                                            }
                                            else {
                                                $goods_num = $v['number'];
                                            }
                                            
                                            if ($coupon['card_price'] == 0.00) { //全额抵扣
                                                $data['card_price'] += $v[$field3] * $goods_num;
                                            }
                                            else {
                                                $data['card_price'] += $coupon['card_price'] * $goods_num;
                                            }
                                            $tmp = 1;
                                        }
                                    }
                                }
                                
                                if (!$tmp) {
                                    return  'canNotUse';
                                }
                            }
                            else if ($coupon['card_type'] == 4) {   //订单金额折扣卡
                                if ($coupon['min_amount'] > 0){
                                    if ($coupon['min_amount'] > ($price-$fare)) {   //订单最小金额
                                        return 'minAmount';
                                    }
                                }
                                $data['card_price'] = $product['amount'] - round($product['amount'] * $coupon['card_price'] / 100, 0);
                            }
                            else {
                                $data['card_price'] = ($coupon['card_price'] > ($price-$fare)) ? ($price-$fare) : $coupon['card_price'];
     			    		}
     			    		
     			    		//购买指定商品
         			    	if ( ($coupon['card_type'] == 0) || ($coupon['card_type'] == 1) || ($coupon['card_type'] == 4) ) {
         			    	    $goods_info = unserialize($coupon['goods_info']);
         			    	    if ($goods_info['allGoods']) {
         			    	        $hasGoods = false;
         			    	        unset($goodsDiscount);unset($goods_id);
         			    	        parse_str($goods_info['allGoods']);
         			    	        foreach ($goodsDiscount as $key => $value) {
         			    	            $goods_id[] = $key;
         			    	        }

                                    if ( $product['data'] ) {
                                        foreach ($product['data'] as $v) {
                                            if ( in_array($v['goods_id'], $goods_id) ) {
                                                $hasGoods = true;
                                                break;
                                            }
                                        }
                                    }
         			    	    }
                                
         			    	
                                
         			    	    if ( ($hasGoods === false)  ) {
         			    	        return 'limitGoods';
         			    	    }
         			    	}
                            
                            if ($data['card_price'] <= 0) {
     						    return  'canNotUse';
     					    }
     					    
     			    		if ($coupon['status'] == 1) {
     						    return  'canNotUse';
     					    }
                            
     			    		if ($coupon['card_type'] == 1 && ($coupon['min_amount'] > 0 && $coupon['min_amount'] > $price)) {
     			    			return 'canNotUse';
     			    		}
                            
     			    		if ($coupon['end_date'] < date('Y-m-d')) {
     			    			return  'cardExpired';
     			    		}
     			    		
							if ($coupon['start_date'] > date('Y-m-d')) {
								return 'noStart';
							}

     			    		if (($coupon['card_price'] <= 0) && ($coupon['card_type']!=3) && ($coupon['card_type']!=5)) {
				    	        return 'noMoney';
					        }
					  
                      
     			    		$data['card_name'] = '优惠券';
     			    		$data['card_sn'] = $cardSn;
     			    		$data['card_pwd'] = $cardPassword;
     			    		$data['card_type'] = $coupon['card_type'];
     			    		$data['is_repeat'] = $coupon['is_repeat'];
     			    		$data['is_spec'] = $coupon['card_type'];
     			    		$data['type'] = 'coupon';
     			    		$data['log_id'] = $coupon['log_id'];
     			    		$data['parent_id'] = $coupon['parent_id'];
                            $data['is_affiliate'] = $coupon['is_affiliate'];
                            $data['is_limit_user'] = $coupon['is_limit_user'];
						    $data['start_date'] = $coupon['start_date'];
                            $data['end_date'] = $coupon['end_date'];
                            $data['goods_info'] = $coupon['goods_info'];
                            $data['freight'] = $coupon['freight'];
                            $data['aid'] = $coupon['aid'];
     			    		$this -> _certificationNamespace -> cardCertification[$data['card_sn']] = $data;
     			    		return $data;
     			    	} else {
     			    		return 'cardError';
     			    	}
     			    } else {
     			    	return 'cardUsed';
     			    }
     			} else {
     			    return 'cardError';
     			}
    		} elseif ($this -> _certificationNamespace -> useMsg['coupon'] == 'used' || $this -> _certificationNamespace -> useMsg['ncoupon'] == 'used' || $this -> _certificationNamespace -> useMsg['gift'] == 'used') {
    		    return 'cardExists';
    		} else {
    		    return 'canNotUse';
    		}
    	} elseif ($cardSn{0} == 'g' || substr($cardSn, 0 ,3) == '999') {
    	    //新版礼品卡
    	    if ($this -> _certificationNamespace -> useMsg['gift'] == 'noUse') {
    	    	if (substr($cardSn, 0 ,3) == '999' || Custom_Model_Encryption::getInstance() -> decrypt(substr($cardSn, 1), 'giftCard')) {
    	    		$data = $this -> getGift(array('card_sn' => $cardSn, 'card_pwd' => $cardPassword, 'status' => 0));
    	    		$_auth = Shop_Models_API_Auth :: getInstance() -> getAuth();
    	    		//只能使用自己的礼品卡 或 未绑定的礼品卡
     			    if ($data &&  ($_auth['user_id'] == $data['user_id'] || $data['user_id'] ==0) ) {
     			    	
     			    	if ($data['end_date'] < date('Y-m-d')) {
     			    		return  'cardExpired';
     			    	}
     			    	
     			    	if ($data['card_price'] <= 0 || $data['able_price'] <= 0) {
				    	    return 'noMoney';
					    }
					    
     			    	$data['card_name'] = '健康卡';
     			    	$data['type'] = 'gift';
     			    	$data['card_price'] = ($data['able_price'] > $price) ? $price : $data['able_price'];
     			    	$this -> _certificationNamespace -> cardCertification[$data['card_sn']] = $data;
     			    	return $data;
     			    } else {
     			    	return 'cardError';
     			    }
    	    	} else {
     			    return 'cardError';
     			}
    	    } else {
     		    return 'cardExists';
     		}
        } elseif ($cardSn{0} == 's') {      //提货卡
            $card = $this -> checkGoodsCard($cardSn, $cardPassword);
            if (is_array($card)) {
                $goodsInfo = unserialize($card['goods_info']);
                $cartApi = new Shop_Models_API_Cart();
                $product = $cartApi -> getCartProduct();
                $hasGoods = false;
                
                if ( $product['data'] ) {
                    foreach ($product['data'] as $v) {
                        if (in_array($v['product_sn'], $goodsInfo)) {
                            $hasGoods = true;
                            $price = $v['price'];
                            break;
                        }
                    }
                }
                if (!$hasGoods) {
                    return 'limitGoods';
                }
                
                $card['card_sn'] = $cardSn;
     		    $card['card_pwd'] = $cardPassword;
                $this -> setGoodsCardCertification($card, $price);
     		    return $data;
            }
            else {
                return $card;
            }
            
    	} elseif ($cardSn{0} == 'v') {
            return 'cardError';
     	} else {
    	    if ($this -> _certificationNamespace -> useMsg['coupon'] == 'used' || $this -> _certificationNamespace -> useMsg['ncoupon'] == 'used' || $this -> _certificationNamespace -> useMsg['gift'] == 'used') {
    		    return 'cardExists';
    		} else {
    		    return 'canNotUse';
    		}
    	}
    }

	/**
     * 清除卡信息
     *
     * @return   void
     */
    public function deleteCard($type)
    {
    	$cards = $this -> getCardMsg();
    	
    	if ($cards) {
    		foreach ($cards as $key => $card)
    		{
     			if ($card['type'] == $type) {
     				unset($cards[$key]);
     			} else {
     				$result[$key] = $card;
     			}
    		}
    		$this -> _certificationNamespace -> cardCertification = $result;
    	}
    }
    /**
     * 标志卡为已使用状态
     *
     * @param    array    $data
     *                    $data['user_id']		 	下单用户ID
     *                    $data['user_name']		下单用户名
     *                    $data['add_time']			下单时间
     *                    $data['parent_id']		绑定联盟ID
     *                    $data['parent_param']		绑定参数
     * @return   void
     */
    public function useCard($data)
    {
    	$cards = $this -> getCardMsg();
    	
    	if ($cards) {
    		foreach ($cards as $card) {
    			if ($card['type'] == 'coupon') {
    				$data['log_id'] = $card['log_id'];
    				$data['card_type'] = ($card['card_type']) ? $card['card_type'] : 0;
    				$data['is_repeat'] = $card['is_repeat'];
    				$data['card_price'] = $card['card_price'];
    				$data['card_sn'] = $card['card_sn'];
    				$data['card_pwd'] = $card['card_pwd'];
    				$data['status'] = (int)!$card['is_repeat'];
    				$data['parent_id'] = $card['parent_id'];
    				$data['parent_param'] = $card['parent_param'];
    				$data['goods_info'] = $card['goods_info'];
    				$data['freight'] = $card['freight'];
    				$result = $this -> _db -> useCoupon($data);
    				
    			} elseif ($card['type'] == 'gift') {
    				$data['card_type'] = $card['card_type'];
    				$data['card_price'] = $card['card_price'];
    				$data['card_sn'] = $card['card_sn'];
    				$data['card_pwd'] = $card['card_pwd'];
    				$data['use_type'] = 1;
    				$result = $this -> _db -> useGift($data);
    				
    				
    			} 
    		}
    		$this -> clearCardMsg();
    		return $result;
    	}
    }

    /**
     * 订单作废及取消时返还用户卡
     *
     * @param    array    $data
     *                    $data['card_sn']			卡sn
     *                    $data['card_price']       抵扣金额
     *                    $data['add_time']         返还时间
     * @return   void
     */
    public function unUseCard($data)
    {
    	if ($data['card_sn'] && $data['card_price']) {
    		if (substr($data['card_sn'], 0, 1) == 'c') {    			
    			$result = $this -> _db -> unUseCoupon($data);

    		} elseif (substr($data['card_sn'], 0, 1) == 'g' || substr($data['card_sn'], 0, 3) == '999') {
    			$result = $this -> _db -> unUseGift($data);
    			

    		} elseif (substr($data['card_sn'], 0, 1) == 'v') {
    			$data['card_id'] = intval(substr($data['card_sn'], 2, -1));
    			$result = $this -> _db -> unUseVirtual($data);
    		} 
    		return $result;
    	}
    }

    /**
     * 取得礼券配置
     *
     * @param    array    $where
     * @return   void
     */
    public function getCoupon($where)
    {
    	return @array_shift($this -> _db -> getCoupon($where));
    }



    /**
     * 取得礼券配置
     *
     * @param    int    $sn
     * @return   void
     */
    public function getCouponConfigBySn($sn)
    {
    	if ($sn > 0) {
    		return @array_shift($this -> _db -> getCouponLog(' AND range_from <=' . $sn . ' AND range_end >='.$sn));
    	}
    }
    /**
     * 添加单一系统礼券
     *
     * @param    void
     * @return   string
     */
	public function addSysCoupon($data)
	{
		$data['card_type']!== null || $data['card_type'] = 1;
		$data['is_repeat'] || $data['is_repeat'] = 0;
		$data['card_price'] !== null || $data['card_price'] = 10;
		if($data['card_type'] == '1'){
		  $data['min_amount'] !== null || $data['min_amount'] = 10;
		}
		$position = $this -> _db -> getPosition();
		$newPosition = ($position) ? intval($position) +1 : 1;
		$data['number'] || $data['number'] = 1;
		$data['range_from'] = $newPosition;
		$data['range_end'] = $newPosition + $data['number'] - 1;
		$data['admin_name'] || $data['admin_name'] = 'SYSTEM';
		$data['add_time'] = time();
		$data['start_date'] || $data['start_date'] = date('Y-m-d', mktime(0, 0, 0, date('m')+2, 0, date('Y')));
		$data['end_date'] || $data['end_date'] = date('Y-m-d', mktime(0, 0, 0, date('m')+2, 0, date('Y')));
		$data['note'] || $data['note'] = '系统发放';
		$data['is_system'] = 1;
		$insertId = $this -> _db -> addCouponLog($data);
		$data['log_id'] = $insertId;
		for ($i = 0; $i < $data['number']; $i++) {
		    $encode = Custom_Model_Encryption::getInstance() -> encrypt($newPosition, 'coupon');
    		$data['card_sn'] = $encode['sn'];
    		$data['card_pwd'] = $encode['pwd'];
    		$data['user_id'] = $data['user_id'];
    		$data['user_name'] = $data['user_name'];
    		
    		if ( !$this -> _db -> addSysCoupon($data)) {
    		    return 'error';
    		}
    		
    		$newPosition++;
		}
		return 'addCouponSucess';
	}
	
	/**
     * 验证提货卡
     *
     * @param   string      $cardSn
     * @param   string      $cardPwd
     * @return  int
     */
    public function checkGoodsCard($cardSn, $cardPwd)
    {
        if (trim($cardSn) == '') {
            return 'cardSnEmpty';
        }
        if (trim($cardPwd) == '') {
            return 'cardPwdEmpty';
        }
        
        $card = $this -> _db -> getGoodsCard(array('card_sn' => $cardSn, 'card_pwd' => $cardPwd));
        if (!$card) {
            return 'cardError';
        }
        if ($card['status'] == 0 || $card['log_status'] == 1) {
            return 'canNotUse';
        }
        if ($card['status'] == 2) {
            return 'cardUsed';
        }
        if ($card['status'] == 3) {
            return 'canNotUse';
        }
        if ($card['end_date'] < time()) {
            return 'cardExpired';
        }

        return $card;
    }
    
    /**
     * 获得提货卡商品信息
     *
     * @param   array      $card
     */
    public function getGoodsCardInfo($card)
    {
        $card['goods_info'] = unserialize($card['goods_info']);
        foreach ($card['goods_info'] as $goods_sn) {
            if (strlen($goods_sn) == 7) {
                $goodsSNArray[] = $goods_sn;
            }
            else if (strlen($goods_sn) == 9) {
                $groupSNArray[] = $goods_sn;
            }
        }
        
        if ($goodsSNArray) {
            $goodsData = $this -> _db -> getGoods("goods_sn in (".implode(',', $goodsSNArray).")");
            foreach ($goodsData as $goods) {
                if ($goods['onsale'] == 1)  continue;
                
                $result[] = array('goods_id' => $goods['goods_id'],
                                  'goods_name' => $goods['goods_name'],
                                  'style' => $goods['goods_style'],
                                  'price' => $goods['price'],
                                  'market_price' => $goods['market_price'],
                                  'goods_sn' => $goods['goods_sn'],
                                  'goods_img' => $goods['goods_img'],
                                 );
            }
        }
        return $result;
    }
    
    /**
     * 设置初始提货卡信息
     *
     * @param   array      $card
     * @param   int         $goodsPrice
     */
    public function setGoodsCardCertification($card, $goodsPrice) {
        $data['card_name'] = '提货券';
        $data['card_sn'] = $card['card_sn'];
        $data['card_pwd'] = $card['card_pwd'];
        $data['type'] = 'goods';
        $data['log_id'] = $card['log_id'];
        $data['end_date'] = $card['end_date'];
		$data['start_date'] = $card['start_date'];
        $data['goods_info'] = $card['goods_info'];
        $data['card_price'] = $goodsPrice;
        $data['freight'] = 10;
        
        $this -> _certificationNamespace -> cardCertification[$card['card_sn']] = $data;
    }
    
    /**
     * 设置当前用户已验证提货卡
     *
     * @param   string      $cardSn
     * @param   string      $cardPwd
     */
    public function setCurrentGoodsCard($cardSn, $cardPwd) {
        $namespace = new Zend_Session_Namespace('GoodsCard');
        $namespace -> cardCertification = array($cardSn, $cardPwd);
    }
    
    /**
     * 获取当前用户已验证提货卡
     */
    public function getCurrentGoodsCard() {
        $namespace = new Zend_Session_Namespace('GoodsCard');
        return $namespace -> cardCertification;
    }
    
    /**
     * 清空当前用户已验证提货卡
     */
    public function clearCurrentGoodsCard() {
        $namespace = new Zend_Session_Namespace('GoodsCard');
        $namespace -> cardCertification = '';
    }
    
    /**
     * 获得当前使用的券信息
     */
    public function getCardCertificationNamespace() {
        return $this -> _certificationNamespace -> cardCertification;
    }
    
    /**
     * 获得当前使用的券是否是提货券
     */
    public function hasGoodsCard() {
        $result = false;
        $cardCertification = $this -> getCardCertificationNamespace();
        if ($cardCertification) {
            foreach ($cardCertification as $cardSn => $value) {
                if ($this -> isGoodsCard($cardSn)) {
                    $result = true;
                    break;
                }
            }
        }
        
        return $result;
    }
    
    /**
     * 根据券号判断是否是提货券
     *
     * @param   string      $cardSn
     */
    public function isGoodsCard($cardSn) 
    {
        return $cardSn[0] == 's';
    }
    
    /**
     * 激活礼品卡
     *
     * @param   string      $cardSn
     */
    public function checkGiftCard($card_sn, $card_pwd,$username='my')
    {
       $card = $this-> _db -> checkGiftCard($card_sn, $card_pwd);
       if (!$card) {
           return "card not exists";
       }
       else if ($card['user_id']) {
           return "card binded";
       }
       else if (strtotime($card['end_date']) < time()){
           return "card expired";
       }
       else{
        if($username == 'my'){
           $_auth = Shop_Models_API_Auth :: getInstance() -> getAuth();          
        }else{
           $memberApi = new Shop_Models_API_Member();
       	   $_auth = array_shift($memberApi ->getMemberByUserName($username));         	   
       	}
      
       	if (!$_auth || count($_auth)<1) {
       		return "user not exists";
       	}
       	
       	
           $data = array('user_name' => $_auth['user_name'], 'user_id' => $_auth['user_id']);
           $res = $this -> _db -> updateGiftCard($data, "card_id = '{$card['card_id']}'");        
          
           if($res)
           {
              return "ok";
	               
           }else{
           	 return  'fail';
           }
          
         
       }
    }
    
    /**
     * 激活优惠券
     *
     * @param   string      $cardSn
     */
    public function checkCouponCard($card_sn, $card_pwd)
    {
        $id = Custom_Model_Encryption::getInstance() -> decrypt(array('sn' => $card_sn, 'pwd' => $card_pwd), 'coupon');
        if (!$id) {
            return "card not exists";
        }
        $coupon = array_shift($this -> _db -> getCouponLog(" and range_from <= {$id} and range_end >= {$id}"));
        if (!$coupon) {
            return "card not exists";
        }
        if (strtotime($coupon['end_date']) < time()) {
           return "card expired";
        }
        if (strtotime($card['start_date']) > time()) {
            return 'noStart';
        }
        $_auth = Shop_Models_API_Auth :: getInstance() -> getAuth();
        
        if ($coupon['is_repeat']) {
            $card = $this-> _db -> checkCouponCard($card_sn, $_auth['user_id']);
        }
        else {
            $card = $this-> _db -> checkCouponCard($card_sn);
        }
        if ($card) {
            if ($card['user_id']) {
                return "card binded";
            }
            else if ($card['status'] == 1 && $card['is_repeat'] == 0) {
               return "card used";
            }
            $this -> _db -> updateCouponCard($card['card_sn'], array('user_name' => $_auth['user_name'], 'user_id' => $_auth['user_id']));
            

            
        }
        else {
            $coupon['card_sn'] = $card_sn;
            $coupon['card_pwd'] = $card_pwd;
            $coupon['user_id'] = $_auth['user_id'];
            $coupon['user_name'] = $_auth['user_name'];
            $coupon['add_time'] = time();
            $coupon['status'] = 0;
            $this -> _db -> useCoupon($coupon);
           
        }
        
        return "ok";
    } 
}