<script type="text/javascript" src="/scripts/kindeditor/kindeditor-min.js"></script>
<script type="text/javascript" src="/scripts/kindeditor/lang/zh_CN.js"></script>
<form name="myForm" id="myForm" action="{{url param.action=$action}}" method="post" enctype="multipart/form-data" />
<div class="title" style="height:25px;">
	<ul id="show_tab">
	   <li onclick="show_tab(0)" id="show_tab_nav_0" class="bg_nav_current">基本信息</li>
	   <li onclick="show_tab(1)" id="show_tab_nav_1" class="bg_nav_attr">商品扩展</li>
	   <li onclick="show_tab(2)" id="show_tab_nav_2" class="bg_nav">商品描述</li>
	</ul>
</div>
<div class="content">
<div id="show_tab_page_0">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="10%">&nbsp;</td>
      <td>
      </td>
    </tr>
    <tr>
      <td><strong>商品编码</strong> * </td>
      <td>
        {{$goods_sn}}
        <input type="hidden" name="goods_sn" value="{{$goods_sn}}"/>
      </td>
    </tr>
    <tr>
      <td width="10%"><strong>商品名称</strong> * </td>
      <td><input type="text" name="goods_name" size="60" value="{{$data.goods_name}}" msg="请填写商品名称" class="required" /></td>
    </tr>
	
    <tr>
      <td width="10%"><strong>商品别名</strong> </td>
      <td><input type="text" name="short_name" size="60" value="{{$data.short_name}}"  /></td>
    </tr>	
	
	 <tr>
      <td width="10%"><strong>展示分类</strong> * </td>
      <td>
         {{$view_cat.cat_name}}   <input name="view_cat_id" id="view_cat_id" type="hidden" value="{{$view_cat.cat_id}}">
      </td>
    </tr>
	
    <tr>
      <td><strong>扩展分类</strong></td>
      <td><input type="button" onclick="addCats()" value="添加" /><span id="cats">&nbsp;</span><span id="copy" style=" display:none;">{{$viewcatSelect}}</span></td>
    </tr>
<script type="text/javascript">
function addCats(){
	var sel = new Element('SELECT',{name:'other_cat_id[]'});
	var selCat = $('other_cat_id');
	for(i = 0; i < selCat.length; i++){
		var opt = new Element("OPTION",{
			text:selCat.options[i].text,
			value:selCat.options[i].value
		});
		sel.appendChild(opt);
	}
	document.getElementById('cats').appendChild(sel);
}
</script>
    <tr>
      <td><strong>产地</strong> * </td>
      <td><input type="text" name="region" size="20" maxlength="6" value="{{$data.region}}" />
      </td>
    </tr>

    <tr>
      <td><strong>市场价</strong> * </td>
      <td><input type="text" name="market_price" size="8" value="0" msg="请填写市场价" class="required number" /></td>
    </tr>
    <tr>
      <td><strong>本店价</strong> * </td>
      <td><input type="text" name="price" size="8" value="0" msg="请填写本店价" class="required number" /></td>
    </tr>
    <tr>
      <td><strong>内购价</strong> * </td>
      <td><input type="text" name="staff_price" size="8" value="0" msg="请填写内购价" class="required number" /></td>
    </tr>
    <tr>
      <td><strong>限购数量</strong> * </td>
      <td><input type="text" name="limit_number" size="8" value="9" msg="请填写限购数量" class="required number" /></td>
    </tr>
    <tr>
      <td><strong>赠送积分</strong> * </td>
      <td><input type="text" name="point" size="8" value="0" msg="请填写赠送积分" class="required number" /></td>
    </tr>
    <tr>
      <td><strong>活动/简要说明：</strong> * </td>
      <td>  
	   <textarea name="act_notes" style="width: 400px;height: 50px"></textarea>
	  </td>
    </tr>	
    <tr>
      <td><strong>商品ALT说明：</strong> * </td>
      <td>  
	  <input type="text" name="goods_alt" id="goods_alt" size="60" value="{{$data.goods_alt}}"  />
	  </td>
    </tr>	
    <tr>
      <td><strong>商品关键字：</strong>  </td>
      <td>  
	  <input type="text" name="goods_keywords" id="goods_keywords" size="60"  />&nbsp;&nbsp;（关键字以<font color="red"><strong>"|"</strong></font>分割）
	  </td>
    </tr>
</tbody>
</table>
</div>

<div id="show_tab_page_1" style="display:none;">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td><strong>meta标题</strong></td>
      <td><input type="text" name="meta_title" size="50" value="{{$data.meta_title}}"></td>
    </tr>
    <tr>
      <td><strong>meta关键字</strong></td>
      <td><input type="text" name="meta_keywords" size="50" value="{{$data.meta_keywords}}"></td>
    </tr>
	<tr>
      <td><strong>meta描述</strong></td>
      <td><textarea name="meta_description" rows="3" cols="39" id="meta_description" style="width:330px; height:45px;">{{$data.meta_description}}</textarea></td>
    </tr>	

</tbody>
</table>
</div>

<div id="show_tab_page_2" style="display:none;">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="10%"><strong>商品特点</strong></td>
      <td>
	    <textarea name="brief" rows="4" cols="39" id="brief" style="width:350px; height:45px;">{{$data.brief}}</textarea>
	  </td>
    </tr>
	<tr>
      <td><strong>商品说明</strong></td>
      <td>
		<textarea name="description" id="description" rows="20" style="width:680px; height:260px;">{{$data.description}}</textarea>
		<script type="text/javascript">
			KindEditor.ready(function(K) {
				K.create('textarea[name="description"]', {
							allowFileManager : true
						});
			});
		</script>
	  </td>
    </tr>
</tbody>
</table>
</div>
</div>
<div class="submit"><input type="submit" name="dosubmit1" id="dosubmit1" value="确定"/> <input type="reset" name="reset" value="重置" /></div>
</form>
