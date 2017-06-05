<div style="clear: both; padding: 50px 0; text-align: center; font-size: 14px; font-weight: bold; color: #ff0000;">
    {{if $type eq '500'}}
        您访问的页面暂时无法访问,请稍候重试
    {{else}}
      <a href="/" ><img src="{{$imgBaseUrl}}/public/images/page404.jpg" border="0" width="400px" /></a>
		  <!--这里定义倒计时开始数值-->
		  	  
		  <p>	</p>
		  	  
		  <p>	
		   <div style="margin: 0 auto;font-family:'微软雅黑'; font-size:20px; color:#a8a5a5; text-align:center; width:510px;">404错误，你访问的页面不存在</div>	  
		   </p>
		   
		  <p>	</p>	  
		  <p>   </p>
		  
		  
	     <p style="margin-top:35px">
	     	 页面将在 <span id="totalSecond">5</span> 秒钟后跳转至购物商城首页。
		 </p>
		 
		 <!--定义js变量及方法-->
		 <script language="javascript" type="text/javascript">
		  var second = document.getElementById('totalSecond').textContent;
		  if (navigator.appName.indexOf("Explorer") > -1){second = document.getElementById('totalSecond').innerText; } else{second = document.getElementById('totalSecond').textContent; }
		  setInterval("redirect()", 1000);
		  function redirect(){if (second < 0){ <!--定义倒计时后跳转页面-->
		  location.href = '/'; } else{if (navigator.appName.indexOf("Explorer") > -1){document.getElementById('totalSecond').innerText = second--; } else{document.getElementById('totalSecond').textContent = second--; }}}
		  </script>	  
    {{/if}}
</div>