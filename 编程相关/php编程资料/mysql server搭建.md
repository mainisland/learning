#mysql server�

������ubuntu 12.04.5 ? ?mysql-server-5.5
��װ��sudo apt-get install mysql-server-5.5 (����� ��һ̨�����)
sudo apt-get install mysql-client-5.5 ?(�ͻ��� �ڶ�̨�����)
�ͻ��˷��ʷ�������������⣺
(1)ERROR 2003 can't ?content to mysql server
����� sudo vi /etc/mysql/my.cnf ? ?ע�͵�?#bind-address? ? ? ? ? = 127.0.0.1
(2)ubuntu��mysql����������͹ر�
�����sudo service mysql ??start|stop|restart?? ? ?sudo /etc/init.d/mysql ?start|stop|restart
(3)����֮��������,ERROR 1130 host is not allowed to content this mysql server
�����GRANT?ALL?PRIVILEGES?ON?*.*?TO?'root'@'%'?IDENTIFIED?BY?'123456'?WITH?GRANT?OPTION;