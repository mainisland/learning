#php命名空间(类文件目录结构)

用途：解决用户编写的代码与php内部的类/函数/常量冲突，使用简短的名称提高代码可读性

使用：namespace 定义，只能在声明命名空间之前放declare，否则其他的都会报错；同一个命名空间可以放在多个文件中

定义子命名空间：namespace project\next\next

统一文件定义多个命名空间：使用命名空间包括 namespace {} 

__NAMESPACE__ 常量：当前命名空间名称的字符串

命名空间使用别名：use    列如：use project\next\next as next

全局空间：没有定义任何命名空间和\
