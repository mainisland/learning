#mysql常用操作

(1)获取mysql版本和当前日期
select version(),current_date,now();
(2)批量shell批量执行mysql命令
mysql -h 192.168.240.130 -u root -p < /home/www/mysql(文件命令集)
mysql -h 192.168.240.130 -u root -p < /home/www/mysql >daxia ? ?(将执行后的结果放到文件里面)
(3)mysql提供的程序
mysqld 是mysql 服务器
mysqld_safe,mysql.server,mysqld_multi
mysql 是命令行程序
mysqladmin管理客户程序
mysqlcheck 表维护操作
mysqldump和mysqlhotcopy ?备份数据库
mysqlimport导入数据文件
(4)显示支持的存储引擎
show engines;
(5)显示变量值
show variables;
(6)查看服务器变量状态
show status;