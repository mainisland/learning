#mongodb �Ĳ�������
**mongodb insert ��save�ıȽ�**

insert�����ǲ����ĵ��������У������¼����������룬�����¼���������
save�����ĵ�������ʱ���룬����ʱ���Ǹ���

    foreach ($menses['page'] as $k => $value) {
                if(isset($value['id'])) {
                    if(isset($value['is_del']) && intval($value['is_del']) === 1) {
                        //ɾ������
                        $pull_date = array("menses.page"=> array("id" => $value['id']));
                        $this->update($where,$pull_date,true,'$pull');
                    } else {
                        //�޸�
                        $set_page['menses.page.$.color'] = isset($value['color'])?intval($value['color']):0;
                        $set_page['menses.page.$.time']  = strval($value['time']);
                        if(isset($value['image']) && !empty($value['image'])) {
                            if($value['image'] == 'http://img.seedit.com/image/cms/2015/01/27/9b607411ad7e28f42786501684e3f283907434.jpg') {
                                $set_page['menses.page.$.image'] = '';
                            } else {
                                $set_page['menses.page.$.image'] = strval($value['image']);
                            }
                        } else {
                            $set_page['menses.page.$.image'] = '';
                        }
                        //$set_page['menses.page.$.image']   = isset($value['image'])?strval($value['image']):'';
                        $set_page['menses.page.$.status']  = isset($value['status'])?intval($value['status']):0;
                        $set_page['menses.page.$.timeline'] = time();
                        $where_aa = array("cuid"=>$cuid,"date"=>$menses['date'],"menses.page.id" => $value['id']);
                        $update = $set_page;
                        $this->update($where_aa,$update,true,'$set');
                    }
                } else {
                    //���
                    $add_page['id']    = (string)new MongoId();
                    $add_page['color'] = intval($value['color']);
                    $add_page['time']  = strval($value['time']);
                    if(isset($value['image']) && !empty($value['image'])) {
                        if($value['image'] == 'http://img.seedit.com/image/cms/2015/01/27/9b607411ad7e28f42786501684e3f283907434.jpg') {
                            $add_page['image'] = '';
                        } else {
                            $add_page['image'] = strval($value['image']);
                        }
                    } else {
                        $add_page['image'] = '';
                    }
                    //$add_page['image']   = isset($value['image'])?strval($value['image']):'';
                    $add_page['status']  = isset($value['status'])?intval($value['status']):0;
                    $add_page['timeline'] = time();
                    $update = array("menses.page"=>$add_page);
                    $this->update($where,$update,true,'$push',true);
                }
            }

mongodb��mysql���������̱Ƚϣ�

���ݿ�ģʽ���û���������-���ݿ��������-���ݿ��������sql�﷨-ӳ������ģ���Լ���ϵ-��ģ��IO�������ݿ�洢�ļ�
NoSqlģʽ���û���������-NoSql�������-��ģIO�����ļ�

mongo���������³�����
  a.��վ���ݣ�mongo�ǳ��ʺ�ʵʱ�Ĳ��룬�������ѯ�����߱���վʵʱ���ݴ洢����ĸ��Ƽ��߶������ԡ�
  b.���棺�������ܸܺߣ�mongoҲ�ʺ���Ϊ��Ϣ������ʩ�Ļ���㡣��ϵͳ����֮����mongo��ĳ־û�������Ա����²������Դ���ء�
  c.��ߴ硢�ͼ�ֵ�����ݣ�ʹ�ô�ͳ�Ĺ�ϵ���ݿ�洢һЩ����ʱ���ܻ�ȽϹ��ڴ�֮ǰ���ܶ����Ա������ѡ��ͳ���ļ����д洢��
  d.�������Եĳ�����mongo�ǳ��ʺ�����ʮ��������̨��������ɵ����ݿ⡣
  e.���ڶ���JSON���ݵĴ洢��mongo��BSON���ݸ�ʽ�ǳ��ʺ��ĵ���ʽ���Ĵ洢����ѯ��
���ʺϵĳ�����
  a.�߶������Ե�ϵͳ���������л���ϵͳ����ͳ�Ĺ�ϵ�����ݿ�Ŀǰ���Ǹ���������Ҫ����ԭ���Ը��������Ӧ�ó���
  b.��ͳ����ҵ����Ӧ�ã�����ض������BI���ݿ��Բ����߶��Ż��Ĳ�ѯ��ʽ�����ڴ���Ӧ�ã����ݲֿ�����Ǹ����ʵ�ѡ��
  c.��ҪSQL�����⡣




MySQL

MongoDB

˵��

mysqld	mongod	�������ػ�����
mysql	mongo	�ͻ��˹���
mysqldump	mongodump	�߼����ݹ���
mysql	mongorestore	�߼��ָ�����
db.repairDatabase()	�޸����ݿ�
mysqldump	mongoexport	���ݵ�������
source	mongoimport	���ݵ��빤��
grant * privileges on *.* to ��	Db.addUser()
Db.auth()

�½��û���Ȩ��
show databases	show dbs	��ʾ���б�
Show tables	Show collections	��ʾ���б�
Show slave status	Rs.status	��ѯ����״̬
Create table users(a int, b int)	db.createCollection("mycoll", {capped:true,
size:100000}) ������ʽ������

������
Create INDEX idxname ON users(name)	db.users.ensureIndex({name:1})	��������
Create INDEX idxname ON users(name,ts DESC)	db.users.ensureIndex({name:1,ts:-1})	��������
Insert into users values(1, 1)	db.users.insert({a:1, b:1})	�����¼
Select a, b from users	db.users.find({},{a:1, b:1})	��ѯ��
Select * from users	db.users.find()	��ѯ��
Select * from users where age=33	db.users.find({age:33})	������ѯ
Select a, b from users where age=33	db.users.find({age:33},{a:1, b:1})	������ѯ
select * from users where age<33	db.users.find({'age':{$lt:33}})	������ѯ
select * from users where age>33 and age<=40	db.users.find({'age':{$gt:33,$lte:40}})	������ѯ
select * from users where a=1 and b='q'	db.users.find({a:1,b:'q'})	������ѯ
select * from users where a=1 or b=2	db.users.find( { $or : [ { a : 1 } , { b : 2 } ] } )	������ѯ
select * from users limit 1	db.users.findOne()	������ѯ
select * from users where name like "%Joe%"	db.users.find({name:/Joe/})	ģ����ѯ
select * from users where name like "Joe%"	db.users.find({name:/^Joe/})	ģ����ѯ
select count(1) from users	Db.users.count()	��ȡ���¼��
select count(1) from users where age>30	db.users.find({age: {'$gt': 30}}).count()	��ȡ���¼��
select DISTINCT last_name from users	db.users.distinct('last_name')	ȥ���ظ�ֵ
select * from users ORDER BY name	db.users.find().sort({name:-1})	����
select * from users ORDER BY name DESC	db.users.find().sort({name:-1})	����
EXPLAIN select * from users where z=3	db.users.find({z:3}).explain()	��ȡ�洢·��
update users set a=1 where b='q'	db.users.update({b:'q'}, {$set:{a:1}}, false, true)	���¼�¼
update users set a=a+2 where b='q'	db.users.update({b:'q'}, {$inc:{a:2}}, false, true)	���¼�¼
delete from users where z="abc"	db.users.remove({z:'abc'})	ɾ����¼
db. users.remove()	ɾ�����еļ�¼
drop database IF EXISTS test;	use test
db.dropDatabase()

ɾ�����ݿ�
drop table IF EXISTS test;	db.mytable.drop()	ɾ����/collection
db.addUser(��test��, ��test��)	����û�
readOnly-->false

db.addUser(��test��, ��test��, true)	����û�
readOnly-->true

db.addUser("test","test222")	��������
db.system.users.remove({user:"test"})
����db.removeUser('test')

ɾ���û�
use admin	�����û�
db.auth(��test��, ��test��)	�û���Ȩ
db.system.users.find()	�鿴�û��б�
show users	�鿴�����û�
db.printCollectionStats()	�鿴��collection��״̬
db.printReplicationInfo()	�鿴���Ӹ���״̬
show profile	�鿴profiling
db.copyDatabase('mail_addr','mail_addr_tmp')	�������ݿ�
db.users.dataSize()	�鿴collection���ݵĴ�С
db. users.totalIndexSize()	��ѯ�����Ĵ�С


snapshot()���ձ�֤û���ظ����ݷ��ػ����ʧ