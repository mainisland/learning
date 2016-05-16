#mysql server搭建

环境：ubuntu 12.04.5 ? ?mysql-server-5.5
安装：sudo apt-get install mysql-server-5.5 (服务端 第一台虚拟机)
sudo apt-get install mysql-client-5.5 ?(客户端 第二台虚拟机)
客户端访问服务端遇到的问题：
(1)ERROR 2003 can't ?content to mysql server
解决： sudo vi /etc/mysql/my.cnf ? ?注释掉?#bind-address? ? ? ? ? = 127.0.0.1
(2)ubuntu下mysql服务的重启和关闭
解决：sudo service mysql ??start|stop|restart?? ? ?sudo /etc/init.d/mysql ?start|stop|restart
(3)重启之后还有问题,ERROR 1130 host is not allowed to content this mysql server
解决：GRANT?ALL?PRIVILEGES?ON?*.*?TO?'root'@'%'?IDENTIFIED?BY?'123456'?WITH?GRANT?OPTION;