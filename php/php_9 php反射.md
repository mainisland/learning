#反射

用途：对类，方法，属性，参数的提取生成文档；自动加载插件

实列化类同于new：
$ref = new ReflectionClass($classname);
$class = $ref->newInstance();  //相当于new $classname;

区别：new 出来的class，不能访问它的私有属性/方法，但反射就可以；
	  反射返回的对象是class的元数据对象(属性/方法)，而不是类本身
