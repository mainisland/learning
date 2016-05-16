nginx简单反向代理与负载均衡

环境：三台ubuntu 12.04.5 虚拟机 ? ?均装有nginx 1.1.19
以下u1(192.168.240.129) ,u2(192.168.240.130),u3(192.168.240.131)代表三台虚拟机
简单的反向代理配置：(u1反向到u2)
u1的配置：
server {
listen 80 default_server;
server_name ?a.jh.net;
index index.html index.php
root /home/www/a;
location / {
proxy_pass http://192.168.240.130:80;
}
}
u2的配置：
server ?{
listen 80 default_server;
server_name b.jh.net;
index index.html index.php
root /home/www/b;
}
简单的负载均衡的配置
u1的配置：
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
其余配置保持原来不变；
所遇到的问题：
(1)浏览器输入ip地址不能总是出现welcome to nginx
解决：删除nginx的默认配置，然后localhost写入到server_name 中；