memcache session��������

��������̨ubuntu 12.04.5�����������װphp-fpm����������֮ǰ��ļ򵥵ĸ��ؾ���
u1(192.168.240.130) ? ?u2(192.168.240.129) ? ?u3(192.168.240.131)
Ŀǰֻ��u3��װ��memcache��
(1)ubuntu��װphp5-fpm ? ��memcached
sudo apt-get install php5-fpm ? sudo apt-get install memcached
(2)nginx������(�ο�֮ǰ�ĸ��ؾ�������ã�ֻ�������м�����php��֧��)
(3)�޸�/etc/php5/fpm/php.ini������
?session.save_handler?=?memcache
?session.save_path?=?"tcp://192.168.240.131:11211" ; ?(ע�⣺������д��װ��memcache��ip��ַ������Ǳ����Ļ���Ҳ��IP��ַ����ʽ����Ҫ��127.0.0.1)
(5)��д�����ļ�
session_start();
var_dump($_SESSION);

���⣺ע��memcache������ʽ(������������ܷ���)
�����memcached -u memcached -d -m 30 -l?127.0.0.1?-p 11211 ? ����ɫ���ֻ���IP��ַ192.168.240.131

���⣺memcache ����
/etc/init.d/memcached restart
telnet 127.0.0.1 11211