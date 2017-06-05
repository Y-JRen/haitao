<script type="text/javascript" src="/scripts/kindeditor/kindeditor-min.js"></script>
<script type="text/javascript" src="/scripts/kindeditor/lang/zh_CN.js"></script>
<form name="myForm" id="myForm" action="{{url param.action=$action}}" method="post" enctype="multipart/form-data">
<input type='hidden' name='article[article_id]' value='{{$article.article_id}}'>
<div class="title">编辑文章</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="10%"><strong>标题</strong> * </td>
      <td><input type="text" name="article[title]" size="30" value="{{$article.title}}" msg="请填写标题" class="required" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>类别</strong> * </td>
      <td><select name='article[cat_id]'>
		<option value=''>--请选择--</option>
		{{foreach from=$catTree key=cat_id item=item}}
		{{if $item.leaf}}
		<option value={{$cat_id}} style="padding-left:{{$item.step*20-20}}px" {{if $article.cat_id==$cat_id}}selected="selected"{{/if}}>{{$item.cat_name}}（{{$item.num}}）</option>
		{{else}}
		<optgroup label='{{$item.cat_name}}' style="padding-left:{{$item.step*20-20}}px"></optgroup>
		{{/if}}
		{{/foreach}}
	</select></td>
    </tr>
    <tr>
      <td width="10%"><strong>作者</strong> * </td>
      <td><input name="article[author]" type="text" id="author" value="{{$article.author}}" size="30" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>来源</strong> * </td>
      <td><input name="article[source]" type="text" id="source" value="{{$article.source}}" size="30" /><span class="gray">分类是“网站公告，促销信息”时，填写跳转页面的链接（url）地址</span></td>
    </tr>

    <tr>
      <td width="10%"><strong>摘要</strong> * </td>
      <td><textarea name="article[abstract]" cols="65" rows="5">{{$article.abstract}}</textarea>    </td>
    </tr>

    <tr>
      <td width="10%"><strong>内容</strong></td>
      <td>

		<textarea name="content" id="content" rows="20" style="width:680px; height:260px;">{{$article.content}}</textarea>
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
      <td width="10%"><strong>是否显示</strong></td>
      <td><input name="article[is_view]" type="radio" value="1"  {{if $article.is_view eq 1}} checked="checked"  {{/if}}/>
      显示 　　　　　
        <input name="article[is_view]" type="radio" value="2" {{if $article.is_view eq 2}} checked="checked"  {{/if}} />
        隐藏</td>
    </tr>

    <tr>
      <td width="10%"><strong>meta标题</strong> * </td>
      <td><input type="text" name="article[meta_title]" size="30" value="{{$article.meta_title}}" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>meta关键字</strong> * </td>
      <td><input type="text" name="article[meta_keywords]" size="30" value="{{$article.meta_keywords}}" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>meta描述</strong> * </td>
      <td><textarea name="article[meta_description]" rows="3" cols="39" id="meta_description" style="width:330px; height:45px;">{{$article.meta_description}}</textarea></td>
    </tr>
    <tr>
	  <td><strong>是否推荐置顶</strong></td>
	  <td><input type="radio" name="article[is_hot]" value="0" {{if $article.is_hot eq 0}} checked="checked" {{/if}} />否&nbsp;&nbsp;&nbsp;<input type="radio" name="article[is_hot]" value="1" {{if $article.is_hot eq 1}} checked="checked" {{/if}} />是</td>
	</tr>
	<tr>
	  <td><strong>代表图片</strong></td>
	  <td><input type="file" name="img_url" /></td>
	</tr>
	{{if $article.img_url}}
	<tr>
	  <td></td>
	  <td>原图：<img src="/{{$article.img_url}}" width="100" height="100" /></td>
	</tr>
	{{/if}}
</tbody>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>