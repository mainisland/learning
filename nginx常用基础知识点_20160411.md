5、配置HTTPS主机

6、rewrite重写

7、nginx location的优先级

if (!-e $request_filename) { 
	rewrite ^(.*)$ /index.php last; 
} 
放入到location /里面，可能.php结尾的可能无法访问，如果将该段放入到sever里面就可以解决该问题；
因为：nginx的匹配规则是如果能匹配location ~ .*\.php(\/.*)*$ 匹配之后就结束，不在匹配localhost /
[参见](http://tengine.taobao.org/nginx_docs/cn/docs/)
