<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>填写核对订单信息</title>
    <!--引入CSS文件-->
    <?=\yii\helpers\Html::cssFile('@web/template/style/base.css')?>
    <?=\yii\helpers\Html::cssFile('@web/template/style/global.css')?>
    <?=\yii\helpers\Html::cssFile('@web/template/style/header.css')?>
    <?=\yii\helpers\Html::cssFile('@web/template/style/footer.css')?>
    <?=\yii\helpers\Html::cssFile('@web/template/style/fillin.css')?>
    <!--引入JS文件-->
    <?=\yii\helpers\Html::jsFile('@web/template/js/jquery-1.8.3.min.js')?>
    <?=\yii\helpers\Html::jsFile('@web/template/js/cart2.js')?>

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
        <h2 class="fl"><a href="<?=\yii\helpers\Url::to(['goods/index'])?>"><img src="<?=Yii::getAlias('@web')?>/template/images/logo.png" alt="京西商城"></a></h2>
        <div class="flow fr flow2">
            <ul>
                <li>1.我的购物车</li>
                <li class="cur">2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->

<div style="clear:both;"></div>

<!-- 主体部分 start -->
<form action="<?=\yii\helpers\Url::to(['order'])?>" id="_form" method="post">
<div class="fillin w990 bc mt15">
    <div class="fillin_hd">
        <h2>填写并核对订单信息</h2>
    </div>
    <div class="fillin_bd">
        <!-- 收货人信息  start-->
        <div class="address">
            <h3>收货人信息</h3>
            <div class="address_info">
                <?php foreach ($addresses as $n=>$address):?>
                <p><input type="radio" value="<?=$address->id?>" name="address_id" checked="<?=$n==0?'checked':''?>" /><?=$address->name?>&nbsp;<?=$address->tel?>&nbsp;<?=$address->province?>&nbsp;<?=$address->city?>&nbsp;<?=$address->area?>&nbsp;<?=$address->address?></p>
                <?php endforeach;?>
            </div>
        </div>
        <!-- 收货人信息  end-->

        <!-- 配送方式 start -->
        <div class="delivery">
            <h3>送货方式 </h3>
            <div class="delivery_select">
                <table>
                    <thead>
                    <tr>
                        <th class="col1">送货方式</th>
                        <th class="col2">运费</th>
                        <th class="col3">运费标准</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach (\frontend\models\Order::$delivery as $n=>$d):?>
                    <tr class="<?=$n==1?'cur':''?>" data-price="<?=$d[1]?>">
                        <td><input type="radio" name="delivery_id" value="<?=$n?>" /><?=$d[0]?></td>
                        <td>￥<?=$d[1]?></td>
                        <td><?=$d[2]?></td>
                    </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- 配送方式 end -->

        <!-- 支付方式  start-->
        <div class="pay">
            <h3>支付方式 </h3>
            <div class="pay_select">
                <table>
                    <?php foreach (\frontend\models\Order::$payment as $n=>$d):?>
                    <tr class="<?=$n==1?'cur':''?>">
                        <td class="col1"><input type="radio" name="payment_id" value="<?=$n?>" checked="<?=$n==1?'checked':''?>" /><?=$d[0]?></td>
                        <td class="col2"><?=$d[1]?></td>
                    </tr>
                    <?php endforeach;?>
                </table>

            </div>
        </div>
        <!-- 支付方式  end-->

        <!-- 发票信息 start-->
        <div class="receipt none">
            <h3>发票信息 </h3>
            <div class="receipt_select ">
                <form action="">
                    <ul>
                        <li>
                            <label for="">发票抬头：</label>
                            <input type="radio" name="type" checked="checked" class="personal" />个人
                            <input type="radio" name="type" class="company"/>单位
                            <input type="text" class="txt company_input" disabled="disabled" />
                        </li>
                        <li>
                            <label for="">发票内容：</label>
                            <input type="radio" name="content" checked="checked" />明细
                            <input type="radio" name="content" />办公用品
                            <input type="radio" name="content" />体育休闲
                            <input type="radio" name="content" />耗材
                        </li>
                    </ul>
                </form>

            </div>
        </div>
        <!-- 发票信息 end-->

        <!-- 商品清单 start -->
        <div class="goods">
            <h3>商品清单</h3>
            <table>
                <thead>
                <tr>
                    <th class="col1">商品</th>
                    <th class="col3">价格</th>
                    <th class="col4">数量</th>
                    <th class="col5">小计</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $total_price = 0;
                foreach ($goods as $good): $total_price += $good->shop_price * $amount[$good->id]?>
                <tr>
                    <td class="col1"><a href=""><img src="<?='http://admin.yiishop.com'.$good->logo?>" alt="" /></a>
                        <strong><a href=""><?=$good->name?></a></strong>
                    </td>
                    <td class="col3">￥<?=$good->shop_price?></td>
                    <td class="col4"><?=$amount[$good->id]?></td>
                    <td class="col5"><span>￥<?=$good->shop_price * $amount[$good->id]?></span></td>
                </tr>
                <?php endforeach;?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <ul>
                            <li id="total_price" data-price="<?=$total_price?>">
                                <?php
                                $total = 0;
                                foreach ($amount as $no){$total += $no;}
                                ?>
                                <span><?=$total?> 件商品，总商品金额：</span>
                                <em>￥<?=$total_price?></em>
                            </li>
                            <li>
                                <span>返现：</span>
                                <em>-￥0.00</em>
                            </li>
                            <li id="delivery-price">
                                <span>运费：</span>
                                <em>￥0.00</em>
                            </li>
                            <li id="total_price1">
                                <span>应付总额：</span>
                                <em>￥</em>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- 商品清单 end -->
    </div>
    <div class="fillin_ft">
        <input id="submit_price" type="hidden" name="total"/>
        <p><input type="submit" value="提交订单"/></p>
        <!--使用a标签提交form表单-->
        <!--<a href="" onclick="javascript:document.getElementById('_form').submit();"></a>-->
        <p>应付总额：<strong id="total_price2">￥元</strong></p>
    </div>
    </form>
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
    $.getJSON("<?=\yii\helpers\Url::to(['member/user-status'])?>",function (json) {
        if(json.isLogin){
            $("#user_status").html("欢迎&nbsp[" + json.name + "]&nbsp<a href='<?=\yii\helpers\Url::to(['member/logout'])?>'>注销</a>");
        }
    });

    //输出配送方式的价格
    $("input[name = 'delivery_id']").click(function () {
        //console.log(this);
        var delivery_price = parseInt($(this).closest('tr').attr('data-price'));
        $("#delivery-price em").text('￥'+ delivery_price);
        var totalPrice = parseInt($("#total_price").attr('data-price'));
        console.debug(delivery_price+totalPrice);

        $("#total_price1 em").text('￥'+ (totalPrice + delivery_price));
        $("#total_price2").text('￥'+ (totalPrice + delivery_price));
        $("#submit_price").val(totalPrice + delivery_price);
    });
</script>
</body>
</html>
