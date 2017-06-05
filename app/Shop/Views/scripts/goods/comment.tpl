<div class="comment_score">
                    	<div class="c_left">
                        	<h2>商品评价</h2>
                            <div class="rate">
                            	<b>{{$level_data_bf.level1}}<i>%</i></b><br /><span>好评度</span>
                            </div>
                            <div class="percent">
                            	<p>共有 {{$level_data.levelall}}人参与评价</p>
                                <dl>
                                	<dt>好评</dt>
                                    <dd class="len"><span style="width:	{{$level_data_bf.level1}}%;"></span></dd>
                                    <dd>{{$level_data_bf.level1}}%</dd>
                                </dl>
                                <dl>
                                	<dt>中评</dt>
                                    <dd class="len"><span style="width:{{$level_data_bf.level2}}%;"></span></dd>
                                    <dd>{{$level_data_bf.level2}}%</dd>
                                </dl>
                                <dl>
                                	<dt>差评</dt>
                                    <dd class="len"><span style="width:{{$level_data_bf.level3}}%;"></span></dd>
                                    <dd>{{$level_data_bf.level3}}%</dd>
                                </dl>
                            </div>
                        </div>
                        <div class="c_right">
                        	<p>买商品进行中肯评价将会给其他网友购物提供参考，并可以获得 10积分评价奖励。<br /><a href="#">查看积分规则</a></p>
                        </div>
                    </div>
                  <div class="comment_list" >   
                  <div class="tab">
                            <ul id="tab-coment-menu">
                                <li {{if $type eq 0}}class="current"{{/if}}><a href="javascript:;" onclick="getCommentListnew(0,1)"  class="first">全部评价({{$level_data.levelall}})</a></li>
                                <li {{if $type eq 1}}class="current"{{/if}}><a href="javascript:;" onclick="getCommentListnew(1,1)">好评({{$level_data.level1}})</a></li>
                                <li {{if $type eq 2}}class="current"{{/if}}><a href="javascript:;" onclick="getCommentListnew(2,1)">中评({{$level_data.level2}})</a></li>
                                <li {{if $type eq 3}}class="current"{{/if}}><a href="javascript:;" onclick="getCommentListnew(3,1)">差评({{$level_data.level3}})</a></li>
                                <li><a href="javascript:;" onclick="getCommentListnew('post',1)">发表评价</a></li>
                            </ul>
                            <div class="btn_addcart"><a href="javascript:;" onclick="addCart('{{$goods.goods_sn}}','buy_cart')" >加入购物车</a></div>
                        </div>
                        <div class="tab_con">
                    	 <div class="cm_list"  id="comment-box" >     
                    	  {{if $datas}}              	 
                    	 {{foreach from=$datas item=item}}                    	 
                    	   <dl>
                            	<dt>
                                	<p class="p_img"><img src="{{$item.pohto}}" width="85" height="85" /></p>
                                    <p class="p_name">{{$item.user_name}}</p>
                                </dt>
                                <dd>
                                	<div><div class="star star0{{$item.cnt1}}"></div><i>{{$item.add_time}}</i></div>
                                    <p>{{$item.content}}</p>
                                </dd>
                            </dl>       
		                  {{/foreach}}                         
                            {{$pageNav}}   
                          {{else}}
                              <div class="tac c2 fb">  {{if $type eq 0}}暂无用户发表评论。{{else}} 暂无用户发表该类体验评论。{{/if}}</div>
                          {{/if}}              	 
                           </div>
                      	 <div class="add_comment none" id="post-comment-box">
                         	<!---unlogin--->
                            <p class="unlogin {{if !$login}}none{{/if}}">请登录后再发表评价。<a href="/login.html">立即登录</a></p>
                            
                            <!----logined--->
                            <form action="javascript:;" onsubmit="submitGoodsComment(this)" method="post" name="commentForm" id="commentForm">
							<input type="hidden" name="score" id="score">
							<input type="hidden" name="title" value="产品评论" />
							<input type="hidden" name="goods_id" value="{{$id}}" />
							<input type="hidden" name="goods_name" value="{{$goods.goods_name}}" />
                            <div class="logined {{if $login}}none{{/if}}">
                            	<h2>发表评价</h2>
                                <div class="w_star">
                                	<span>评分：</span>
                                    <div id="star">
                                        <ul>
                                            <li title="很差"><a href="javascript:;" title="很差">1</a></li>
                                            <li title="比较差"><a href="javascript:;" title="比较差">2</a></li>
                                            <li title="一般"><a href="javascript:;" title="一般">3</a></li>
                                            <li title="很好"><a href="javascript:;" >4</a></li>
                                            <li title="强烈推荐"><a href="javascript:;" >5</a></li>  
                                        </ul>
                                    </div><!--star end-->                                    
                                    
                                </div>
                                <div class="add_content">
                                	<span>内容：</span><textarea name="content" id="post_content">输入评价内容，限500字。</textarea>
                                </div>
                                <div class="btn_comment"><a href="javascript:;" onclick="$('#commentForm').submit();">评价</a></div>                                
                            </div>
                            </form>
                            
                         </div>
                         
                       </div>
  </div>



<script type="text/javascript">
    $("#star li").hover(
    		function(){$("#star li").removeClass('on');var index = parseInt($(this).index())+1; $("#star li:lt("+index+")").addClass('on');},
    		function(){$("#star li").removeClass('on'); var score = $("#score").val(); $("#star li:lt("+score+")").addClass('on')});
    
    $("#star li").click(function(){
    	var index = parseInt($(this).index())+1;
    	$("#score").val(index);
    });
	$('#post_content').iClear({enter: $(':submit')}); 
	/**
	 * 提交评论信息
	 */
	var cmt_empty_content = "评论的内容不能小于10个字符";
	var cmt_large_content = "您输入的评论内容超过了500个字符";
	function submitGoodsComment() {
		var uname = $.trim($("#commentForm input[name='user_name']").val());
		var content = $.trim($("#post_content").val());
        var score = parseInt($("#score").val());
        if(score == 0)
        {
          alert("请选择评分！");	
        }
        
		if (content.length < 10 || content =="输入评价内容，限500字。") {
			alert(cmt_empty_content);
			return false;
		}
		
		if (content.length > 250) {
			alert(cmt_large_content);
			return false;
		}

		$.ajax({
			url : '/goods/msg',
			type : 'post',
			data : $('#commentForm').serialize(),
			success : function(msg) {
				if (msg != '') {
					alert(msg);
				} else {
					alert('您的评论已成功提交，请等待管理员审核');
					$("#post_content").val('');
				}
			},
			error : function(msg, err) {
				alert(err);
			}
		})
		return false;
	}

	function getCommentListnew(conf, page) {
		if(conf == 'post') //发表评论
		{
			$("#tab-coment-menu li").removeClass('current');
			$("#tab-coment-menu li:eq(4)").addClass('current');
			$("#comment-box").addClass("none");
			$("#post-comment-box").removeClass('none');
		   return false;	
		}
		
		$.ajax({
			url : '/goods/comment',
			data : {
				id : '{{$id}}',
				conf : conf,
				page : page
			},
			type : 'get',
			success : function(msg) {
				$('#comment_list').html(msg);
			}
		})
	}
</script>