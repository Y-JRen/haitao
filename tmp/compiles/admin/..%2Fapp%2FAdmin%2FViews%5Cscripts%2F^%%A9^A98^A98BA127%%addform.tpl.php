<?php /* Smarty version 2.6.19, created on 2014-08-22 15:16:51
         compiled from article/addform.tpl */ ?>
<script type="text/javascript" src="/scripts/kindeditor/kindeditor-min.js"></script>
<script type="text/javascript" src="/scripts/kindeditor/lang/zh_CN.js"></script>
<form name="myForm" id="myForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['action'],)));?>" method="post" enctype="multipart/form-data">
<div class="title">添加文章</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="10%"><strong>标题</strong> * </td>
      <td><input type="text" name="article[title]" size="30" value="" msg="请填写标题" class="required" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>类别</strong> * </td>
      <td><select name='article[cat_id]'>
		<option value=''>--请选择--</option>
		<?php $_from = $this->_tpl_vars['catTree']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['cat_id'] => $this->_tpl_vars['item']):
?>
		<?php if ($this->_tpl_vars['item']['leaf']): ?>
		<option value=<?php echo $this->_tpl_vars['cat_id']; ?>
 style="padding-left:<?php echo $this->_tpl_vars['item']['step']*20-20; ?>
px" <?php if ($this->_tpl_vars['catID'] == $this->_tpl_vars['cat_id']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['item']['cat_name']; ?>
（<?php echo $this->_tpl_vars['item']['num']; ?>
）</option>
		<?php else: ?>
		<optgroup label='<?php echo $this->_tpl_vars['item']['cat_name']; ?>
' style="padding-left:<?php echo $this->_tpl_vars['item']['step']*20-20; ?>
px"></optgroup>
		<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
	</select></td>
    </tr>
    <tr>
      <td width="10%"><strong>作者</strong> * </td>
      <td><input name="article[author]" type="text" id="author" value="" size="30" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>来源</strong> * </td>
      <td><input name="article[source]" type="text" id="source" value="" size="30" />
      <span class="gray">分类是“网站公告，促销信息”时，填写跳转页面的链接（url）地址</span>
      </td>
    </tr>

    <tr>
      <td width="10%"><strong>摘要</strong> * </td>
      <td><textarea name="article[abstract]" cols="65" rows="5"></textarea></td>
    </tr>

    <tr>
      <td width="10%"><strong>内容</strong></td>
      <td>

		<textarea name="content" id="content" rows="20" style="width:680px; height:260px;"><?php echo $this->_tpl_vars['article']['content']; ?>
</textarea>
		<script type="text/javascript">
			KindEditor.ready(function(K) {
				K.create('textarea[name="content"]', {
				            filterMode : false,
							allowFileManager : true
						});
			});
		</script>
	  </td>
    </tr>

    <tr>
      <td width="10%"><strong>meta标题</strong> * </td>
      <td><input type="text" name="article[meta_title]" size="30" value="" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>meta关键字</strong> * </td>
      <td><input type="text" name="article[meta_keywords]" size="30" value="" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>meta描述</strong> * </td>
      <td><textarea name="article[meta_description]" rows="3" cols="39" id="meta_description" style="width:330px; height:45px;"></textarea></td>
    </tr>
    <tr>
	  <td><strong>是否推荐置顶</strong></td>
	  <td><input type="radio" name="article[is_hot]" value="0" checked="checked" />否&nbsp;&nbsp;&nbsp;<input type="radio" name="article[is_hot]" value="1" />是</td>
	</tr>
	<tr>
	  <td><strong>代表图片</strong></td>
	  <td><input type="file" name="img_url" /></td>
	</tr>
</tbody>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>