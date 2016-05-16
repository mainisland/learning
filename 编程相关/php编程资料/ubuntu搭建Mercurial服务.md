#ubuntu 搭建Mercurial 服务(nginx)

环境：ubuntu 12.05 ?Mercurial
步骤:
(1)安装nginx 和?Mercurial： sudo apt-get install nginx mercurial
(2)新建仓库目录:sudo mkdir /home/www ? ? ?sudo chmod -R 777 www ? ? mkdir hg;
(3)新建配置文件：用每个项目hgrc或者新建一个hgweb.config文件,内容如下：
[web]
push_ssl = false
allow_push = *
encoding = "UTF-8"
[paths]
/hg = /home/www/hg
(4)重启hg serve: hg serve 或?hg serve -d -a localhost --webdir-conf hgweb.config
(5)配置nginx：
server {
listen 80 default_server;
server_name hg.pengcz.com;
location / {
proxy_pass http://localhost:8000;
}
可能出现的问题：no username supplied (see "hg help config")
解决：vi ./hg/hgrc [ui] username = daxia<daxia@pengcz.com>