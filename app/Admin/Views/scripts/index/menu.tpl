<script type="text/javascript">
d=new dTree("d");
{{foreach from=$menu item=r key=key}}
{{if $key==$pid}}
d.add({{$r.menu_id}}, -1, ' {{$r.menu_title}}');
{{else}}
d.add({{$r.menu_id}}, {{$r.parent_id}}, '{{$r.menu_title}}', '{{$r.url}}', '', '', '', '', '{{if $r.is_open==0}}true{{/if}}');
{{/if}}
{{/foreach}}
$('menu_iframe').innerHTML = d;
</script>