#memcache 使用

memcache 适合场景：
    （1）网站包含访问量很大动态，因而数据库的负载将会很高。由于大部分数据库请求都是读操作，那么memcached可以显著地减小数据库负载
    （2）如果数据库服务器的负载比较低但CPU使用率很高，这时可以缓存计算好的结果和渲染后的网页模板
    （3）利用memcached可以缓存 session数据 、临时数据以减少对他们的数据库写操作
    （4）缓存一些很小但是被频繁访问的文件
memcache不适合场景：
    （1）缓存对象的大小大于1MB 
    （2）key的长度大于250字符
    （3）虚拟主机不让运行memcached服务   
      如果应用本身托管在低端的虚拟私有服务器上，像vmware, xen这类虚拟化技术并不适合运行memcached。Memcached需要接管            和控制大块的内存，如果memcached管理的内存 
被OS或 hypervisor交换出去，memcached的性能将大打折扣
    （4）应用运行在不安全的环境中，仅仅通过telnet就可以访问到memcached。如果应用运行在共享的系统上，需要着重考虑安全问题
    （5）业务本身需要的是持久化数据或者说需要的应该是database


memcache解释：
    过期时间最长可以达到30天

memcached 使用：

连接memcached服务器：   telnet 127.0.0.1 11211

memcache 类：

    <?php
    /**
     * Seedit_Cache
     *
     * @version $Id$
     * @copyright
     */

    /**
     * 缓存类
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