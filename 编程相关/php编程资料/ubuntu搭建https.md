ubuntu ����׵�https��վ

������ubuntu 12.04.5 openssl
(1)����һ��ssl�ı���·�� sudo mkdir /opt/nginx/ssl
(2)������Կsudo openssl genrsa -out key.pem 2048
(3)sudo openssl req -new -x509 -nodes -out server.crt -keyout server.key
(4)����nginx
server {
listen 443;
index index.html index.php;
root /home/www;
ssl on;
ssl_certificate ?/opt/nginx/ssl/server.crt;
ssl_certificate_key /opt/nginx/ssl/server.key;
}