{{if !empty($menus)}}
<select name="sel_menu_id" onchange="if(this.value&gt;0){ajax_get_child('{{url param.action=getchild}}',this.value);this.disabled=true;}">
      {{if $pid==0}}
      <option value="0" selected>设为顶部菜单</option>
      {{else}}
      <option value="-1" selected>请选择</option>
      {{/if}}
      {{foreach from=$menus item=menu}}
      <option value="{{$menu.menu_id}}">{{$menu.menu_title}}</option>
      {{/foreach}}
</select>
{{/if}}