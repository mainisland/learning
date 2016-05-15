#mysql的配置和优化

登录MySQL的命令是mysql：       mysql [-u username] [-h host] [-p[password]] [dbname] 

mysql目录：
    数据库目录：/var/lib/mysql/
    配置文件 ：/usr/share/mysql（mysql.server命令及配置文件）
    相关命令 ：/usr/bin(mysqladmin mysqldump等命令)
    启动脚本 ：/etc/rc.d/init.d/（启动脚本文件mysql的目录）

修改mysql的登录密码：  usr/bin/mysqladmin -u root password 'new-password'

mysql启动和停止： /etc/init.d/mysql start      /usr/bin/mysqladmin -u root -p shutdown

mysql自动启动:  /sbin/chkconfig –list     /sbin/chkconfig　– add　mysql(添加到自动启动列表)  /sbin/chkconfig　– del　mysql(删除自动启动列表)

添加mysql用户：
    grant select on 数据库.* to 用户名@登录主机 identified by "密码" 
    grant select,insert,update,delete on *.* to user_1@"%" Identified by "123"; 

mysql的备份和恢复：
    mysqldump -u root -p --opt(配置项) (库或者表) > back.sql
    mysql -u root -p (库或者表) < back.sql

mysql 优化工具

    show status             explain             optimize(允许你恢复空间和合并数据文件碎片,OPTIMIZE目前只工作于MyIASM和BDB表)


mysql查询优化基本规则：

 1. 对查询优化应该避免全表查询，首先要考虑在where和order by 涉及的列上建立索引
 2. 尽量避免where语句对字段进行null判断，否则将导致引擎放弃使用索引而进行全表扫描    列：select id from t where num is null
 3. 应尽量避免在 where 子句中使用!=或<>操作符，否则将引擎放弃使用索引而进行全表扫描

显示表索引：

查看索引
mysql> show index from tblname;
mysql> show keys from tblname;
· Table
表的名称。
· Non_unique
如果索引不能包括重复词，则为0。如果可以，则为1。
· Key_name
索引的名称。
· Seq_in_index
索引中的列序列号，从1开始。
· Column_name
列名称。
· Collation
列以什么方式存储在索引中。在MySQL中，有值‘A’（升序）或NULL（无分类）。
· Cardinality
索引中唯一值的数目的估计值。通过运行ANALYZE TABLE或myisamchk -a可以更新。基数根据被存储为整数的统计数据来计数，所以即使对于小型表，该值也没有必要是精确的。基数越大，当进行联合时，MySQL使用该索引的机 会就越大。
· Sub_part
如果列只是被部分地编入索引，则为被编入索引的字符的数目。如果整列被编入索引，则为NULL。
· Packed
指示关键字如何被压缩。如果没有被压缩，则为NULL。
· Null
如果列含有NULL，则含有YES。如果没有，则该列含有NO。
· Index_type
用过的索引方法（BTREE, FULLTEXT, HASH, RTREE）。
· Comment


mysql调优：

(1)记录慢日志
[mysqld]  
; enable the slow query log, default 10 seconds  
log-slow-queries  
; log queries taking longer than 5 seconds  
long_query_time = 5  
; log queries that don't use indexes even if they take less than long_query_time  
; MySQL 4.1 and newer only  
log-queries-not-using-indexes  

慢速查询日志都保存在 MySQL 数据目录中，名为 hostname-slow.log。如果希望使用一个不同的名字或路径，可以在 my.cnf 中使用log-slow-queries = /new/path/to/file 实现此目的。

mysqldumpslow /data/mysql-db/slow_queries.log -t 10   显示错误日志

(2)对查询缓存

将query_cache_size = 32M 添加到 /etc/my.conf 中可以启用 32MB 的查询缓存

SHOW STATUS LIKE 'qcache%'; 

变量名                 说明  
Qcache_free_blocks  缓存中相邻内存块的个数。数目大说明可能有碎片。FLUSH QUERY CACHE 会对缓存中的碎片进行整理，从而得到一个空闲块。  
Qcache_free_memory  缓存中的空闲内存。  
Qcache_hits             每次查询在缓存中命中时就增大。  
Qcache_inserts          每次插入一个查询时就增大。命中次数除以插入次数就是不中比率；用 1 减去这个值就是命中率。在上面这个例子中，大约有 87% 的查询都在缓存中命中。  
Qcache_lowmem_prunes    缓存出现内存不足并且必须要进行清理以便为更多查询提供空间的次数。这个数字最好长时间来看；如果这个数字在不断增长，就表示可能碎片非常严重，或者内存很少。（上面的 free_blocks 和 free_memory 可以告诉您属于哪种情况）。  
Qcache_not_cached   不适合进行缓存的查询的数量，通常是由于这些查询不是 SELECT 语句。  
Qcache_queries_in_cache 当前缓存的查询（和响应）的数量。  
Qcache_total_blocks     缓存中块的数量。

(3)强制限制(my.cn)

set-variable=max_connections=500  
最大连接数，在服务器没有崩溃之前确保只建立服务允许数目的连接，要确定服务器上目前建立过的最大连接数，请执行：
SHOW STATUS LIKE 'max_used_connections'

set-variable=wait_timeout=10 
 mysqld将终止等待时间（空闲时间）超过10秒的连接。在 LAMP 应用程序中，连接数据库的时间通常就是 Web 服务器处理请求所花费的时间。有时候，如果负载过重，连接会挂起，并且会占用连接表空间。如果有多个交互用户或使用了到数据库的持久连接，那么将这个值设低一点并不可取！

max_connect_errors = 100 
如果一个主机在连接到服务器时有问题，并重试很多次后放弃，那么这个主机就会被锁定，直到FLUSH HOSTS 之后才能运行。默认情况下，10 次失败就足以导致锁定了。将这个值修改为 100 会给服务器足够的时间来从问题中恢复。如果重试 100 次都无法建立连接，那么使用再高的值也不会有太多帮助，可能它根本就无法连接

(4)
表缓存
最大数目由 /etc/mysqld.conf 中的 table_cache 指定
SHOW STATUS LIKE 'open%tables';
如果你每次重新执行 SHOW TABLE LIKE "open%tables"；open_tables 的变化很大，说明该缓存的命中率不高

线程缓存
SHOW STATUS LIKE 'threads%'; 
这里主要看Threads_created，如果重复执行SHOW STATUS LIKE  "threads%"时，这个值增长的非常快，那么你可以考虑在my.cnf中加大线程缓存，例如：thread_cache = 40 

关键字缓存
show status like '%key_read%';
Key_reads 代表命中磁盘的请求个数，Key_read_requests 是总数， 命中磁盘的读请求数除以读请求总数就是不中比率 —— 在本例中每 1,000 个请求，大约有 0.6 个没有命中内存。如果每 1,000 个请求中命中磁盘的数目超过 1 个，就应该考虑增大关键字缓冲区了。例如，key_buffer = 384M 会将缓冲区设置为 384MB

临时表使用
SHOW STATUS LIKE 'created_tmp%';
 每次使用临时表都会增大 Created_tmp_tables；基于磁盘的表也会增大 Created_tmp_disk_tables。对于这个比率，并没有什么严格的规则，因为这依赖于所涉及的查询。长时间观察Created_tmp_disk_tables 会显示所创建的磁盘表的比率，您可以确定设置的效率。tmp_table_size 和max_heap_table_size 都可以控制临时表的最大大小，因此请确保在 my.cnf 中对这两个值都进行了设置

排序
SHOW STATUS LIKE "sort%";
如果sort_merge_passes 很大，就表示需要注意sort_buffer_size。例如， sort_buffer_size = 4M 将排序缓冲区设置为 4MB。

查询
SHOW STATUS LIKE "com_select";  
SHOW STATUS LIKE "handler_read_rnd_next"; 

Handler_read_rnd_next /Com_select 如果该值超过 4000，就应该查看read_buffer_size，例如read_buffer_size = 4M。如果这个数字超过了 8M，对这些查询进行调优了！