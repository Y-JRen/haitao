<script type="text/javascript" src="/scripts/kindeditor/kindeditor-min.js"></script>
<script type="text/javascript" src="/scripts/kindeditor/lang/zh_CN.js"></script>
<form name="myForm" id="myForm" action="{{url param.action=$action}}" method="post">
<div class="title">{{if $action eq 'edit'}}编辑供货商{{else}}添加供货商{{/if}}</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr> 
      <td width="15%"><strong>供货商名称</strong> * </td>
      <td>
     {{if $action eq 'edit'}} {{$data.supplier_name}} {{else}} 
      <input type="text" name="supplier_name" size="30" value="{{$data.supplier_name}}" msg="请填写供货商名称" class="required" />  {{/if}}
      </td>
	   <td width="15%"><strong>公司名称</strong> * </td>
      <td><input type="text" name="company" size="30" value="{{$data.company}}" msg="请填写公司名称" class="required" /></td>
    </tr>
    <tr> 
      <td width="15%"><strong>公司法人代表</strong></td>
      <td><input type="text" name="corporate" size="30" value="{{$data.corporate}}"  /></td>
      <td width="15%"><strong>公司注册号</strong></td>
      <td><input type="text" name="registration_num" size="30" value="{{$data.registration_num}}"  /></td>
    </tr>
    <tr> 
      <td width="15%"><strong>地址</strong></td>
      <td><input type="text" name="addr" size="30" value="{{$data.addr}}"  /></td>
      <td width="15%"><strong>公司类型</strong></td>
      <td><input type="text" name="business_type" size="30" value="{{$data.business_type}}"  /></td>
    </tr>
   
    <tr> 
      <td width="15%"><strong>联系人</strong></td>
      <td><input type="text" name="contact" size="30" value="{{$data.contact}}"  /></td>
	  <td width="15%"><strong>电话</strong></td>
      <td><input type="text" name="tel" size="30" value="{{$data.tel}}"  /></td>
    </tr>
    <tr> 
      <td width="15%"><strong>手机:</strong></td>
      <td><input type="text" name="mobile" size="30" value="{{$data.mobile}}" /></td>
	  <td width="15%"><strong>EMAIL:</strong></td>
      <td><input type="text" name="email" size="30" value="{{$data.email}}" /></td>
    </tr>
    <tr>
      <td width="15%"><strong>备注</strong></td>
      <td colspan="3">
	  
	  <textarea name="supplier_desc" id="supplier_desc" rows="20" style="width:680px; height:260px;">{{$data.supplier_desc}}</textarea>
		<script type="text/javascript">
			KindEditor.ready(function(K) {
				K.create('textarea[name="supplier_desc"]', {
							allowFileManager : true
						});
			});
		</script>
	  </td>
    </tr>
    <tr> 
      <td width="15%"><strong>有效期开始时间</strong></td>
      <td><input type="text" name="start_time" size="30" value="{{$data.start_time|date_format:"%Y-%m-%d"}}"  />
        如:2012-04-10</td>
	  <td width="15%"><strong>结束时间</strong>  </td>
      <td><input type="text" name="end_time" size="30" value="{{$data.end_time|date_format:"%Y-%m-%d"}}"  />
      如:2018-10-25</td>
    </tr>
	 <tr> 
      <td width="15%"><strong>银行</strong></td>
      <td><input type="text" name="bank_name" size="30" value="{{$data.bank_name}}"/></td>
	  <td width="15%"><strong>应收银行帐户</strong>  </td>
      <td><input type="text" name="bank_account" size="30" value="{{$data.bank_account}}"  /></td>
    </tr>
	<tr> 
	  <td width="15%"><strong>银行帐号</strong>  </td>
      <td colspan="3"><input type="text" name="bank_sn" size="55" value="{{$data.bank_sn}}"  /></td>
    </tr>

    <tr> 
      <td><strong>是否启用</strong></td>
      <td colspan="3">
	   <input type="radio" name="status" value="0" {{if $data.status==0 && $action eq 'edit'}}checked{{/if}}/> 是
	   <input type="radio" name="status" value="1" {{if $data.status==1 or $action eq 'add'}}checked{{/if}}/> 否
	  </td>
    </tr>
</tbody>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>