#php代码优化建议

(1) 函数可以静态声明，就使用static静态化；

(2)echo 比print快

(3)使用echo多重参数，代替连接符(句点)

(4)注销不要用的大对象

(5)避免使用__get ,__set,__autoload

(6)require_once限制使用

(7)包含文件时使用完整路径，解析时间会减少

(8)使用函数替代正则表达式

(9)使用switch case 好于多个if else if

(10)避免使用@

(11)$row['id'] 比$row[id] 快

(12)不要在for循环中使用函数for($x=0;$x<count($array);$x)

(13)使用单引号替代双引号