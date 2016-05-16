#mysql的简单主从复制

环境：两台ubuntu 12.04.5 虚拟机 ? ?mysql-server-5.5
master (192.168.240.130)
slave (192.168.240.129)
(1)查看二进制日志是否打开
show variables like 'log_bin';
(2)登录master，开个数据库账户
GRANT REPLICATION SLAVE ON *.* TO 'daxia'@'192.168.240.129' IDENTIFIED BY '123456';
(3)修改master ?my.cnf文件
server-id?????????????? = 1??? #主机标示，整数
log_bin???????????????? = /var/log/mysql/mysql-bin.log?? #确保此文件可写，开启bin-log
read-only????????????? =0? #读写都可以，也可以不写
binlog-do-db???????? =daixa ?#需要备份数据，多个写多行，也可以没有
binlog-ignore-db??? =mysql #不需要备份的数据库，多个写多行，也可以没有
(4)查看主机状态
show master status;
(5)配置slave
log_bin?????????? =??/var/log/mysql/mysql-bin.log
server_id???????? = 2
log_slave_updates = 1 ?#将日志写入从服务器的二进制日志
read_only???????? = 1 ?#只读
(6)让slave链接master (这部分的配置不要放到my.cnf里面)
mysql> CHANGE MASTER TO MASTER_HOST='192.168.240.130',
-> MASTER_USER='daxia',
-> MASTER_PASSWORD='123456',
-> MASTER_LOG_FILE='mysql-bin.000001',
-> MASTER_LOG_POS=0;
MASTER_LOG_POS的值为0，因为它是日志的开始位置
(7)验证是否slave是否开启
SHOW SLAVE STATUS\G ? ? ? ? ? ? 如果没有开启可以使用 ?start ?slave；
(8)查看主从的I/O
show processlist \G
?
问题描述
(1)主从不同步的问题;
解决:master是没有问题,查看slave ?show slave status\G
Slave_IO_Running: Yes
Slave_SQL_Running: No
解决: stop slave;
#表示跳过一步错误，后面的数字可变
set global sql_slave_skip_counter =1;
start slave;