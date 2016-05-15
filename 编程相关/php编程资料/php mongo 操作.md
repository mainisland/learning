#mongodb 的操作和类
**mongodb insert 和save的比较**

insert仅仅是插入文档到集合中，如果记录不存在则插入，如果记录存在则忽略
save是在文档不存在时插入，存在时则是更新

    foreach ($menses['page'] as $k => $value) {
                if(isset($value['id'])) {
                    if(isset($value['is_del']) && intval($value['is_del']) === 1) {
                        //删除操作
                        $pull_date = array("menses.page"=> array("id" => $value['id']));
                        $this->update($where,$pull_date,true,'$pull');
                    } else {
                        //修改
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
                    //添加
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

mongodb和mysql的数据流程比较：

数据库模式：用户建立连接-数据库引擎接受-数据库引擎解析sql语法-映射数据模型以及关系-建模并IO插入数据库存储文件
NoSql模式：用户建立连接-NoSql引擎接受-建模IO插入文件

mongo适用于以下场景：
  a.网站数据：mongo非常适合实时的插入，更新与查询，并具备网站实时数据存储所需的复制及高度伸缩性。
  b.缓存：由于性能很高，mongo也适合作为信息基础设施的缓存层。在系统重启之后，由mongo搭建的持久化缓存可以避免下层的数据源过载。
  c.大尺寸、低价值的数据：使用传统的关系数据库存储一些数据时可能会比较贵，在此之前，很多程序员往往会选择传统的文件进行存储。
  d.高伸缩性的场景：mongo非常适合由数十或者数百台服务器组成的数据库。
  e.用于对象及JSON数据的存储：mongo的BSON数据格式非常适合文档格式化的存储及查询。
不适合的场景：
  a.高度事物性的系统：例如银行或会计系统。传统的关系型数据库目前还是更适用于需要大量原子性复杂事务的应用程序。
  b.传统的商业智能应用：针对特定问题的BI数据库会对产生高度优化的查询方式。对于此类应用，数据仓库可能是更合适的选择。
  c.需要SQL的问题。




MySQL

MongoDB

说明

mysqld	mongod	服务器守护进程
mysql	mongo	客户端工具
mysqldump	mongodump	逻辑备份工具
mysql	mongorestore	逻辑恢复工具
db.repairDatabase()	修复数据库
mysqldump	mongoexport	数据导出工具
source	mongoimport	数据导入工具
grant * privileges on *.* to …	Db.addUser()
Db.auth()

新建用户并权限
show databases	show dbs	显示库列表
Show tables	Show collections	显示表列表
Show slave status	Rs.status	查询主从状态
Create table users(a int, b int)	db.createCollection("mycoll", {capped:true,
size:100000}) 另：可隐式创建表。

创建表
Create INDEX idxname ON users(name)	db.users.ensureIndex({name:1})	创建索引
Create INDEX idxname ON users(name,ts DESC)	db.users.ensureIndex({name:1,ts:-1})	创建索引
Insert into users values(1, 1)	db.users.insert({a:1, b:1})	插入记录
Select a, b from users	db.users.find({},{a:1, b:1})	查询表
Select * from users	db.users.find()	查询表
Select * from users where age=33	db.users.find({age:33})	条件查询
Select a, b from users where age=33	db.users.find({age:33},{a:1, b:1})	条件查询
select * from users where age<33	db.users.find({'age':{$lt:33}})	条件查询
select * from users where age>33 and age<=40	db.users.find({'age':{$gt:33,$lte:40}})	条件查询
select * from users where a=1 and b='q'	db.users.find({a:1,b:'q'})	条件查询
select * from users where a=1 or b=2	db.users.find( { $or : [ { a : 1 } , { b : 2 } ] } )	条件查询
select * from users limit 1	db.users.findOne()	条件查询
select * from users where name like "%Joe%"	db.users.find({name:/Joe/})	模糊查询
select * from users where name like "Joe%"	db.users.find({name:/^Joe/})	模糊查询
select count(1) from users	Db.users.count()	获取表记录数
select count(1) from users where age>30	db.users.find({age: {'$gt': 30}}).count()	获取表记录数
select DISTINCT last_name from users	db.users.distinct('last_name')	去掉重复值
select * from users ORDER BY name	db.users.find().sort({name:-1})	排序
select * from users ORDER BY name DESC	db.users.find().sort({name:-1})	排序
EXPLAIN select * from users where z=3	db.users.find({z:3}).explain()	获取存储路径
update users set a=1 where b='q'	db.users.update({b:'q'}, {$set:{a:1}}, false, true)	更新记录
update users set a=a+2 where b='q'	db.users.update({b:'q'}, {$inc:{a:2}}, false, true)	更新记录
delete from users where z="abc"	db.users.remove({z:'abc'})	删除记录
db. users.remove()	删除所有的记录
drop database IF EXISTS test;	use test
db.dropDatabase()

删除数据库
drop table IF EXISTS test;	db.mytable.drop()	删除表/collection
db.addUser(‘test’, ’test’)	添加用户
readOnly-->false

db.addUser(‘test’, ’test’, true)	添加用户
readOnly-->true

db.addUser("test","test222")	更改密码
db.system.users.remove({user:"test"})
或者db.removeUser('test')

删除用户
use admin	超级用户
db.auth(‘test’, ‘test’)	用户授权
db.system.users.find()	查看用户列表
show users	查看所有用户
db.printCollectionStats()	查看各collection的状态
db.printReplicationInfo()	查看主从复制状态
show profile	查看profiling
db.copyDatabase('mail_addr','mail_addr_tmp')	拷贝数据库
db.users.dataSize()	查看collection数据的大小
db. users.totalIndexSize()	查询索引的大小


snapshot()快照保证没有重复数据返回或对象丢失