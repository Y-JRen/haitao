<form name="myForm" id="myForm" action="{{url}}" method="post">
<div class="title">{{if $action eq 'edit'}}编辑库位{{else}}添加库位{{/if}}</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr> 
      <td width="15%"><strong>所属仓库</strong> * </td>
      <td>
        <select name="area" onchange="changeArea(this.value)" {{if $action eq 'edit'}}disabled{{/if}}>
          {{foreach from=$areas item=item key=key}}
          {{if $area eq $key}}
          <option value="{{$key}}" {{if $data.area eq $key}}selected{{/if}}>{{$item}}</option>
          {{/if}}
          {{/foreach}}
        </select>
      </td>
    </tr>
    <tr> 
      <td width="15%"><strong>所属库区</strong> * </td>
      <td id="districtBox">
        <select name="district_id" id="district_id" onchange="changeDistrict()" {{if $action eq 'edit'}}disabled{{/if}}>
          {{foreach from=$districts item=item key=key}}
          <option value="{{$key}}" {{if $data.district_id eq $key}}selected{{/if}}>{{$item}}</option>
          {{/foreach}}
        </select>
        {{if $action eq 'edit'}}
          <input type="hidden" name="district_id" value="{{$data.district_id}}">
        {{/if}}
      </td>
    </tr>
    <tr> 
      <td><strong>库位编号</strong> * </td>
      <td><input type="text" name="position_no" id="position_no" size="20" value="{{$data.position_no}}" msg="请填写库位编号" class="required" /></td>
    </tr>
    <tr> 
      <td><strong>是否启用</strong> * </td>
      <td>
	   <input type="radio" name="status" value="0" {{if $data.status==0 && $action eq 'edit'}}checked{{/if}}/> 是
	   <input type="radio" name="status" value="1" {{if $data.status==1 or $action eq 'add'}}checked{{/if}}/> 否
	  </td>
    </tr>
</tbody>
</table>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定"/> <input type="reset" name="reset" value="重置" /></div>
</form>
<script>
function changeArea(area)
{
    new Request({
        url:'/admin/stock-report/get-district-box/area/' + area,
	    onSuccess:function(msg){
            $('districtBox').innerHTML = msg;
            changeDistrict();
		},
		onError:function() {
			alert("网络繁忙，请稍后重试");
		}
	}).send();
}
function changeDistrict()
{
    if ($('district_id') == null) {
        $('position_no').value = '';
        return;
    }
    
    var district_id = $('district_id').value;
    new Request({
        url:'/admin/stock-report/get-district-no/district_id/' + district_id,
	    onSuccess:function(msg){
            $('position_no').value = msg;
		},
		onError:function() {
			alert("网络繁忙，请稍后重试");
		}
	}).send();
}
</script>