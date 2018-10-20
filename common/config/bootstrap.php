<?php
// __DIR__ 是入口文件的绝对路径,所以别名所指向的路径都是相对于入口文件的路径
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@Aliyun', dirname(dirname(__DIR__)) . '/aliyun');
