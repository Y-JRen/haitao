<div class="content">
    <div style="width:100%;text-align:center;height:20px;padding-top:5px;color:red;font-weight:700;">注意：清缓存请不要频繁操作</div>
      <table cellpadding="0" cellspacing="0" border="0" class="table_form">
        <tbody>
            <tr>
              <td>
                <b>商城快速清理指定的缓存</b>
              </td>
            </tr>
            <tr>
              <td>
                <select name="domain" id="domain">
                  <option value="www.1jiankang.com">官网B2C</option>
                </select>&nbsp;&nbsp;
                <input type="button" id="index" onclick="cleanCache('index');" value="首页">
                <input type="button" id="goodsgallery" onclick="cleanCache('goodsgallery');" value="所有商品列表">
                <input type="button" id="groupgoodsgallery" onclick="cleanCache('groupgoodsgallery');" value="所有组合商品列表">
                <input type="button" id="goodsshow" onclick="cleanCache('goodsshow');" value="所有商品单页">
                <input type="button" id="groupgoodsshow" onclick="cleanCache('groupgoodsshow');" value="所有组合商品单页">
                <input type="button" id="helppage" onclick="cleanCache('helppage');" value="帮助文章页面">
                <input type="button" id="special" onclick="cleanCache('special');" value="全部专题页">
              </td>
            </tr>
            <tr>
              <td>
                <input type="button" id="js" onclick="cleanCache('js');" value="JS目录">
                <input type="button" id="css" onclick="cleanCache('css');" value="CSS目录">
              </td>
            </tr>
            <tr>
              <td>
                <input type="text" name="img" id="img" size="25"> <font color="#999999">必须以/开头</font>
                <input type="button" id="img_file" onclick="cleanCache('img_file');" value="图片文件">
                <input type="button" id="img_folder" onclick="cleanCache('img_folder');" value="图片目录">
              </td>
            </tr>
            <tr>
              <td>
                <input type="text" name="url" id="url" size="50"> <font color="#999999">必须以http://开头</font>
                <input type="button" id="full_url" onclick="cleanCache('full_url');" value="完整地址">
              </td>
            </tr>
           </tbody>
       </table>
</div>
<script>
function cleanCache(type){
	var data = 'type=' + type + '&domain=' + document.getElementById('domain').value + '&img=' + document.getElementById('img').value + '&url=' + document.getElementById('url').value;
	if ( type != 'img_file' && type != 'img_folder' && type != 'full_url') {
        $(type).disabled = true;
    }
	new Request({
	    url: '/admin/index/clean-cache-cdn/',
	    method: 'post',
	    data: data,
	    onSuccess: function(data)
	    {
	    	alert(data);
	    }
	}).send();
}
</script>