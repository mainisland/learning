ubuntu搭建svn服务器

环境:ubuntu 12.04.5
?apt-get install subversion
?找个目录作为svn的仓库 mkdir svn svnadmin create svn/phpcms
?修改svnserve.conf文件 打开或者新增 anon-access = read auth-access = write
password-db = passwd
authz-db = authz
?修改passwd 增加用户名和密码: [user] username=password //用户可以自己填写username和password
?修改authz 用于解决客户端svn认证失败的问题 [/]*= rw
?重启svn:svnserve -d -r /opt/svn