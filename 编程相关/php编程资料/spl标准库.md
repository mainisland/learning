#SPL标准库

###SPL数据结构

SplDoublyLinkedList：双向链表
SplStack：堆
SplQueue：队列
SplFixedArray：定长php数组


###SPL迭代器
AppendIterator：按顺序访问几个不同的迭代器
	$a = new ArrayIterator(array('a','b','c'));
    $b = new ArrayIterator(array('e','d','f'));
    $iterator = new AppendIterator;
    $iterator->append($a);
    $iterator->append($b);
    //$iterator->current();
    //$iterator->getArrayIterator();
    //$iterator->getInnerIterator();
    foreach ($iterator as $key => $value) {
	   echo $value."\n";
    }

ArrayIterator:数组迭代器
	$a = array(
        'name' => 'daxia',
        'age'  => '10'
    );
    $a = new ArrayIterator($a);
    $a->append(array(
        'address' => '123',
    ));
	
CachingIterator：缓存迭代另一个迭代器
	$iterator = new ArrayIterator(array(1,2,3));
    $cache = new CachingIterator($iterator, CachingIterator::FULL_CACHE);
    $cache->next();
    var_dump($cache->getCache());
	
CallbackFilterIterator:同时执行过滤和回调操作

DirectoryIterator：目录文件迭代器

EmptyIterator：类占位符迭代器，不执行任何操作

FilesystemIterator：文件迭代器，继承DirectoryIterator

FilterIterator：过滤数据，必须实现accept()抽象方法

GlobIterator：带匹配模式的文件遍历器

InfiniteIterator：无限循环访问迭代器，当到达末尾时，自动重头遍历

IteratorIterator：通用类型迭代器，可以自定义迭代器

LimitIterator：限定迭代器，限定遍历的元素

MultipleIterator：依次遍历所有连接迭代的迭代器

NoRewindIterator：不能多次迭代，只能一次性

RecursiveArrayIterator：创建一个用于递归形式数组结构迭代器，类似多维数组

###常用的SPL函数
iterator_to_array()  将迭代器中的元素拷贝到数组
iterator_count() 统计迭代器的元素个数
spl_autoload()  __autoload()函数