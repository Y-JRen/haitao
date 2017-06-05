
<link rel="stylesheet" type="text/css" href="/styles/admin/checktree.css" />
	 <style type="text/css" rel="stylesheet">
    form {
        margin: 0;
    }
    .editor {
        margin-top: 5px;
        margin-bottom: 5px;
    }
    ul,li{
		list-style:none outside none;
		margin:0xp;
		padding:0px;
	}
	.tree li{
		text-align: left;
		display: block;
		width:100%;
		line-height:20px;
	}
	.cate1 .tree .checkbox{
		display: none;
	}
	.cate1,.cate2,.cate3,.cate4{
    	background: #FFFFFF;
    	border-left: 1px #8A9295 solid;
    	border-right: 1px #8A9295 solid;
    	border-bottom: 1px #8A9295 solid;
    	display:none;
    	position: absolute;
    	top: 21px;
    	left: 0px;
    	z-index:999;
    }
   .cate1 .content,.cate2 .content,.cate3 .content,.cate4 .content{
   		width:216px;
    	height: 150px;
    	overflow-x:auto;
    	overflow-y:auto;
   }
  </style>
<form name="myForm" id="myForm" action="{{url param.action=region-edit}}" enctype="multipart/form-data" method="post">
<div class="title">{{if $action eq 'edit'}}编辑归属地{{else}}添加归属地{{/if}}</div>

<div class="title" style="height:25px;">
	<ul id="show_tab">
	   <li onclick="show_tab(0)" id="show_tab_nav_0" class="bg_nav_current">基本信息</li>
	</ul>
</div>

<div class="content">

	<div id="show_tab_page_0"> 
		<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
		<tbody>
		
			<tr>
			  <td width="10%"><strong>归属地名称</strong> * </td>
			  <td><input type="text" name="region_name" size="30" value="{{$data.region_name|stripslashes}}" msg="请填写归属地名称" class="required" /></td>
			</tr>
			
			
			<tr>
			  <td width="10%"><strong>归属地图片</strong>  </td>
			  <td>  <input type="file"  name="region_imgurl"  /> {{if $data.region_imgurl}} <img  src="{{$imgBaseUrl}}/{{$data.region_imgurl}}"   width="30px" hegiht='20px'/>{{/if}}  </td>
			</tr>
			
			
			
		</tbody>
		</table>	
	 </div>
	
	

</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>
