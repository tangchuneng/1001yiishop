<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/12 0012
 * Time: 下午 3:24
 */
namespace backend\filters;
use yii\web\ForbiddenHttpException;

class RbacFilter extends \yii\base\ActionFilter{
    public function beforeAction($action)
    {
        //当前访问的路由 $action->uniqueId
        //\Yii::$app->user->can($action->uniqueId);//检查是否有当前路由的权限
        if(!\Yii::$app->user->can($action->uniqueId)){
            //判断用户是否登录,如果未登录则引导用户啊到登录页面
            if(\Yii::$app->user->isGuest){
                //跳转必须执行send方法,确保页面直接跳转,否则该次操作没有被拦截,相当于返回了true
                return $action->controller->redirect(\Yii::$app->user->loginUrl)->send();
            }
            throw new ForbiddenHttpException('对不起.您没有该操作权限');
        }
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }
}