#02.Yii常用配置

```
<?php
return array(
'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..', //当前应用根目录路径
'name' => '大侠商城', //应用名称
'preload' => array('log'), //预加载
'import' => array(
'application.models.*', //加载application/models所有模型类
'application.components.*', //加载所有的应用组件类
),
'defaultController' => 'index', //默认的控制器类
'components' => array( //当前应用组件配置
'user' => array(
'allowAutoLogin' => true, //允许自动登录
),
'cache' => array( //缓存组件
'class' => '', //缓存组件类
'servers' => '', //服务器配置
),
'db' => array( //数据库组件配置
'connectionString' => 'mysql:host=localhost;dbname=daxia', //连接mysql
'emulatePrepare' => true,
'username' => 'root', //用户名
'password' => '', //密码
'charset' => 'utf8', //数据库编码格式
'tablePrefix' => 'dx_', //表名前缀
),
'urlManager' => array( //路由管理组件
'urlFormat' => 'path', //url路由格式,path和get格式
'rules' => array(
),
),
'log' => array( //日志记录组件
'class' => 'CLogRouter',
'routes' => array(
array(
'class'=>'CFileLogRoute',
'levels'=>'error, warning',
),
),
),
),
'params' => array(), //应用层参数,使用Yii::app()->params[''] 访问
);
```