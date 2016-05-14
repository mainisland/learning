#php面向对象知识总结

### 1.类和对象的基本概念；使用new.使用$this->property访问非静态;使用self::$property访问静态

### 2.构造方法__construct 和析构方法__destruct()

### 3.访问控制符public,protect,private比较

### 4.基本的魔术方法__get __set __isset __unset

### 5.extends类的继承

### 6.类的重写：parent::method();

### 7.final关键词：final标识的类不能继承，final标识的方法不能覆盖

### 8.静态关键词static：不能实例化而直接访问，不能使用$this,静态属性不用使用->访问

### 9.抽象方法和抽象类：类中有抽象方法类必须是抽象类，子类必须实现父类的抽象方法，访问控制相同

### 10.自动加载类：__autoload(),推荐使用spl_autoload_register()

### 11.接口(interface)和实现(implements)：