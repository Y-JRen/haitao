<?php /* Smarty version 2.6.19, created on 2014-08-25 16:52:26
         compiled from brand/region-edit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'stripslashes', 'brand/region-edit.tpl', 60, false),)), $this); ?>

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
<form name="myForm" id="myForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>"region-edit",)));?>" enctype="multipart/form-data" method="post">
<div class="title"><?php if ($this->_tpl_vars['action'] == 'edit'): ?>编辑归属地<?php else: ?>添加归属地<?php endif; ?></div>

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
			  <td><input type="text" name="region_name" size="30" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['region_name'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
" msg="请填写归属地名称" class="required" /></td>
			</tr>
			
			
			<tr>
			  <td width="10%"><strong>归属地图片</strong>  </td>
			  <td>  <input type="file"  name="region_imgurl"  /> <?php if ($this->_tpl_vars['data']['region_imgurl']): ?> <img  src="<?php echo $this->_tpl_vars['imgBaseUrl']; ?>
/<?php echo $this->_tpl_vars['data']['region_imgurl']; ?>
"   width="30px" hegiht='20px'/><?php endif; ?>  </td>
			</tr>
			
			
			
		</tbody>
		</table>	
	 </div>
	
	

</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>