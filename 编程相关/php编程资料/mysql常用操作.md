#mysql���ò���

(1)��ȡmysql�汾�͵�ǰ����
select version(),current_date,now();
(2)����shell����ִ��mysql����
mysql -h 192.168.240.130 -u root -p < /home/www/mysql(�ļ����)
mysql -h 192.168.240.130 -u root -p < /home/www/mysql >daxia ? ?(��ִ�к�Ľ���ŵ��ļ�����)
(3)mysql�ṩ�ĳ���
mysqld ��mysql ������
mysqld_safe,mysql.server,mysqld_multi
mysql �������г���
mysqladmin����ͻ�����
mysqlcheck ��ά������
mysqldump��mysqlhotcopy ?�������ݿ�
mysqlimport���������ļ�
(4)��ʾ֧�ֵĴ洢����
show engines;
(5)��ʾ����ֵ
show variables;
(6)�鿴����������״̬
show status;