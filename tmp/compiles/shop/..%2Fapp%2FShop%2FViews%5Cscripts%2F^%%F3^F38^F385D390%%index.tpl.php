<?php /* Smarty version 2.6.19, created on 2014-12-11 17:16:25
         compiled from member/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'member/index.tpl', 7, false),array('function', 'widget', 'member/index.tpl', 27, false),)), $this); ?>
<div class="memberCenter">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "member/menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      <div class="mcContentRight">
        <div class="mcContentRightTop">
          <div class="member_content">
            <ul class="member_tx">
              <li class="member_tx_img"><img src="<?php echo $this->_tpl_vars['imgBaseUr']; ?>
/<?php echo ((is_array($_tmp=@$this->_tpl_vars['member']['photo'])) ? $this->_run_mod_handler('default', true, $_tmp, 'public/images/member_tx.jpg') : smarty_modifier_default($_tmp, 'public/images/member_tx.jpg')); ?>
"/></li>
              
            </ul>
            <ul class="member_main">
              <li class="member_name"><span><?php if ($this->_tpl_vars['member']['nick_name']): ?><?php echo $this->_tpl_vars['member']['nick_name']; ?>
<?php else: ?><?php echo $this->_tpl_vars['member']['user_name']; ?>
<?php endif; ?><span>&nbsp;&nbsp;&nbsp;&nbsp;欢迎回来！</li>
              <li class="member_event first">
                <div class="member_event_tit">事务提醒：</div>
                <div class="member_event_list"><a href="/member/order/ordertype/4">等待付款（<?php echo $this->_tpl_vars['feeOrder']; ?>
）</a></div>
                <div class="member_event_list"><a href="/member/order/ordertype/6">等待收货（<?php echo $this->_tpl_vars['feeOrder2']; ?>
）</a></div>
                
                <br class="clearfix"/>
              </li>
              <li class="member_event">
                <div class="member_event_tit">我的收藏：</div>
                <div class="member_event_list"><a href="/member/favorite">收藏数量（<?php echo $this->_tpl_vars['fav']; ?>
）</a></div>
                <br class="clearfix"/>
              </li>
            </ul>
            <br class="clearfix"/>
          </div>
          <div class="member_img"><?php echo smarty_function_widget(array('class' => 'AdvertWidget','id' => '31'), $this);?>
</div>
        </div>
        <div class="mcContentRightMiddle">
          <div class="mccrmTitle"><img src="<?php echo $this->_tpl_vars['imgBaseUr']; ?>
/public/images/mcContentRightMiddle_title.jpg"/></div>
          <ul class="tj_product">
          	<?php unset($this->_sections['v']);
$this->_sections['v']['name'] = 'v';
$this->_sections['v']['loop'] = is_array($_loop=$this->_tpl_vars['tui']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['v']['start'] = (int)1;
$this->_sections['v']['step'] = ((int)1) == 0 ? 1 : (int)1;
$this->_sections['v']['max'] = (int)4;
$this->_sections['v']['show'] = true;
if ($this->_sections['v']['max'] < 0)
    $this->_sections['v']['max'] = $this->_sections['v']['loop'];
if ($this->_sections['v']['start'] < 0)
    $this->_sections['v']['start'] = max($this->_sections['v']['step'] > 0 ? 0 : -1, $this->_sections['v']['loop'] + $this->_sections['v']['start']);
else
    $this->_sections['v']['start'] = min($this->_sections['v']['start'], $this->_sections['v']['step'] > 0 ? $this->_sections['v']['loop'] : $this->_sections['v']['loop']-1);
if ($this->_sections['v']['show']) {
    $this->_sections['v']['total'] = min(ceil(($this->_sections['v']['step'] > 0 ? $this->_sections['v']['loop'] - $this->_sections['v']['start'] : $this->_sections['v']['start']+1)/abs($this->_sections['v']['step'])), $this->_sections['v']['max']);
    if ($this->_sections['v']['total'] == 0)
        $this->_sections['v']['show'] = false;
} else
    $this->_sections['v']['total'] = 0;
if ($this->_sections['v']['show']):

            for ($this->_sections['v']['index'] = $this->_sections['v']['start'], $this->_sections['v']['iteration'] = 1;
                 $this->_sections['v']['iteration'] <= $this->_sections['v']['total'];
                 $this->_sections['v']['index'] += $this->_sections['v']['step'], $this->_sections['v']['iteration']++):
$this->_sections['v']['rownum'] = $this->_sections['v']['iteration'];
$this->_sections['v']['index_prev'] = $this->_sections['v']['index'] - $this->_sections['v']['step'];
$this->_sections['v']['index_next'] = $this->_sections['v']['index'] + $this->_sections['v']['step'];
$this->_sections['v']['first']      = ($this->_sections['v']['iteration'] == 1);
$this->_sections['v']['last']       = ($this->_sections['v']['iteration'] == $this->_sections['v']['total']);
?>
          	<li <?php if ($this->_sections['v']['index'] == 4): ?>class="last"<?php endif; ?>>
              <div class="tj_product_img"><a href='/goods-<?php echo $this->_tpl_vars['tui'][$this->_sections['v']['index']]['goods_id']; ?>
.html' title=<?php echo $this->_tpl_vars['tui'][$this->_sections['v']['index']]['goods_name']; ?>
><img src="<?php echo $this->_tpl_vars['imgBaseUr']; ?>
/<?php echo $this->_tpl_vars['tui'][$this->_sections['v']['index']]['goods_img']; ?>
"/></a></div>
              <p class="tj_product_name"><?php echo $this->_tpl_vars['tui'][$this->_sections['v']['index']]['title']; ?>
</p>
              <div class="tj_product_price">
                <div class="tj_product_price01">品牌：<?php echo $this->_tpl_vars['tui'][$this->_sections['v']['index']]['brand_name']; ?>
</div>
                <div class="tj_product_price02">￥<?php echo $this->_tpl_vars['tui'][$this->_sections['v']['index']]['price']; ?>
</div>
                <br class="clearfix"/>
              </div>
            </li>
            
          	
          	<?php endfor; endif; ?>
            
            <br class="clearfix"/>
          </ul>
        </div>
        <div class="mcContentRightBottom"><?php echo smarty_function_widget(array('class' => 'AdvertWidget','id' => '32'), $this);?>
</div>
      </div>
      <br class="clearfix"/>
    </div>
  </div>
<script>
  	//清空浏览记录
		function clearCook(url,elt) {
			$.ajax({
				type : "GET",
				cache : false,
				url : url,
				success : function(msg) {
					$(elt).parent().parent().parent().find('.summary_content').empty().html("<div style='color:#999999;padding:10px;'>暂无浏览记录！</div>");;
				}
			});
		}
</script> 