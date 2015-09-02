# linux第一课
## 讲笔记总结一下

###常用命令

(1)查看系统类型：cat /proc/version    lsb_release -a      cat  /etc/issue

(2)查看软件安装路径：ps aux | grep nginx

(3)查看被打开的端口： netstat -tnap

(4)查看软件的安装路径： whereis  nginx 

###Ubuntu安装svn服务

* apt-get install subversion

* 找个目录作为svn的仓库    mkdir svn   svnadmin create svn/phpcms

* 修改svnserve.conf文件 打开或者新增
      anon-access = read
      auth-access = write     
      password-db = passwd   
      authz-db = authz
* 修改passwd   增加用户名和密码:
    [user]
    username=password    //用户可以自己填写username和password
* 修改authz   用于解决客户端svn认证失败的问题
    [/]
    * = rw
* 重启svn:svnserve -d -r /opt/svn