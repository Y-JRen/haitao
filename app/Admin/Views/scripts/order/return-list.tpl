<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
    <form id="searchForm">
    <div style="clear:both; padding-top:5px">
    开始日期：<input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/>
    结束日期：<input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/>
    订单号：<input type="text" name="batch_sn" size="20" maxLength="50" value="{{$param.batch_sn}}">
	用户名：<input type="text" name="user_name" size="20" maxLength="50" value="{{$param.user_name}}">
	收货人名字：<input type="text" name="addr_consignee" size="20" maxLength="50" value="{{$param.addr_consignee}}">
    运单号：<input type="text" name="logistic_no" size="20" maxLength="50" value="{{$param.logistic_no}}"><br />
    店铺：
    <select name="shop_id" id="shop_id">
      <option value="">请选择...</option>
      {{foreach from=$shopDatas item=shop}}
      <option value="{{$shop.shop_id}}" {{if $shop.shop_id eq $param.shop_id}}selected{{/if}}>{{$shop.shop_name}}</option>
      {{/foreach}}
    </select>
    渠道订单号：
    <input type="text" name="external_order_sn" value="{{$param.external_order_sn}}" />
    
     支付状态:
    <select name="status_pay">
    <option value="">请选择...</option>
    <option value="0" {{if $param.status_pay eq '0'}}selected{{/if}}>未收款</option>
    <option value="1" {{if $param.status_pay eq '1'}}selected{{/if}}>未退款</option>
    <option value="2" {{if $param.status_pay eq '2'}}selected{{/if}}>已收款</option>
    <option value="3" {{if $param.status_pay eq '3'}}selected{{/if}}>部分收款</option>
    </select>
    配送状态:
    <select name="status_logistic">
    <option value="">请选择...</option>
    <option value="0" {{if $param.status_logistic eq '0'}}selected{{/if}}>未确认</option>
    <option value="1" {{if $param.status_logistic eq '1'}}selected{{/if}}>已确认[待收款]</option>
    <option value="2" {{if $param.status_logistic eq '2'}}selected{{/if}}>待发货</option>
    <option value="3" {{if $param.status_logistic eq '3'}}selected{{/if}}>已发货未签收</option>
    <option value="4" {{if $param.status_logistic eq '4'}}selected{{/if}}>客户已签收</option>
    <option value="5" {{if $param.status_logistic eq '5'}}selected{{/if}}>客户拒收</option>
    <option value="6" {{if $param.status_logistic eq '6'}}selected{{/if}}>部分签收</option>
    </select>
    退换货状态:
    <select name="status_return">
    <option value="">请选择...</option>
    <option value="0" {{if $param.status_return eq '0'}}selected{{/if}}>正常单</option>
    <option value="1" {{if $param.status_return eq '1'}}selected{{/if}}>退货单</option>
    </select>

    <input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'{{url param.do=search}}','ajax_search')"/>
	<br />
	<input type="button" name="dosearch" value="所有被我锁定的订单" onclick="ajax_search($('searchForm'),'{{url param.do=search param.is_lock=yes}}','ajax_search')"/>
	<input type="button" name="dosearch" value="所有没有锁定的订单" onclick="ajax_search($('searchForm'),'{{url param.do=search param.is_lock=no}}','ajax_search')"/>
    <input type="button" name="dosearch" value="所有挂起的订单" onclick="ajax_search($('searchForm'),'{{url param.do=search param.hang=1}}','ajax_search')"/>
    </form>
</div>	
</div>	
<form name="myForm" id="myForm">
	<div class="title">退/换货开单列表   [ <a href="javascript:fGo()" onclick="G('{{url param.action=return-list  param.rejection=1}}')"><font color="#FF0000">拒收开单</font></a> ]  </div>  
	<div class="content">
<div style="padding:0 5px">
	<div style="float:left;width:500px;">
		<input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall(this.form,'ids',this)"/> 
		<input type="button" value="锁定" onclick="ajax_submit(this.form, '{{url param.action=lock}}/lock/1','Gurl(\'refresh\')')"> 
		<input type="button" value="解锁" onclick="ajax_submit(this.form, '{{url param.action=lock}}/lock/0','Gurl(\'refresh\')')">
		<input type="button" value="超级锁定" onclick="ajax_submit(this.form, '{{url param.action=super-lock}}/lock/1','Gurl(\'refresh\')')"> 
		<input type="button" value="超级解锁" onclick="ajax_submit(this.form, '{{url param.action=super-lock}}/lock/0','Gurl(\'refresh\')')">
	</div>
	<div style="float:right;"><b>订单总金额：￥{{$totalPriceOrder}}</b></div>
</div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td width=10></td>
				<td width="40">操作</td>
				<td>ID</td>
                <td>店铺</td>
				<td width="120">订单号</td>
                <td>下单时间</td>
				<td width="350">订单商品</td>
				<td width="60">收货人</td>
				<td>金额</td>
				<td>支付方式</td>
				<td>锁定状态</td>
			  </tr>
		</thead>
		<tbody>
		{{foreach from=$data item=item}}
		<tr id="ajax_list{{$item.order_batch_id}}">
			<td valign="top"><input type='checkbox' name="ids[]" value="{{$item.batch_sn}}"></td>
			<td valign="top">
			{{if $item.lock_name==$auth}}
			<input type="button" onclick="G('{{url param.action=info param.batch_sn=$item.batch_sn}}')" value="修改">
			{{else}}
			<input type="button" onclick="G('{{url param.action=info param.batch_sn=$item.batch_sn}}')" value="查看">
			{{/if}}</td>
			<td valign="top">{{$item.order_batch_id}}</td>
            <td valign="top">{{$item.shop_name}}</td>
			<td valign="top">
			{{$item.batch_sn}}
			{{if $item.hang}}<br /><font color="red">已被{{$item.hang_admin_name}}挂起</font>{{/if}}
			<br />
			{{$item.status}}  {{$item.status_pay}} {{$item.status_logistic}}  {{$item.status_return}}  
			</td>
            <td valign="top">{{$item.add_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
			<td valign="top">
				{{foreach from=$product item=goods}}
					{{if $goods.batch_sn==$item.batch_sn}}
						 {{$goods.goods_name}} (<font color="#FF3333">{{$goods.goods_style}}</font>)  
                         <font color="#336633">{{$goods.product_sn}} </font><br />
					{{/if}}
				{{/foreach}}
			</td>
			<td valign="top">{{$item.addr_consignee}}</td>
			<td valign="top">
			{{if $item.blance>0}}应收：{{$item.blance}}<br />{{/if}}
			{{if $item.price_payed+$item.price_from_return>0}}已收：{{$item.price_payed+$item.price_from_return}}<br />{{/if}}
			{{if $item.blance<0}}应退：{{$item.blance|replace:"-":""}}<br />{{/if}}
			</td>
			<td valign="top">{{$item.pay_name}}</td>
			<td valign="top">{{if $item.lock_name}}<font color="red">被{{$item.lock_name}}锁定</font>{{else}}未锁定{{/if}}</td>
		  </tr>
		{{/foreach}}
		</tbody>
		</table>
	</div>
	<div style="padding:0 5px;">
		<input type="checkbox" name="chkall" title="全选/全不选" onclick="checkall(this.form,'ids',this)"/> 
		<input type="button" value="锁定" onclick="ajax_submit(this.form, '{{url param.action=lock}}/lock/1','Gurl(\'refresh\')')"> 
		<input type="button" value="解锁" onclick="ajax_submit(this.form, '{{url param.action=lock}}/lock/0','Gurl(\'refresh\')')">
		<input type="button" value="超级锁定" onclick="ajax_submit(this.form, '{{url param.action=super-lock}}/lock/1','Gurl(\'refresh\')')"> 
		<input type="button" value="超级解锁" onclick="ajax_submit(this.form, '{{url param.action=super-lock}}/lock/0','Gurl(\'refresh\')')">
	</div>
	<div class="page_nav">{{$pageNav}}</div>
</form>