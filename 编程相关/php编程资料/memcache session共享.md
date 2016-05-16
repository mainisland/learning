memcache session共享问题

环境：三台ubuntu 12.04.5虚拟机，均安装php-fpm，并重用了之前搭建的简单的负载均衡
u1(192.168.240.130) ? ?u2(192.168.240.129) ? ?u3(192.168.240.131)
目前只有u3安装了memcache；
(1)ubuntu安装php5-fpm ? 和memcached
sudo apt-get install php5-fpm ? sudo apt-get install memcached
(2)nginx的配置(参考之前的负载均衡的配置，只在配置中加入了php的支持)
(3)修改/etc/php5/fpm/php.ini的配置
?session.save_handler?=?memcache
?session.save_path?=?"tcp://192.168.240.131:11211" ; ?(注意：这里填写安装了memcache的ip地址，如果是本机的话，也用IP地址的形式，不要用127.0.0.1)
(5)编写测试文件
session_start();
var_dump($_SESSION);

问题：注意memcache开启方式(让其他虚拟机能访问)
解决：memcached -u memcached -d -m 30 -l?127.0.0.1?-p 11211 ? 将红色部分换成IP地址192.168.240.131

问题：memcache 重启
/etc/init.d/memcached restart
telnet 127.0.0.1 11211