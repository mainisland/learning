#Yaf���һЩϸ��

(1)Yaf_Request_Abstract��getPost, getQuery�ȷ���, ��û�ж�Ӧ��setter����. ������Щ������ֱ�Ӵ�PHP�ڲ���$_POST, $_GET�ȴ������ԭ�����ֻ���Ĳ�ѯֵ, ���Ծ���һ������:ͨ����PHP�ű��ж���Щ�������޸�, �����ܷ�ӳ��getPost/getQuery�ȷ�����

    <?php
    class Index_Controller extends Yaf_Controller_Abstract {
        public function indexAction() {
            $_POST['name'] = "new_name";
            //��ʱ��POST���޸�, �����ܷ�ӳ��getPost��
            echo  $this->getRequest()->getPost("name"); //old_name
        }
	}