#02.Yii��������

```
<?php
return array(
'basePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..', //��ǰӦ�ø�Ŀ¼·��
'name' => '�����̳�', //Ӧ������
'preload' => array('log'), //Ԥ����
'import' => array(
'application.models.*', //����application/models����ģ����
'application.components.*', //�������е�Ӧ�������
),
'defaultController' => 'index', //Ĭ�ϵĿ�������
'components' => array( //��ǰӦ���������
'user' => array(
'allowAutoLogin' => true, //�����Զ���¼
),
'cache' => array( //�������
'class' => '', //���������
'servers' => '', //����������
),
'db' => array( //���ݿ��������
'connectionString' => 'mysql:host=localhost;dbname=daxia', //����mysql
'emulatePrepare' => true,
'username' => 'root', //�û���
'password' => '', //����
'charset' => 'utf8', //���ݿ�����ʽ
'tablePrefix' => 'dx_', //����ǰ׺
),
'urlManager' => array( //·�ɹ������
'urlFormat' => 'path', //url·�ɸ�ʽ,path��get��ʽ
'rules' => array(
),
),
'log' => array( //��־��¼���
'class' => 'CLogRouter',
'routes' => array(
array(
'class'=>'CFileLogRoute',
'levels'=>'error, warning',
),
),
),
),
'params' => array(), //Ӧ�ò����,ʹ��Yii::app()->params[''] ����
);
```