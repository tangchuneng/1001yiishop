<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>用户注册</title>
    <?=\yii\helpers\Html::cssFile('@web/template/style/base.css')?>
    <?=\yii\helpers\Html::cssFile('@web/template/style/global.css')?>
    <?=\yii\helpers\Html::cssFile('@web/template/style/header.css')?>
    <?=\yii\helpers\Html::cssFile('@web/template/style/login.css')?>
    <?=\yii\helpers\Html::cssFile('@web/template/style/footer.css')?>

    <!--jqueryValidate将校验规则写到控件中-->
    <script src="<?=Yii::getAlias('@web')?>/jquery-validation/lib/jquery.js"></script>
    <script src="<?=Yii::getAlias('@web')?>/jquery-validation/dist/jquery.validate.min.js"></script>
    <script src="<?=Yii::getAlias('@web')?>/jquery-validation/dist/localization/messages_zh.js"></script>

    <!--<script>
        $.validator.setDefaults({
            submitHandler: function() {
                alert("提交事件!");
            }
        });
    </script>-->

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
                        [<a href="<?=\yii\helpers\Url::to(['member/regist'])?>">免费注册</a>] </li>
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
			<h2 class="fl"><a href="index.html"><img src="<?=Yii::getAlias('@web')?>/template/images/logo.png" alt="京西商城"></a></h2>
		</div>
	</div>
	<!-- 页面头部 end -->
	
	<!-- 登录主体部分start -->
	<div class="login w990 bc mt10 regist">
		<div class="login_hd">
			<h2>用户注册</h2>
		</div>
		<div class="login_bd">
			<div class="login_form fl">
				<form class="cmxform" id="commentForm" action="<?= \yii\helpers\Url::to(['member/regist'])?>" method="post">
					<ul>
						<li>
							<label for="name">用户名：</label>
							<input id="name" type="text" class="txt" name="username" placeholder="请输入..." />
							<p>3-20位字符，可由中文、字母、数字和下划线组成</p>
						</li>
						<li>
							<label for="password">密码：</label>
							<input id="password" type="password" class="txt" name="password" />
							<p>6-20位字符，可使用字母、数字和符号的组合，不建议使用纯数字、纯字母、纯符号</p>
						</li>
						<li>
							<label for="confirm_password">确认密码：</label>
							<input id="confirm_password" type="password" class="txt" name="confirm_password" />
							<p> <span>请再次输入密码</p>
						</li>
						<li>
							<label for="email">邮箱：</label>
							<input id="email" type="text" class="txt" name="email" />
							<p>邮箱必须合法</p>
						</li>
						<li>
							<label for="tel">手机号码：</label>
							<input id="tel" type="text" class="txt" value="" name="tel" placeholder=""/>
						</li>
						<li>
							<label for="captcha">验证码：</label>
							<input type="text" class="txt" value="" placeholder="请输入短信验证码" name="captcha" disabled="disabled" id="captcha"/>
                            <input type="button" onclick="bindPhoneNum(this)" id="get_captcha" value="获取验证码" name="submit_btn" style="height: 25px;padding:3px 8px"/>
						</li>
						<li class="checkcode">
							<label for="captcha_img">验证码：</label>
							<input type="text"  name="checkcode" />
							<img id="captcha_img" src="" alt="" />
							<span>看不清？<a href="javascript:;" id="change_captcha">换一张</a></span>
						</li>
						
						<li>
							<label for="">&nbsp;</label>
                            <input type="checkbox" class="chb" checked="checked" name="chb" required minlength="1" id="chb" />我已阅读并同意《用户注册协议》
						</li>
						<li>
							<label for="">&nbsp;</label>
							<input type="submit" value="" class="login_btn" />
						</li>
					</ul>
				</form>

				
			</div>
			
			<div class="mobile fl">
				<h3>手机快速注册</h3>			
				<p>中国大陆手机用户，编辑短信 “<strong>XX</strong>”发送到：</p>
				<p><strong>1069099988</strong></p>
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

	<script type="text/javascript">
		function bindPhoneNum(){
			//启用短信验证码的输入框
			$('#captcha').prop('disabled',false);

			var time=30;
			var interval = setInterval(function(){
				time--;
				if(time <= 0){
					clearInterval(interval);
					var html = '获取验证码';
					$('#get_captcha').prop('disabled',false);
				} else{
					var html = time + ' 秒后再次获取';
					$('#get_captcha').prop('disabled',true);
				}

				$('#get_captcha').val(html);
			},1000);
			//发送短信
            $.post("<?=\yii\helpers\Url::to(['sms'])?>",{phone:$("#tel").val(),'_csrf-frontend':'<?=Yii::$app->request->csrfToken?>'},function (data) {
                console.log(data);
            });
		}

        <!--jqueryValidate开始-->
		$().ready(function () {
			//在键盘按下并释放及提交后验证提交表单时,验证执行
			$("#commentForm").validate({
                debug: false,//设置为true,表单就只验证不提交,方便调试
                //定义规则
				rules: {
                    username: {
                        required: true,
                        minlength: 2,//最短
                        maxlength: 20,//最长
                        remote: "<?= \yii\helpers\Url::to(['member/validate-user'])?>"// ajax 验证用户名是否唯一 默认传当前字段的值
                    },
                    password: {
                        required: true,
                        minlength: 4,
                    },
                    confirm_password: {
                        required: true,
                        minlength: 4,
                        equalTo: "#password",//这里关联密码的id
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    tel: {
                        required: true,
                    },
                    captcha: {
                        required: true,
                        //ajax验证短信验证码
                        remote: {
                            url: "<?= \yii\helpers\Url::to(['member/validate-sms'])?>",//后台处理程序
                            type: "get",               //数据发送方式
                            //dataType: "json",           //接受数据格式
                            data: {                     //要传递的数据,默认还会传递当前字段的值
                                phone: function() {
                                    return $("#tel").val();
                                }
                            }
                        }
                    },
                    checkcode: "validateCaptcha"//使用自定义验证规则 验证 验证码
				},
                //提示信息
				messages: {
				    username: {
                        required: "请输入您的用户名",
                        minlength: "用户名至少3个字符",
                        maxlength: "用户名不能超过20个字符",
                        remote: "用户名已存在"
                    },
				    password: {
                        required: "请输入密码",
                        minlength: "密码至少4个字符",
                    },
                    confirm_password: {
                        required: "请输入密码",
                        minlength: "密码至少4个字符",
                        equalTo: "两次密码输入不一致",
                    },
                    email: '请输入正确的邮箱',
                    tel: '请输入您的电话号码',
                    captcha: {
                        required: '请输入短信验证码',
                        remote: "验证码错误",
                    },
                    chb: '请先阅读用户协议',
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