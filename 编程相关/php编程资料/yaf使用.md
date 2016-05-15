#Yaf框架一些细节

(1)Yaf_Request_Abstract的getPost, getQuery等方法, 并没有对应的setter方法. 并且这些方法是直接从PHP内部的$_POST, $_GET等大变量的原身变量只读的查询值, 所以就有一个问题:通过在PHP脚本中对这些变量的修改, 并不能反映到getPost/getQuery等方法上

    <?php
    class Index_Controller extends Yaf_Controller_Abstract {
        public function indexAction() {
            $_POST['name'] = "new_name";
            //此时对POST的修改, 并不能反映到getPost上
            echo  $this->getRequest()->getPost("name"); //old_name
        }
	}