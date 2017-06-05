<form method="post" name="theForm" id="theForm" action="/admin/logistic/template-logistic" enctype="multipart/form-data">
<input type="hidden" name="logistic_code" value="{{$logistic.logistic_code}}" />
<input type="hidden" name="config" id="config" />
<input type="hidden" name="global_value" id="global_value" />
<input type="hidden" name="global_image" id="global_image" value="{{$template.image}}" />
<div class="title">物流打印单模板</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tr>
  <td>
    快递公司：<b>{{$logistic.name}}</b>&nbsp;&nbsp;&nbsp;&nbsp;
    模板类型：
    <input type="radio" name="type" value="1" {{if $type eq 1}}checked{{/if}} onclick="window.location='/admin/logistic/template-logistic/logistic_code/{{$logistic.logistic_code}}/type/1'">货到付款
    <input type="radio" name="type" value="2" {{if $type eq 2}}checked{{/if}} onclick="window.location='/admin/logistic/template-logistic/logistic_code/{{$logistic.logistic_code}}/type/2'">款到发货
  </td>
</tr>
<tr>
<td>
<select name="lable" id="lable" class="select_box" onchange="call_flash('lable_add', this);">
  <option value="" selected="selected">--选择插入标签--</option>
  <option value="brand">国药电商</option>
  <option value="company">国药（上海）电子商务有限公司</option>
  <option value="sender">寄件人</option>
  <option value="sender_addr">寄件人地址</option>
  <option value="sender_city">寄件城市</option>
  <option value="sender_tel">寄件人电话</option>
  <option value="consignee">收货人</option>
  <option value="consignee_tel_mobile">收货人手机/电话</option>
  <option value="consignee_tel">收货人电话</option>
  <option value="consignee_mobile">收货人手机</option>
  <option value="consignee_validate_sn">收货人验证码</option>
  <option value="country">收货地址-国家</option>
  <option value="povince">收货地址-省份</option>
  <option value="city">收货地址-城市</option>
  <option value="area">收货地址-地区</option>
  <option value="address">收货地址</option>
  <option value="addr">收货地址(包含省城区)</option>
  <option value="contact">联络人</option>
  <option value="contact_tel">联络人电话</option>
  <option value="bill_no">订单号</option>
  <option value="print_remark">备注</option>
  <option value="time">打印日期</option>
  <option value="goods_name">货物名称</option>
  <option value="goods_number">货物数量</option>
  <option value="amount">金额</option>
  <option value="amount_cn">金额(大写)</option>
  <option value="card_sn">提货卡</option>
  <option value="sign_received">验货签收</option>
  <option value="sign_code">400电话提醒</option>
  <option value="gou1">打钩1</option>
  <option value="gou2">打钩2</option>
  <option value="payment">月结账号</option>
  <option value="pay_sign">刷卡提示</option>
</select>
<input type="button" name="del" id="del" value="删除标签" onclick="call_flash('lable_del', this);">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="file" name="image" id="image" >
<input type="submit" name="upload" id="upload" value="上传打印单" onclick="return bg_upload();" >
<input type="button" name="del_bg" id="del_bg" value="隐藏打印单" onclick="call_flash('bg_delete', this);">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="打印预览" onclick="preview();">
<input type="submit" value="保存设置" onclick="return save();">
</td>
</tr>
</table>

<div>
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="1024" height="600" id="test">
      <param name="movie" value="/haitaoadmin/images/print/print.swf">
      <param name="quality" value="high">
      <param name="menu" value="false">
      <param name="wmode" value="transparent">
      <param name="FlashVars" value="bcastr_config_bg={{$template.image}}&swf_config_lable={{$template.config}}">
      <param name="allowScriptAccess" value="sameDomain"/>
      <embed src="/haitaoadmin/images/print/print.swf" wmode="transparent" FlashVars="bcastr_config_bg={{$template.image}}&swf_config_lable={{$template.config}}" menu="false" quality="high" width="1024" height="600" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" allowScriptAccess="sameDomain" name="test" swLiveConnect="true"/>
</object>
</div>

</div>
</form>

<script language="JavaScript">

var process_request = "正在处理您的请求...";
var todolist_caption = "记事本";
var todolist_autosave = "自动保存";
var todolist_save = "保存";
var todolist_clear = "清除";
var todolist_confirm_save = "是否将更改保存到记事本？";
var todolist_confirm_clear = "是否清空内容？";
var lang_removeconfirm = "您确定要卸载该配送方式吗？";
var shipping_area = "设置区域";
var upload_falid = "错误：文件类型不正确。请上传“%s”类型的文件！";
var upload_del_falid = "错误：删除失败！";
var upload_del_confirm = "提示：您确认删除打印单图片吗？";
var no_select_upload = "错误：您还没有选择打印单图片。请使用“浏览...”按钮选择！";
var no_select_lable = "操作终止！您未选择任何标签。";
var no_add_repeat_lable = "操作失败！不允许添加重复标签。";
var no_select_lable_del = "删除失败！您没有选中任何标签。";
var recovery_default_suer = "您确认恢复默认吗？恢复默认后将显示安装时的内容。";

var Browser = new Object();
Browser.isMozilla = (typeof document.implementation != 'undefined') && (typeof document.implementation.createDocument != 'undefined') && (typeof HTMLDocument != 'undefined');
Browser.isIE = window.ActiveXObject ? true : false;
Browser.isFirefox = (navigator.userAgent.toLowerCase().indexOf("firefox") != - 1);
Browser.isSafari = (navigator.userAgent.toLowerCase().indexOf("safari") != - 1);
Browser.isOpera = (navigator.userAgent.toLowerCase().indexOf("opera") != - 1);

function this_obj(flash_name)
{
  var _obj;

  if (Browser.isIE)
  {
      _obj = window[flash_name];
  }
  else
  {
      _obj = document[flash_name];
  }

  if (typeof(_obj) == "undefined")
  {
    _obj = document[flash_name];
  }
  
  return _obj;
}

function call_flash(type, currt_obj)
{
  //获取flash对象
  var obj = this_obj("test");
  
  //执行操作
  switch (type)
  {
    case 'bg_delete': //删除打印单背景图片

      var result_del = obj.bg_delete();
      
    break;

    case 'bg_add': //添加打印单背景图片

      var result_add = obj.bg_add(currt_obj);

    break;

    case 'lable_add': //插入标签

      if (typeof(currt_obj) != 'object')
      {
        return false;
      }

      if (currt_obj.value == '')
      {
        alert(no_select_lable);

        return false;
      }

      var result = obj.lable_add('t_' + currt_obj.value, currt_obj.options[currt_obj.selectedIndex].text, 150, 50, 20, 100, 'b_' + currt_obj.value);
      if (!result)
      {
        alert(no_add_repeat_lable);

        return false;
      }

    break;

    case 'lable_del': //删除标签

      var result_del = obj.lable_del();

      if (result_del)
      {
        //alert("删除成功！");
      }
      else
      {
        alert(no_select_lable_del);
      }

    break;

    case 'lable_Location_info': //获取标签位置信息

      var result_info = obj.lable_Location_info();

      return result_info;

    break;
  }

  return true;

}

function save()
{
    var value = call_flash('lable_Location_info', '');
    if ( value == '' ) {
        alert('打印单上没有标签！');
        return false;
    }
    
    document.getElementById('config').value = value;
    return true;
}

function bg_upload()
{
    if (document.getElementById('image').value == '') {
        return false;
    }
    
    if (confirm('确认要上传新打印单吗？')) {
        return true;
    }
    
    return false;
}

function preview()
{
    value = call_flash('lable_Location_info', '');
    if ( value == '' ) {
        alert('打印单上没有标签！');
        return false;
    }
    
    document.getElementById('global_value').value = value;
    
    window.open('/admin/logistic/template-logistic-preview', 'print_preview' );
}


</script>
