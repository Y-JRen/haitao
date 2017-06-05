<?php /* Smarty version 2.6.19, created on 2015-02-04 14:58:03
         compiled from flow/address.tpl */ ?>

<style type="text/css">
*{padding: 0;margin: 0;}
input,select,option{border: none;}
.pop_flow_adr { width: 721px; height: 350px; background: url(/public/images/pop_revise_bg.png) no-repeat;position: absolute; top: 0; left: 50%; margin: 0 0 0 -360px; z-index: 101; display: none; }
.pop_flow_adr .pop_input01 { width: 176px; height: 20px; border: 2px solid #ccc; position: absolute; top: 44px; left: 178px; background: #fff; }
.pop_flow_adr .pop_input02 { width: 450px; height: 20px; border: 2px solid #ccc; position: absolute; top: 122px; left: 178px; background: #fff; }
.pop_flow_adr .pop_inputCode { width: 100px; height: 20px; border: 2px solid #ccc; position: absolute; top: 160px; left: 178px; background: #fff; }
.pop_flow_adr .pop_input03 { width: 450px; height: 24px; line-height: 24px; position: absolute; top: 196px; left: 178px; font-size: 14px; color: #666; }
.pop_flow_adr .pop_input04 { top: 234px; }
.pop_flow_adr .pop_input05 { width: 46px; height: 20px; border: 2px solid #ccc; position: absolute; top: 270px; left: 178px; background: #fff; }
.pop_flow_adr .pop_input06 { left: 238px; width: 116px; }
.pop_flow_adr .pop_input07 { left: 368px; }
.pop_flow_adr input { width: 100%; height: 100%; }
.pop_flow_adr .pop_select { width: 320px; height: 24px; position: absolute; top: 82px; left: 173px; }
.pop_flow_adr .pop_select select { width: 90px; height: 20px; margin: 0 5px; padding: 0; border: 2px solid #ccc; }
.pop_flow_adr .pop_name { width: 150px; height: 24px; line-height: 24px; font-size: 14px; left: 20px; text-align: right; position: absolute; color: #666; }
.pop_flow_adr .pop_name span { color: #c00; }
.pop_flow_adr .pop_name01 { top: 44px; }
.pop_flow_adr .pop_name02 { top: 82px; }
.pop_flow_adr .pop_name03 { top: 122px; }
.pop_flow_adr .pop_nameCode { top: 160px; }
.pop_flow_adr .pop_name04 { top: 196px; }
.pop_flow_adr .pop_name05 { top: 234px; }
.pop_flow_adr .pop_name06 { top: 270px; }
.pop_flow_adr .pop_baocun_btn { width: 127px; height: 31px; position: absolute; left: 178px; top: 306px; cursor: pointer; }
.pop_close_btn { width: 30px; height: 30px;position: absolute; top: 0; right: 0; cursor: pointer; }
</style>

<div class="pop_flow_adr" id="pop_revise_adr" style="top: 200px; display: block;">
    <form action="/flow/order" id="form1"  method="Post" name="form1">
    	<?php if ($this->_tpl_vars['address_id']): ?>
    	<input type='hidden' name='address_id' value='<?php echo $this->_tpl_vars['address_id']; ?>
' id="ad_id" />
    	<?php endif; ?>
        <div class="pop_close_btn" onclick="closeaddadress()"></div>
        <div class="pop_name pop_name01">
            <span>*</span>
            收货人姓名：
        </div>
        <div class="pop_input01">
            <input type="text" name="consignee" id="consignee" value="<?php echo $this->_tpl_vars['address']['consignee']; ?>
" style="background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABHklEQVQ4EaVTO26DQBD1ohQWaS2lg9JybZ+AK7hNwx2oIoVf4UPQ0Lj1FdKktevIpel8AKNUkDcWMxpgSaIEaTVv3sx7uztiTdu2s/98DywOw3Dued4Who/M2aIx5lZV1aEsy0+qiwHELyi+Ytl0PQ69SxAxkWIA4RMRTdNsKE59juMcuZd6xIAFeZ6fGCdJ8kY4y7KAuTRNGd7jyEBXsdOPE3a0QGPsniOnnYMO67LgSQN9T41F2QGrQRRFCwyzoIF2qyBuKKbcOgPXdVeY9rMWgNsjf9ccYesJhk3f5dYT1HX9gR0LLQR30TnjkUEcx2uIuS4RnI+aj6sJR0AM8AaumPaM/rRehyWhXqbFAA9kh3/8/NvHxAYGAsZ/il8IalkCLBfNVAAAAABJRU5ErkJggg==); background-attachment: scroll; background-position: 100% 50%; background-repeat: no-repeat;"></div>
        <div class="pop_name pop_name02">
            <span>*</span>
            配送区域：
        </div>
        <div class="pop_select">
            <select name="province_id" id="province_id" onchange="getCity(this)"  >
                <option value="">请选择省</option>
                
                <?php $_from = $this->_tpl_vars['province']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['province_id'] => $this->_tpl_vars['province']):
?>
	      	      <option value="<?php echo $this->_tpl_vars['province_id']; ?>
"><?php echo $this->_tpl_vars['province']['area_name']; ?>
</option>
	      	      <?php endforeach; endif; unset($_from); ?>
            </select>
            <select name="city_id" id="city_id" onchange="getCity(this)">
                <option  selected="selected">请选择市</option>
            </select>
            <select name="area_id" id="area_id" onchange="getAreaCode()">
                <option  selected="selected">请选择区</option>
            </select>
        </div>
        <div class="pop_name pop_name03">
            <span>*</span>
            详细地址：
        </div>
        <div class="pop_input02">
            <input type="text" name="address" id="address" size="30" maxlength="100" value="<?php echo $this->_tpl_vars['address']['address']; ?>
">
        </div>
        <div class="pop_name pop_nameCode">
            <span>*</span>
            邮政编码：
        </div>
        <div class="pop_inputCode">
            <input type="text" name="zip" id="postalcode" value="<?php echo $this->_tpl_vars['address']['zip']; ?>
">
        </div>
        <div class="pop_name pop_name04">
            <span>*</span>
            联系电话：
        </div>
        <div class="pop_input03">手机或者固话任填一项</div>
        <div class="pop_name pop_name05">
            <span>*</span>
            手机：
        </div>
        <div class="pop_input01 pop_input04">
            <input type="text"  name="mobile" id="mobile" size="25" maxlength="20" value="<?php echo $this->_tpl_vars['address']['mobile']; ?>
"></div>
        <div class="pop_name pop_name06">固话：</div>
        <div class="pop_input05">
            <input type="text" name="area_code" id="area_code" readonly="readonly" value="<?php echo $this->_tpl_vars['tel']['0']; ?>
"></div>
        <div class="pop_input05 pop_input06">
            <input type="text" name="tel" id="tel" value='<?php echo $this->_tpl_vars['tel']['1']; ?>
'></div>
        <div class="pop_input05 pop_input07">
            <input type="text" name="tel_branch" id="tel_branch" value="<?php echo $this->_tpl_vars['tel']['2']; ?>
"></div>
        <div class="pop_baocun_btn">
            <img src="<?php echo $this->_tpl_vars['imgBaseUr']; ?>
/public/images/pop_baocun_btn.png" onclick="editAddress();"></div>
    </form>
</div>