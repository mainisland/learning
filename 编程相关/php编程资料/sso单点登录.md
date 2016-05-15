#php sso单点登录原理阐述

原理：就是用户登录了单点登录系统（sso）之后，就可以免登录形式进入相关系统；
实现：(1)点击登录跳转到SSO登录页面并带上当前应用的callback地址
(2)登录成功后生成COOKIE并将COOKIE传给callback地址
(3)callback地址接收SSO的COOKIE并设置在当前域下再跳回系统到即完成登录
问题：
1.cookie不安全（只能通过加密来解决）
2.cookie不能跨域登录
另外一种方案：可以部署使用ucenter的sso单点登录系统，或者如下图（借鉴别人的）
![sso单点登录](../../img/sso登录.png)
对于这样的设计的话：对于sso系统和其他系统之间可以使用token值作为基本验证，可以考虑增加redis来做缓存登录信息
