#ubuntu �Mercurial ����(nginx)

������ubuntu 12.05 ?Mercurial
����:
(1)��װnginx ��?Mercurial�� sudo apt-get install nginx mercurial
(2)�½��ֿ�Ŀ¼:sudo mkdir /home/www ? ? ?sudo chmod -R 777 www ? ? mkdir hg;
(3)�½������ļ�����ÿ����Ŀhgrc�����½�һ��hgweb.config�ļ�,�������£�
[web]
push_ssl = false
allow_push = *
encoding = "UTF-8"
[paths]
/hg = /home/www/hg
(4)����hg serve: hg serve ��?hg serve -d -a localhost --webdir-conf hgweb.config
(5)����nginx��
server {
listen 80 default_server;
server_name hg.pengcz.com;
location / {
proxy_pass http://localhost:8000;
}
���ܳ��ֵ����⣺no username supplied (see "hg help config")
�����vi ./hg/hgrc [ui] username = daxia<daxia@pengcz.com>