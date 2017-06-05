<?php
class Admin_LogisticController extends Zend_Controller_Action
{

    public function init()
    {
        $this -> _api = new Admin_Models_API_Logistic();
        $this -> _auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
    }
    /**
     * 锁定订单
     *
     * @return void
     */
    public function lockAction()
    {
    	$this -> _helper -> viewRenderer -> setNoRender();
    	$val = (int)$this -> _request -> getParam('lock', 0);
    	$this -> _api -> lock($this -> _request -> getPost(), $val);
    }
    /**
     * 地区列表
     *
     * @return void
     */
    public function listAreaAction()
    {
        $countryID = 1;
        $provinceID = intval($this -> _request -> getParam('province_id', null));
        $cityID = intval($this -> _request -> getParam('city_id', null));
        $areaID = intval($this -> _request -> getParam('area_id', null));
        $code = $this -> _request -> getParam('code', null);
        $zip = $this -> _request -> getParam('zip', null);
        $data = $this -> _api -> getAreaListWithPage(array('province_id' => $provinceID,
                                                           'city_id' => $cityID,
                                                           'area_id' => $areaID,
                                                           'code' => $code,
                                                           'zip' => $zip), intval($this -> _request -> getParam('page', 1)));
        $pageNav = new Custom_Model_PageNav($data['total']);
        $this -> view -> pageNav = $pageNav -> getNavigation();
        $this -> view -> areaList = $data['data'];
        $this -> view -> province = $this -> _api -> getAreaListByID($countryID);
        if ($provinceID) {
            $this -> view -> city = $this -> _api -> getAreaListByID($provinceID);
        }
        if ($cityID) {
            $this -> view -> area = $this -> _api -> getAreaListByID($cityID);
        }
        $this -> view -> provinceID = $provinceID;
        $this -> view -> cityID = $cityID;
        $this -> view -> areaID = $areaID;
        $this -> view -> logisticCode = $logisticCode;
        $this -> view -> zip = $zip;
    }
    /**
     * 指定ID的地区列表JSON数据
     *
     * @return void
     */
    public function listAreaByJsonAction()
    {
        echo $this -> _api -> getAreaListJsonData(intval($this -> _request -> getParam('area_id', null)));
        exit;
    }
    /**
     * 地区列表管理
     *
     * @return void
     */
    public function listManageAreaAction()
    {
        $this -> view -> areaID = $areaID = intval($this -> _request -> getParam('area_id', null));
        $this -> view -> place = $this -> _api -> getWholeAreaName($areaID);
        $this -> view -> area = $this -> _api -> getAreaListByID($areaID);
    }
    /**
     * 导入邮编 和 区号
     *
     * @return void
     */
    public function importLogisticAreaAction()
    {
        $return = $this -> _api -> importLogisticArea($_FILES['logistic']);
        $url = $this -> getFrontController() -> getBaseUrl() . '/admin/logistic/list-area/';
        Custom_Model_Message :: showMessage($return['tip'], $url, 1250);
    }
    /**
     * 添加地区
     *
     * @return void
     */
    public function addAreaAction()
    {
        $parentID = intval($this -> _request -> getParam('parent_id', null));
        $data = array('parent_id' => $parentID,
                      'area_name' => $this -> _request -> getParam('area_name', ''),
                      'code' => $this -> _request -> getParam('code', ''),
                      'zip' => $this -> _request -> getParam('zip', ''));
        $return = $this -> _api -> addArea($data);
        $url = $this -> getFrontController() -> getBaseUrl() . '/admin/logistic/list-manage-area/area_id/' . $parentID;
        Custom_Model_Message :: showMessage($return['tip'], $url, 1250);
    }
    /**
     * 删除地区
     *
     * @return void
     */
    public function delAreaAction()
    {
        $areaID = intval($this -> _request -> getParam('area_id', null));
        $area = $this -> _api -> getAreaByID($areaID);
        $return = $this -> _api -> delArea($areaID);
        $url = $this -> getFrontController() -> getBaseUrl() . '/admin/logistic/list-manage-area/area_id/' . $area['parent_id'];
        Custom_Model_Message :: showMessage($return['tip'], $url, 1250);
    }
    /**
     * 编辑地区
     *
     * @return void
     */
    public function editAreaAction()
    {
        $areaID = intval($this -> _request -> getParam('area_id', null));
        $area = $this -> _api -> getAreaByID($areaID);
        $data = array('area_name' => $this -> _request -> getParam('area_name', ''),
                      'price' => $this -> _request -> getParam('price', 0),
                      'code' => $this -> _request -> getParam('code', ''),
                      'zip' => $this -> _request -> getParam('zip', ''));
        $return = $this -> _api -> editArea($areaID, $data);
        $url = $this -> getFrontController() -> getBaseUrl() . '/admin/logistic/list-manage-area/area_id/' . $area['parent_id'];
        Custom_Model_Message :: showMessage($return['tip'], $url, 1250);
    }
    /**
     * 物流公司列表
     *
     * @return void
     */
    public function listLogisticAction()
    {
        $this -> view -> logisticPlugin = $this -> _api -> getLogisticPluginList();
        $this -> view -> logistic = $this -> _api -> getLogisticList();
    }
    /**
     * 导出指定ID物流公司的操作区域/配送价格表
     *
     * @return void
     */
    public function exportLogisticAction()
    {
        Zend_Controller_Front::getInstance() -> unRegisterPlugin(Custom_Controller_Plugin_Layout);
        $this -> _helper -> viewRenderer -> setNoRender();
        $this -> getResponse() -> setHeader('Content-type', 'application/vnd.ms-excel')
                               -> setHeader('Content-Disposition', 'filename=logistic.xls');
        $logisticCode = $this -> _request -> getParam('logistic_code', null);
        $this -> _api -> exportLogisticByID($logisticCode);
    }
    /**
     * 导入指定ID物流公司的操作区域/配送价格表
     *
     * @return void
     */
    public function importLogisticAction()
    {
        $logisticCode = $this -> _request -> getParam('logistic_code', null);
        if (!$this -> _request -> getParam('submit', null)) {
            $this -> view -> logisticCode = $logisticCode;
        } else {
            $return = $this -> _api -> importLogistic($logisticCode, $_FILES['logistic']);
            $url = $this -> getFrontController() -> getBaseUrl() . '/admin/logistic/list-logistic/';
            Custom_Model_Message :: showMessage($return['tip'], $url, 1250);
        }
    }
    /**
     * 添加物流公司
     *
     * @return void
     */
    public function addLogisticAction()
    {
        $data = array('name' => $this -> _request -> getParam('name', ''),
                      'logistic_code' => $this -> _request -> getParam('logistic_code', ''),
                      'cod_rate' => $this -> _request -> getParam('cod_rate', ''),
                      'url' => $this -> _request -> getParam('url', ''),
                      'brief' => $this -> _request -> getParam('brief', ''),
                      'sort' => $this -> _request -> getParam('sort', ''),
                      'open' => $this -> _request -> getParam('open', ''));
        $return = $this -> _api -> addLogistic($data);
        $url = $this -> getFrontController() -> getBaseUrl() . '/admin/logistic/list-logistic/';
        Custom_Model_Message :: showMessage($return['tip'], $url , 1250);
        $this->_redirect($url);
    }
    /**
     * 删除物流公司
     *
     * @return void
     */
    public function delLogisticAction()
    {
        $this -> _helper -> viewRenderer -> setNoRender();
        $logisticCode = $this -> _request -> getParam('logistic_code', null);
        $return = $this -> _api -> delLogistic($logisticCode);
        $url = $this -> getFrontController() -> getBaseUrl() . '/admin/logistic/list-logistic/';
        $this->_redirect($url);
    }
    /**
     * 编辑物流公司
     *
     * @return void
     */
    public function editLogisticAction()
    {
      if ($this -> _request -> isPost()) {
            $dates = $this -> _request -> getPost();
            $logisticCode = $dates['logistic_code'];
            $data = array('name' => $dates['name'],
                'cod_rate' => $dates['cod_rate'],
                'cod_min' => $dates['cod_min'],
                'fee_service' => $dates['fee_service'],
                'url' => $dates['url'],
                'brief' => $dates['brief'],
                'sort' => $dates['sort'],
                'open' => $dates['open']
               );
            $return = $this -> _api -> editLogistic($logisticCode, $data);
            $url = $this -> getFrontController() -> getBaseUrl() . '/admin/logistic/list-logistic/';
            Custom_Model_Message :: showMessage($return['tip'], $url, 1250);
      }else{
        $logisticCode = $this -> _request -> getParam('logistic_code', null);
        $this -> view -> logisticPlugin = $this -> _api -> getLogisticPluginList();
        $this -> view -> logistic = $this -> _api -> getLogisticByID($logisticCode);
           
      }

    }
    /**
     * 配送价格列表
     *
     * @return void
     */
    public function listLogisticAreaPriceAction()
    {
        $countryID = 1;
        $provinceID = intval($this -> _request -> getParam('province_id', null));
        $cityID = intval($this -> _request -> getParam('city_id', null));
        $areaID = intval($this -> _request -> getParam('area_id', null));
        $logisticCode = $this -> _request -> getParam('logistic_code', null);
        $page = intval($this -> _request -> getParam('page', 1));

        $this -> view -> province = $this -> _api -> getAreaListByID($countryID);
        if ($provinceID) {
            $this -> view -> city = $this -> _api -> getAreaListByID($provinceID);
        }
        if ($cityID) {
            $this -> view -> area = $this -> _api -> getAreaListByID($cityID);
        }
        $this -> view -> logisticCode = $logisticCode;
        $this -> view -> provinceID = $provinceID;
        $this -> view -> cityID = $cityID;
        $this -> view -> areaID = $areaID;
        $this -> view -> logistic = $this -> _api -> getLogisticList();
        if ($logisticCode) {
            $where = array('logistic_code' => $logisticCode, 
                           'province_id' => $provinceID, 
                           'city_id' => $cityID, 
                           'area_id' => $areaID);
            $data = $this -> _api -> getLogisticAreaPriceListWithPage($where, $page);
            $this -> view -> title = $data['title'];
            $this -> view -> logisticArea = $data['logistic_area'];
            $pageNav = new Custom_Model_PageNav($data['total']);
            $this -> view -> pageNav = $pageNav -> getNavigation();
        }
    }
    /**
     * 编辑物流公司配送价格列表
     *
     * @return void
     */
    public function editLogisticAreaPriceAction()
    {
        $logisticCode = $this -> _request -> getParam('logistic_code', null);
        $provinceID = intval($this -> _request -> getParam('province_id', null));
        $cityID = intval($this -> _request -> getParam('city_id', null));
        $areaID = intval($this -> _request -> getParam('area_id', null));
        $logisticAreaID = intval($this -> _request -> getParam('logistic_area_id', null));
        if (!$this -> _request -> getParam('submit', null)) {
            $this -> view -> logisticArea = $logisticArea = $this -> _api -> getLogisticAreaByID($logisticAreaID);
            $this -> view -> logistic = $this -> _api -> getLogisticByID($logisticArea['logistic_code']);
            $where = array('logistic_code' => $logisticArea['logistic_code'], 'area_id' => $logisticArea['area_id']);
            $this -> view -> logisticAreaPrice = $this -> _api -> getLogisticAreaPriceList($where);
            $this -> view -> logisticCode = $logisticCode;
            $this -> view -> provinceID = $provinceID;
            $this -> view -> cityID = $cityID;
            $this -> view -> areaID = $areaID;
        } else {
            $price = $this -> _request -> getParam('price', null);
            if (is_array($price) && count($price)) {
                foreach ($price as $logisticAreaPriceID => $price) {
                    $return = $this -> _api -> editLogisticAreaPrice($logisticAreaPriceID, array('price' => $price));
                }
            }
            $url = $this -> getFrontController() -> getBaseUrl() . '/admin/logistic/list-logistic-area-price/';
            $url .= "logistic_code/{$logisticCode}/province_id/{$provinceID}/city_id/{$cityID}/area_id/{$areaID}/";
            Custom_Model_Message :: showMessage($return['tip'], $url, 1250);
        }
    }
    /**
     * 配送策略列表
     *
     * @return void
     */
    public function listAreaStrategyAction()
    {
        $countryID = 1;
        $provinceID = intval($this -> _request -> getParam('province_id', null));
        $cityID = intval($this -> _request -> getParam('city_id', null));
        $areaID = intval($this -> _request -> getParam('area_id', null));
        $page = intval($this -> _request -> getParam('page', 1));

        $this -> view -> province = $this -> _api -> getAreaListByID($countryID);
        if ($provinceID) {
            $this -> view -> city = $this -> _api -> getAreaListByID($provinceID);
        }
        if ($cityID) {
            $this -> view -> area = $this -> _api -> getAreaListByID($cityID);
        }
        $this -> view -> provinceID = $provinceID;
        $this -> view -> cityID = $cityID;
        $this -> view -> areaID = $areaID;
        $where = array('province_id' => $provinceID, 'city_id' => $cityID, 'area_id' => $areaID);
        $data = $this -> _api -> getAreaStrategyListWithPage($where, $page);
        $this -> view -> strategy = $data['data'];
        $this -> view -> logisticPlugin = $this -> _api -> getLogisticPluginList();
        $pageNav = new Custom_Model_PageNav($data['total']);
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    /**
     * 管理指定ID的区 列表 的 配送策略
     *
     * @return void
     */
    public function listManageAreaStrategyAction()
    {
        $this -> view -> areaID = $areaID = intval($this -> _request -> getParam('area_id', null));
        $this -> view -> place = $this -> _api -> getWholeAreaName($areaID);
        $this -> view -> area = $this -> _api -> getAreaListByID($areaID);
    }
    /**
     * 设置配送策略
     *
     * @return void
     */
    public function setAreaStrategyAction()
    {
        if (!$this -> _request -> getParam('submit', null)) {
            $areaID = intval($this -> _request -> getParam('area_id', null));
            $this -> view -> place = $this -> _api -> getWholeAreaName($areaID);
            $this -> view -> logistic = $this -> _api -> getLogisticList();
            
        } else {
            $areaID = intval($this -> _request -> getParam('area_id', null));
            $openLogisticCode = $this -> _request -> getParam('open_logistic_code', null);
            $strategy = $this -> _request -> getParam('strategy', null);
            if ($strategy[$openLogisticCode]) {
                $strategy[$openLogisticCode]['use'] = 1;
            }
            $return = $this -> _api -> setAreaStrategy($areaID, $strategy);
            $url = $this -> getFrontController() -> getBaseUrl() . "/admin/logistic/list-manage-area-strategy/area_id/{$areaID}/";
            Custom_Model_Message :: showMessage($return['tip'], $url, 1250);
        }
    }
    /**
     * ajax修改配送策略列表 的 优先级  	指定  	暂停
     *
     * @return void
     */
    public function updateAreaStrategyByAjaxAction()
    {
        $areaID = intval($this -> _request -> getParam('area_id', null));
        $code = $this -> _request -> getParam('code', null);
        $name = $this -> _request -> getParam('name', null);
        $value = $this -> _request -> getParam('value', null);
        $this -> _api -> updateAreaStrategyByAjax($areaID, $code, $name, $value);
        exit;
    }
    /**
     * 操作区域列表
     *
     * @return void
     */
    public function listLogisticAreaAction()
    {
        $countryID = 1;
        $provinceID = intval($this -> _request -> getParam('province_id', null));
        $cityID = intval($this -> _request -> getParam('city_id', null));
        $areaID = intval($this -> _request -> getParam('area_id', null));
        $delivery = $this -> _request -> getParam('delivery', null);
        $pickup = $this -> _request -> getParam('pickup', null);
        $cod = $this -> _request -> getParam('cod', null);
        $open = $this -> _request -> getParam('open', null);
        $logistic_code = $this -> _request -> getParam('logistic_code', null);
        $page = intval($this -> _request -> getParam('page', 1));

        $this -> view -> province = $this -> _api -> getAreaListByID($countryID);
        if ($provinceID) {
            $this -> view -> city = $this -> _api -> getAreaListByID($provinceID);
        }
        if ($cityID) {
            $this -> view -> area = $this -> _api -> getAreaListByID($cityID);
        }
        $this -> view -> provinceID = $provinceID;
        $this -> view -> cityID = $cityID;
        $this -> view -> areaID = $areaID;
        $this -> view -> delivery = $delivery;
        $this -> view -> pickup = $pickup;
        $this -> view -> cod = $cod;
        $this -> view -> open = $open;
        $where = array('province_id' => $provinceID, 
                       'city_id' => $cityID, 
                       'delivery' => $delivery, 
                       'pickup' => $pickup, 
                       'cod' => $cod, 
                       'open' => $open, 
                       'logistic_code' => $logistic_code, 
                       'area_id' => $areaID);
        $data = $this -> _api -> getLogisticAreaListWithPage($where, $page);
        $this -> view -> logisticPlugin = $this -> _api -> getLogisticPluginList();
        $this -> view -> data = $data['data'];
        $pageNav = new Custom_Model_PageNav($data['total']);
        $this -> view -> pageNav = $pageNav -> getNavigation();
    }
    /**
     * 编辑操作区域
     *
     * @return void
     */
    public function editLogisticAreaAction()
    {
       $logisticAreaID = $this -> _request -> getParam('logistic_area_id', null);
       if ($this -> _request -> isPost()) {
            $data = array('delivery' => intval($this -> _request -> getParam('delivery', '')),
                          'pickup' => intval($this -> _request -> getParam('pickup', '')),
                          'cod' => intval($this -> _request -> getParam('cod', '')),
                          'open' => intval($this -> _request -> getParam('open', '')),
                          'delivery_keyword' => $this -> _request -> getParam('delivery_keyword', ''),
                          'non_delivery_keyword' => $this -> _request -> getParam('non_delivery_keyword', ''));
            $return = $this -> _api -> editLogisticArea($logisticAreaID, $data);
            $url = $this -> getFrontController() -> getBaseUrl() . '/admin/logistic/list-logistic-area/';
            Custom_Model_Message :: showMessage($return['tip'], $url, 1250);
       }else{
            $this -> view -> logisticPlugin = $this -> _api -> getLogisticPluginList();
            $this -> view -> data = $this -> _api -> getLogisticAreaByID($logisticAreaID);
            $this -> view -> admin = $this->_auth['admin_name'];
 
       }
    }
    
    /**
     * 模板管理
     *
     * @return void
     */
    public function templateLogisticAction()
    {
        $logisticCode = $this -> _request -> getParam('logistic_code', null);
        $type = $this -> _request -> getParam('type', null);
        if ( !$type )   $type = 1;
        $logistic = $this -> _api -> getLogisticByID($logisticCode);
        if ( !$logistic ) {
            Custom_Model_Message :: showMessage('error', '/admin/logistic/list-logistic');
            exit;
        }
        
        if ($this -> _request -> isPost()) {
            if( is_file($_FILES['image']['tmp_name']) ) {
                $upload_path = 'images/admin/print';
                $upload = new Custom_Model_Upload( 'image', $upload_path );
    		    $upload -> up( false );
    		    if( $upload -> error() ) {
    		        Custom_Model_Message :: showMessage($upload -> error());
    		        exit;
    		    }
                
    		    $setData = array('logistic_code' => $logistic['logistic_code'],
    		                     'type'          => $type,
    		                     'image'         => $upload_path.'/'.$upload -> uploadedfiles[0]['filepath'],
    		                    );
            }
            else {
                $postData = $this -> _request -> getPost();
                $setData = array('logistic_code' => $logistic['logistic_code'],
    		                     'type'          => $type,
    		                     'config'        => $postData['config'],
    		                    );
            }
            
            $this -> _api -> setLogisticTemplate($setData);
        }
        
        $template = $this -> _api -> getLogisticTemplate($logisticCode, $type);
        $template['image'] && $template['image'] = '/'.$template['image'];
        
        $this -> view -> logistic = $logistic;
        $this -> view -> template = $template;
        $this -> view -> type = $type;
    }
    
    /**
     * 模板预览
     *
     * @return void
     */
    public function templateLogisticPreviewAction()
    {
        
        
    }
}
