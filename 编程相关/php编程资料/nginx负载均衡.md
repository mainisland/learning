nginx�򵥷�������븺�ؾ���

��������̨ubuntu 12.04.5 ����� ? ?��װ��nginx 1.1.19
����u1(192.168.240.129) ,u2(192.168.240.130),u3(192.168.240.131)������̨�����
�򵥵ķ���������ã�(u1����u2)
u1�����ã�
server {
listen 80 default_server;
server_name ?a.jh.net;
index index.html index.php
root /home/www/a;
location / {
proxy_pass http://192.168.240.130:80;
}
}
u2�����ã�
server ?{
listen 80 default_server;
server_name b.jh.net;
index index.html index.php
root /home/www/b;
}
�򵥵ĸ��ؾ��������
u1�����ã�
upstream a.jh.net {
server 192.168.240.130:80 weight=1;
server 192.168.240.131:80 weight=2;
}
server {
listen 80 default_server;
server_name ?a.jh.net;
index index.html index.php
root /home/www/a;
location / {
proxy_pass http://a.jh.net;
}
}
�������ñ���ԭ�����䣻
�����������⣺
(1)���������ip��ַ�������ǳ���welcome to nginx
�����ɾ��nginx��Ĭ�����ã�Ȼ��localhostд�뵽server_name �У�