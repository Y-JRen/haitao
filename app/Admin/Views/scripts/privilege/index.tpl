<div class="title">权限类别管理</div>
<div class="content">
    <div id="treeboxbox_tree" style="margin-left:10px; padding: 5px; width:400px; height: 550px; float:left; background-color:#f5f5f5; border:1px solid Silver; overflow:auto;"><img src='/haitaoadmin/images/loading.gif' alt='loading'> 正在加载，请稍候……</div>
    <div style="float:left; padding-left:15px"><input type="button" name="treeboxbox_tree_checked" id="treeboxbox_tree_checked" value="删除" /></div>
</div>
<div>
</div>
<script>
loadCss('/scripts/dhtmlxSuite/dhtmlxTree/dhtmlxtree.css');
loadJs('/scripts/dhtmlxSuite/dhtmlxTree/dhtmlxcommon.js,/scripts/dhtmlxSuite/dhtmlxTree/dhtmlxtree.js,/scripts/dhtmlxSuite/dhtmlxTree/ext/dhtmlxtree_json.js,/scripts/dhtmlxSuite/dhtmlxTree/ext/dhtmlxtree_ed.js', checkLoaded, 'treeboxbox_tree');
function checkLoaded(div)
{
    $(div).set('html', '');
    var tree = new dhtmlXTreeObject(div, '100%', '100%', 0);
    tree.setImagePath('/scripts/dhtmlxSuite/dhtmlxTree/imgs/csh_bluebooks/');
    tree.enableTreeImages(false);
    tree.enableHighlighting(true);
    tree.enableItemEditor(true);
    tree.setOnEditHandler(treeboxbox_tree_edit);
    tree.enableCheckBoxes(true);
    tree.enableThreeStateCheckboxes(true);
    tree.loadJSONObject({{$jsonMixedPrivilege}});
    $('treeboxbox_tree_checked').addEvent('click', function(){
        if (tree.getAllChecked()) {
            var nodeChecked = tree.getAllChecked();
            nodeChecked = nodeChecked + ',';
            if (nodeChecked.indexOf(',') > 0) {
                nodeIdArray = nodeChecked.split(',');
            } else if(nodeChecked != '') {
                nodeIdArray[0] = nodeChecked;
            }
            checkedText = new Array();
            nodeArray = new Array();
            for(var k = 0; k < nodeIdArray.length; k++)
            {
                node = nodeIdArray[k].trim();
                if (node != '') {
                    nodeArray[k] = node;
                    if (tree.getParentId(node) == 0) {
                        checkedText[k] = tree.getItemTooltip(node);
                    } else if (tree.getParentId(node) == 1) {
                        checkedText[k] = tree.getItemTooltip(tree.getParentId(node)) + '|' + tree.getItemTooltip(node);
                    } else {
                        checkedText[k] = tree.getItemTooltip(tree.getParentId(tree.getParentId(node))) + '|' + tree.getItemTooltip(tree.getParentId(node)) + '|' + tree.getItemTooltip(node);
                    }
                }
            }
            if (checkedText) {
                new Request({
                    method: 'get',
                    url: '/admin/privilege/delete/string/' + checkedText.toString(),
                    onSuccess: function(data){
                        if (data != '') {
                            alert(data);
                        } else if (nodeArray.constructor == Array) {
                            for(var i = 0; i < nodeArray.length; i++)
                            {
                                tree.deleteItem(nodeArray[i]);
                            }
                        }
                    },
                    onFailure: function(){
        	            alert('error');
                    }
                }).send();
            }
        }
    });
}

function treeboxbox_tree_edit(state, id, tree, value)
{
    if (state == 0) {
    	orgValue = tree.getItemText(id);
    	orgToolTip = tree.getItemTooltip(id);
    }
    if (state == 2) {
    	if (value == '' || id < 1 || value == orgValue) {
    		return false;
    	} else {
    		if (tree.getParentId(id) == 0) {
    			url = '/admin/privilege/edit/id/' + id + '/mod/' + orgToolTip + '/title/' + encodeURIComponent(value);
    		} else if (tree.getParentId(id) == 1) {
    			url = '/admin/privilege/edit/id/' + id + '/mod/' + tree.getItemTooltip(tree.getParentId(id)) + '/ctl/' + orgToolTip + '/title/' + encodeURIComponent(value);
    		} else {
    			url = '/admin/privilege/edit/id/' + id + '/mod/' + tree.getItemTooltip(tree.getParentId(tree.getParentId(id))) + '/ctl/' + tree.getItemTooltip(tree.getParentId(id)) + '/act/' + orgToolTip + '/title/' + encodeURIComponent(value);
    		}
    		new Request({
                method: 'get',
                url: url,
                onSuccess: function(data){
                    if (data != '') {
                    	alert(data);
                    }
                },
                onFailure: function(){
        	        alert('error');
                }
            }).send();
    	}
    }
    	
    return true;
}
</script>