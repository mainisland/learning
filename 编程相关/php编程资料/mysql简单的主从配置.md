#mysql�ļ����Ӹ���

��������̨ubuntu 12.04.5 ����� ? ?mysql-server-5.5
master (192.168.240.130)
slave (192.168.240.129)
(1)�鿴��������־�Ƿ��
show variables like 'log_bin';
(2)��¼master���������ݿ��˻�
GRANT REPLICATION SLAVE ON *.* TO 'daxia'@'192.168.240.129' IDENTIFIED BY '123456';
(3)�޸�master ?my.cnf�ļ�
server-id?????????????? = 1??? #������ʾ������
log_bin???????????????? = /var/log/mysql/mysql-bin.log?? #ȷ�����ļ���д������bin-log
read-only????????????? =0? #��д�����ԣ�Ҳ���Բ�д
binlog-do-db???????? =daixa ?#��Ҫ�������ݣ����д���У�Ҳ����û��
binlog-ignore-db??? =mysql #����Ҫ���ݵ����ݿ⣬���д���У�Ҳ����û��
(4)�鿴����״̬
show master status;
(5)����slave
log_bin?????????? =??/var/log/mysql/mysql-bin.log
server_id???????? = 2
log_slave_updates = 1 ?#����־д��ӷ������Ķ�������־
read_only???????? = 1 ?#ֻ��
(6)��slave����master (�ⲿ�ֵ����ò�Ҫ�ŵ�my.cnf����)
mysql> CHANGE MASTER TO MASTER_HOST='192.168.240.130',
-> MASTER_USER='daxia',
-> MASTER_PASSWORD='123456',
-> MASTER_LOG_FILE='mysql-bin.000001',
-> MASTER_LOG_POS=0;
MASTER_LOG_POS��ֵΪ0����Ϊ������־�Ŀ�ʼλ��
(7)��֤�Ƿ�slave�Ƿ���
SHOW SLAVE STATUS\G ? ? ? ? ? ? ���û�п�������ʹ�� ?start ?slave��
(8)�鿴���ӵ�I/O
show processlist \G
?
��������
(1)���Ӳ�ͬ��������;
���:master��û������,�鿴slave ?show slave status\G
Slave_IO_Running: Yes
Slave_SQL_Running: No
���: stop slave;
#��ʾ����һ�����󣬺�������ֿɱ�
set global sql_slave_skip_counter =1;
start slave;