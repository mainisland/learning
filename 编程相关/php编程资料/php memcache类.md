#memcache ʹ��

memcache �ʺϳ�����
    ��1����վ�����������ܴ�̬��������ݿ�ĸ��ؽ���ܸߡ����ڴ󲿷����ݿ������Ƕ���������ômemcached���������ؼ�С���ݿ⸺��
    ��2��������ݿ�������ĸ��رȽϵ͵�CPUʹ���ʺܸߣ���ʱ���Ի������õĽ������Ⱦ�����ҳģ��
    ��3������memcached���Ի��� session���� ����ʱ�����Լ��ٶ����ǵ����ݿ�д����
    ��4������һЩ��С���Ǳ�Ƶ�����ʵ��ļ�
memcache���ʺϳ�����
    ��1���������Ĵ�С����1MB 
    ��2��key�ĳ��ȴ���250�ַ�
    ��3������������������memcached����   
      ���Ӧ�ñ����й��ڵͶ˵�����˽�з������ϣ���vmware, xen�������⻯���������ʺ�����memcached��Memcached��Ҫ�ӹ�            �Ϳ��ƴ����ڴ棬���memcached������ڴ� 
��OS�� hypervisor������ȥ��memcached�����ܽ�����ۿ�
    ��4��Ӧ�������ڲ���ȫ�Ļ����У�����ͨ��telnet�Ϳ��Է��ʵ�memcached�����Ӧ�������ڹ����ϵͳ�ϣ���Ҫ���ؿ��ǰ�ȫ����
    ��5��ҵ������Ҫ���ǳ־û����ݻ���˵��Ҫ��Ӧ����database


memcache���ͣ�
    ����ʱ������Դﵽ30��

memcached ʹ�ã�

����memcached��������   telnet 127.0.0.1 11211

memcache �ࣺ

    <?php
    /**
     * Seedit_Cache
     *
     * @version $Id$
     * @copyright
     */

    /**
     * ������
     *
     */
    class Seedit_Cache {
        var $_memcached;
        var $_app_key;

        public function Seedit_Cache($app_key) {
            $this->_memcached = new Memcached();
            $this->_memcached->addServer(MEMCACHE_HOST, MEMCACHE_PORT);
            $this->_app_key = strtolower($app_key);
        }
        public function set($key,$value,$expiration=0) {
            $key   = $this->_app_key . "_" . substr(md5($key),8,16);
            $this->_memcached->set($key,$value,$expiration);
        }
        public function get($key) {
            $key   = $this->_app_key . "_" . substr(md5($key),8,16);
            $data = $this->_memcached->get($key);
            return $data;
        }
        public function delete($key) {
            $key   = $this->_app_key . "_" . substr(md5($key),8,16);
            $data = $this->_memcached->delete($key);
            return $data;
        }
        /*public function flush() {
            return $this->_memcached->flush();
        }*/
    }