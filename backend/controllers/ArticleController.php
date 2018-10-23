<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleDetail;
use yii\data\Pagination;

class ArticleController extends \yii\web\Controller
{
    //>>文章列表
    public function actionIndex()
    {
        //实例化一个查询器(简化写法)
        $query = Article::find();

        //判断是否有搜索条件
        $data = \Yii::$app->request->get('articleSearchForm');
        //var_dump($data);exit;
        if(!empty($data['name'])){
            $query->andWhere(['like','name',$data['name']]);
        }
        if(!empty($data['intro'])){
            $query->andWhere(['like','intro',$data['intro']]);
        }

        //当前页码数(get参数)
        //实例化分页工具类(主要用来获取分页的数据)
        $pager = new Pagination([
            'totalCount' => $query->count(),//总共多少条
            'defaultPageSize' => 5,//每页多少条
        ]);
        //根据分页工具类获取数据
        //limit 0(offset偏移量),10(limit)
//        $model = $query->all();
        $model = $query->limit($pager->limit)->offset($pager->offset)->all();
        //显示到页面
        return $this->render('index',['model'=>$model,'pager'=>$pager,'data'=>$data]);
    }

    //>>添加文章
    public function actionAdd(){
        $model = new Article();
        $detail = new ArticleDetail();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            $model->create_time = time();
            $detail->load($request->post());
            //var_dump($model->validate());exit;
            if($model->validate() && $detail->validate()){
                $model->save();
                $detail->article_id = $model->id;
                $detail->save();
                \Yii::$app->session->setFlash('success','文章添加成功');
                return $this->redirect(['article/index']);
            }else{
                //return $model->getErrors();
            }
        }
        return $this->render('add',['model'=>$model,'detail'=>$detail]);
    }

    //>>修改文章
    public function actionEdit($id){
        $model = Article::findOne($id);
        $detail = ArticleDetail::findOne($id);
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            $detail->load($request->post());
            //var_dump($model->validate());exit;
            if($model->validate() && $detail->validate()){
                $model->save();
                $detail->article_id = $model->id;
                $detail->save();
                \Yii::$app->session->setFlash('success','文章添加成功');
                return $this->redirect(['article/index']);
            }else{
                //return $model->getErrors();
            }
        }
        return $this->render('add',['model'=>$model,'detail'=>$detail]);
    }

    //>>删除文章
    public function actionDel(){
        //ajax是通过post方式传值的,所以需要在post中获取id值
        $id = \Yii::$app->request->post('id');
        //根据id删除数据
        $model = Article::findOne($id);
        $model->is_delete = 0;
        if($model->save()){
            return 'success';
        }
        return 'fail';
    }

    //>>
    public function actions()
    {
        return [
            //配置UEditor,文件上传相关配置
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => "http://admin.yiishop.com",//图片访问路径前缀
                    "imagePathFormat" => "/upload/article/{yyyy}{mm}{dd}/{time}{rand:6}" ,//上传保存路径
                    "imageRoot" => \Yii::getAlias("@webroot"),
                ],
            ]
        ];
    }
}
