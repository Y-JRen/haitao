<div class="title">系统信息</div>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>服务器信息</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$systemMessage name=message key=key item=message}}
            {{if $key mod 2 eq 0}}
            <tr>
            {{/if}}
                <td width="20%" style="padding-left: 5px">{{$message.title}}</td>
                <td width="30%" {{if $key mod 2 eq 0}}style="border-right:1px solid #eeeeee"{{/if}}>{{$message.value}}</td>
        {{/foreach}}
        {{if $smarty.foreach.message.total mod 2 neq 0}}
           <td width="20%"></td>
           <td width="30%"></td>
        {{/if}}
        </tbody>
    </table>
</div>