<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>登录商城</title>
    <?=\yii\helpers\Html::cssFile('@web/template/style/base.css')?>
    <?=\yii\helpers\Html::cssFile('@web/template/style/global.css')?>
    <?=\yii\helpers\Html::cssFile('@web/template/style/header.css')?>
    <?=\yii\helpers\Html::cssFile('@web/template/style/login.css')?>
    <?=\yii\helpers\Html::cssFile('@web/template/style/footer.css')?>
    <script src="<?=Yii::getAlias('@web')?>/jquery-validation/lib/jquery.js"></script>
    <script src="<?=Yii::getAlias('@web')?>/jquery-validation/dist/jquery.validate.min.js"></script>
    <script src="<?=Yii::getAlias('@web')?>/jquery-validation/dist/localization/messages_zh.js"></script>
</head>
<body>
	<!-- 顶部导航 start -->
	<div class="topnav">
		<div class="topnav_bd w990 bc">
			<div class="topnav_left">
				
			</div>
			<div class="topnav_right fr">
				<ul>
					<li>您好，欢迎来到京西！[<a href="<?=\yii\helpers\Url::to(['member/login'])?>">登录</a>]
                        [<a href="<?=\yii\helpers\Url::to(['member/regist'])?>">免费注册</a>]
                    </li>
					<li class="line">|</li>
					<li>我的订单</li>
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
		</div>
	</div>
	<!-- 页面头部 end -->
	
	<!-- 登录主体部分start -->
	<div class="login w990 bc mt10">
		<div class="login_hd">
			<h2>用户登录</h2>
			<b></b>
		</div>
		<div class="login_bd">
			<div class="login_form fl">
				<form id="commentForm" name="commentForm" action="<?=\yii\helpers\Url::to(['member/login'])?>" method="post">
					<ul>
						<li>
							<label for="username">用户名：</label>
							<input id="username" type="text" class="txt" name="username" />
						</li>
						<li>
							<label for="password">密码：</label>
							<input id="password" type="password" class="txt" name="password" />
							<a href="">忘记密码?</a>
						</li>
						<li class="checkcode">
							<label for="checkcode">验证码：</label>
							<input id="checkcode" type="text"  name="checkcode" />
							<img id="captcha_img" src="" alt="" />
							<span>看不清？<a href="javascript:;" id="change_captcha">换一张</a></span>
						</li>
						<li>
							<label for="chb">&nbsp;</label>
							<input type="checkbox" id="remember" name="remember" class="chb" /> 保存登录信息
						</li>
						<li>
							<label for="">&nbsp;</label>
							<input type="submit" value="" class="login_btn" />
						</li>
					</ul>
				</form>

				<div class="coagent mt15">
					<dl>
						<dt>使用合作网站登录商城：</dt>
						<dd class="qq"><a href=""><span></span>QQ</a></dd>
						<dd class="weibo"><a href=""><span></span>新浪微博</a></dd>
						<dd class="yi"><a href=""><span></span>网易</a></dd>
						<dd class="renren"><a href=""><span></span>人人</a></dd>
						<dd class="qihu"><a href=""><span></span>奇虎360</a></dd>
						<dd class=""><a href=""><span></span>百度</a></dd>
						<dd class="douban"><a href=""><span></span>豆瓣</a></dd>
					</dl>
				</div>
			</div>
			
			<div class="guide fl">
				<h3>还不是商城用户</h3>
				<p>现在免费注册成为商城用户，便能立刻享受便宜又放心的购物乐趣，心动不如行动，赶紧加入吧!</p>

				<a href="<?= \yii\helpers\Url::to(['member/regist'])?>" class="reg_btn">免费注册 >></a>
			</div>

		</div>
	</div>
	<!-- 登录主体部分end -->

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

    <!--jqueryValidate开始-->
    <script type="text/javascript">
        $().ready(function () {
            //在键盘按下并释放及提交后验证提交表单时,验证执行
            $("#commentForm").validate({
                debug: false,//设置为true,表单就只验证不提交,方便调试
                //定义规则
                rules: {
                    username: {
                        required: true,
                        //minlength: 2,//最短
                        //maxlength: 20,//最长
                    },
                    password: {
                        required: true,
                        //minlength: 4,
                    },
                    checkcode: "validateCaptcha"//使用自定义验证规则 验证 验证码
                },
                //提示信息
                messages: {
                    username: {
                        required: "请输入您的用户名",
                        minlength: "用户名至少3个字符",
                        maxlength: "用户名不能超过20个字符",
                    },
                    password: {
                        required: "请输入密码",
                    },
                },
                errorElement:'span'//错误信息的标签
            })
        });
        var hash;
        //定义验证码地址
        var captcha_url = '<?= \yii\helpers\Url::to(['site/captcha'])?>';
        //获取新验证码的url(Yii的验证码)
        var change_captcha = function () {
            $.getJSON(captcha_url,{refresh:1},function (json) {
                $("#captcha_img").attr('src',json.url);
                //将验证码的hash值保存到hash
                hash = json.hash1;
                console.debug(hash);
            })
        }
        change_captcha();
        //点击换一张切换验证码,监听事件
        $("#change_captcha").click(function () {
            change_captcha();
        })
        //点击图片切换验证码,监听事件
        $("#captcha_img").click(function () {
            change_captcha();
        })
        //自定义验证规则:验证 验证码
        jQuery.validator.addMethod("validateCaptcha",function (value,element) {
            //循环遍历用户提交的验证码,然后拿到每个字符的Unicode编码,依次相加,累加起来与hash值对比,如果相等则验证成功
            //这种方式的缺点就是验证码如果调换位置也会验证成功
            var h;
            for(var i =value.length - 1,h = 0;i >= 0;--i){
                h += value.charCodeAt(i);
            }
            return this.optional(element) || (h == hash);
        },"验证码错误");
    </script>
    <!--jqueryValidate结束-->
</body>
</html>