<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>成功提交订单</title>
    <!--引入CSS文件-->
    <?=\yii\helpers\Html::cssFile('@web/template/style/base.css')?>
    <?=\yii\helpers\Html::cssFile('@web/template/style/global.css')?>
    <?=\yii\helpers\Html::cssFile('@web/template/style/header.css')?>
    <?=\yii\helpers\Html::cssFile('@web/template/style/footer.css')?>
    <?=\yii\helpers\Html::cssFile('@web/template/style/success.css')?>
    <!--引入JS文件-->
    <?=\yii\helpers\Html::jsFile('@web/template/js/jquery-1.8.3.min.js')?>
</head>
<body>
	<!-- 顶部导航 start -->
	<div class="topnav">
		<div class="topnav_bd w990 bc">
			<div class="topnav_left">
				
			</div>
			<div class="topnav_right fr">
                <ul>
                    <li id="user_status">您好，欢迎来到京西！[<a href="<?=\yii\helpers\Url::to(['member/login'])?>">登录</a>]
                        [<a href="<a href="<?=\yii\helpers\Url::to(['member/regist'])?>">免费注册</a>]
                    </li>
                    <li class="line">|</li>
                    <li><a href="<?=\yii\helpers\Url::to(['order/index'])?>">我的订单</a></li>
                    <li class="line">|</li>
                    <li>客户服务</li>
                </ul>
			</div>
		</div>
	</div>
	<!-- 顶部导航 end -->
	
	<div style="clear:both;"></div>
	
	<!-- 页面头部 start -->
	<div class="header w990 bc mt15">
		<div class="logo w990">
			<h2 class="fl"><a href="<?=\yii\helpers\Url::to(['goods/index'])?>"><img src="<?=Yii::getAlias('@web')?>/template/images/logo.png" alt="京西商城"></a></h2>
			<div class="flow fr flow3">
				<ul>
					<li>1.我的购物车</li>
					<li>2.填写核对订单信息</li>
					<li class="cur">3.成功提交订单</li>
				</ul>
			</div>
		</div>
	</div>
	<!-- 页面头部 end -->
	
	<div style="clear:both;"></div>

	<!-- 主体部分 start -->
	<div class="success w990 bc mt15">
		<div class="success_hd">
			<h2>订单提交成功</h2>
		</div>
		<div class="success_bd">
			<p><span></span>订单提交成功，我们将及时为您处理</p>
			
			<p class="message">完成支付后，你可以
                <a href="<?=\yii\helpers\Url::to(['index'])?>">查看订单状态</a>
                <a href="<?=\yii\helpers\Url::to(['goods/index'])?>">继续购物</a>
                <a href="">问题反馈</a>
            </p>
		</div>
	</div>
	<!-- 主体部分 end -->

	<div style="clear:both;"></div>
	<!-- 底部版权 start -->
	<div class="footer w1210 bc mt15">
		<p class="links">
			<a href="">关于我们</a> |
			<a href="">联系我们</a> |
			<a href="">人才招聘</a> |
			<a href="">商家入驻</a> |
			<a href="">千寻网</a> |
			<a href="">奢侈品网</a> |
			<a href="">广告服务</a> |
			<a href="">移动终端</a> |
			<a href="">友情链接</a> |
			<a href="">销售联盟</a> |
			<a href="">京西论坛</a>
		</p>
		<p class="copyright">
			 © 2005-2013 京东网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号 
		</p>
		<p class="auth">
			<a href=""><img src="<?=Yii::getAlias('@web')?>/template/images/xin.png" alt="" /></a>
			<a href=""><img src="<?=Yii::getAlias('@web')?>/template/images/kexin.jpg" alt="" /></a>
			<a href=""><img src="<?=Yii::getAlias('@web')?>/template/images/police.jpg" alt="" /></a>
			<a href=""><img src="<?=Yii::getAlias('@web')?>/template/images/beian.gif" alt="" /></a>
		</p>
	</div>
	<!-- 底部版权 end -->

    <script type="text/javascript">
        //ajax请求判断用户登录状态
        //document.execCommand("BackgroundImageCache", false, true);
        $.getJSON("<?=\yii\helpers\Url::to(['member/user-status'])?>",function (json) {
            if(json.isLogin){
                $("#user_status").html("欢迎&nbsp[" + json.name + "]&nbsp<a href='<?=\yii\helpers\Url::to(['member/logout'])?>'>注销</a>");
            }
        });
    </script>
</body>
</html>
