ubuntu 搭建简易的https网站

环境：ubuntu 12.04.5 openssl
(1)创建一个ssl的保存路径 sudo mkdir /opt/nginx/ssl
(2)生存密钥sudo openssl genrsa -out key.pem 2048
(3)sudo openssl req -new -x509 -nodes -out server.crt -keyout server.key
(4)配置nginx
server {
listen 443;
index index.html index.php;
root /home/www;
ssl on;
ssl_certificate ?/opt/nginx/ssl/server.crt;
ssl_certificate_key /opt/nginx/ssl/server.key;
}