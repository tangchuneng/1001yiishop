<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/10 0010
 * Time: 下午 6:49
 */
namespace backend\controllers;

use backend\models\RoleForm;
use yii\web\Controller;
use backend\models\PermissionForm;

class RbacController extends Controller{

    //>>添加权限
    public function actionAddPermission(){
        $model = new PermissionForm();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $auth = \Yii::$app->authManager;
                //添加权限
                //1.创建权限
                $permission = $auth->createPermission($model->name);
                $permission->description = $model->description;//添加描述
                //2.保存到数据表
                $auth->add($permission);
                \Yii::$app->session->setFlash('success','权限添加成功');
                return $this->redirect('permission-index');
            }
        }
        return $this->render('permission',['model'=>$model]);
    }
    //>>权限列表
    public function actionPermissionIndex(){
        //获取所有权限数据
        $permissions = \Yii::$app->authManager->getPermissions();
        //分配数据,渲染视图
        return $this->render('permissionIndex',['permissions'=>$permissions]);
    }

    //>>添加角色
    public function actionAddRole(){
        $model = new RoleForm();
        $model->scenario = RoleForm::SCENARIO_ADD;//指定当前方法的使用场景是SCENARIO_ADD场景.
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $auth = \Yii::$app->authManager;
                //创建角色
                $role = $auth->createRole($model->name);
                //添加描述
                $role->description = $model->description;
                //保存到数据表
                $auth->add($role);
                //给角色分配权限
                if($model->permissions){
                    //$model->permissions = ['user/add','user/index']  |  null
                    foreach ($model->permissions as $permissionName){
                        //遍历出来的是一个字符串,要根据字符串找到对应的权限对象
                        $permission = $auth->getPermission($permissionName);
                        $auth->addChild($role,$permission);
                    }
                }
                \Yii::$app->session->setFlash('success','角色添加成功');
                return $this->redirect('role-index');
            }
        }
        return $this->render('role',['model'=>$model]);
    }

    //>>修改角色
    public function actionEditRole($name){
        $auth = \Yii::$app->authManager;
        //1.显示表单(回显数据)
        //1.1根据主键获取数据
        $role = $auth->getRole($name);
        //1.2实例化表单模型(活动记录)
        $model = new RoleForm();
        $model->scenario = RoleForm::SCENARIO_EDIT;//指定当前方法的使用场景是SCENARIO_EDIT场景.
        //1.3调用视图,分配数据
        $model->name = $role->name;
        $model->description = $role->description;
        //1.4获取角色关联的权限
        $permissionsName = array_keys($auth->getPermissionsByRole($name));
        //var_dump(array_keys($permissions));exit;
        $model->permissions = $permissionsName;

        //2.接收表单数据
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //3.验证数据,保存到数据表
                $role->name = $model->name;
                $role->description = $model->description;
                $auth->update($name,$role);
                //4.接收表单传过来的权限选项
                //4.1清楚角色的所有权限
                $auth->removeChildren($role);
                //4.2重新赋予权限
                if($model->permissions){
                    //$model->permissions = ['user/add','user/index']  |  null
                    foreach ($model->permissions as $permissionName){
                        //遍历出来的是一个字符串,要根据字符串找到对应的权限对象
                        $permission = $auth->getPermission($permissionName);
                        $auth->addChild($role,$permission);
                    }
                }
                \Yii::$app->session->setFlash('success','角色修改成功');
                return $this->redirect(['role-index']);
            }
        }

        //回显表单
        return $this->render('role',['model'=>$model]);
    }

    //>>角色列表
    public function actionRoleIndex(){
        //获取所有角色数据
        $roles = \Yii::$app->authManager->getRoles();

        //分配数据,渲染视图
        return $this->render('roleIndex',['roles'=>$roles]);
    }


    //>>练习时候的代码,用作参考
    public function Study(){
        //另个用户: admin zhangsan
        //两个角色: 超级管理员 前台
        //两个权限: 添加用户,用户列表
        //超级管理员: [添加用户 用户列表]  前台: [用户列表]
        //admin:超级管理员   zhangsan:前台
        //所有RBAC操作都不需要直接操作数据表,都是通过authManager组件提供的方法来执行
        $auth = \Yii::$app->authManager;
        //1.添加角色
        //1.1新建角色
        $role1 = $auth->createRole('超级管理员');
        $role2 = $auth->createRole('前台');
        //1.2保存到数据表
        $auth->add($role1);
        $auth->add($role2);

        //2.添加权限
        //2.1新建权限
        $permission1 = $auth->createPermission('rbac/add');//权限名直接使用路由
        $permission2 = $auth->createPermission('rbac/user-index');//权限名直接使用路由
        //2.2保存到数据表
        $auth->add($permission1);
        $auth->add($permission2);
        //3.给角色分配权限
        $auth->addChild($role1,$permission1);//角色 权限
        $auth->addChild($role1,$permission2);//角色 权限
        $auth->addChild($role2,$permission2);//角色 权限
        //4.给用户分配角色
        $auth->assign($role1,1);//角色 用户ID
        $auth->assign($role1,2);//角色 用户ID
        $auth->assign($role2,3);//角色 用户ID
        echo '权限分配成功!';

        //补充方法:判断角色和权限是否存在,就是获取角色和权限
        //获取角色
        $role = $auth->getRole('超级管理员');
        //获取权限
        $permission = $auth->getPermission('rbac/add');
        //移除角色的权限
        $auth->removeChild($role1,$permission2);
        //取消用户的角色
        $auth->revoke($role1,2);
    }
}