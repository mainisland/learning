#mysql�����ú��Ż�

��¼MySQL��������mysql��       mysql [-u username] [-h host] [-p[password]] [dbname] 

mysqlĿ¼��
    ���ݿ�Ŀ¼��/var/lib/mysql/
    �����ļ� ��/usr/share/mysql��mysql.server��������ļ���
    ������� ��/usr/bin(mysqladmin mysqldump������)
    �����ű� ��/etc/rc.d/init.d/�������ű��ļ�mysql��Ŀ¼��

�޸�mysql�ĵ�¼���룺  usr/bin/mysqladmin -u root password 'new-password'

mysql������ֹͣ�� /etc/init.d/mysql start      /usr/bin/mysqladmin -u root -p shutdown

mysql�Զ�����:  /sbin/chkconfig �Clist     /sbin/chkconfig���C add��mysql(��ӵ��Զ������б�)  /sbin/chkconfig���C del��mysql(ɾ���Զ������б�)

���mysql�û���
    grant select on ���ݿ�.* to �û���@��¼���� identified by "����" 
    grant select,insert,update,delete on *.* to user_1@"%" Identified by "123"; 

mysql�ı��ݺͻָ���
    mysqldump -u root -p --opt(������) (����߱�) > back.sql
    mysql -u root -p (����߱�) < back.sql

mysql �Ż�����

    show status             explain             optimize(������ָ��ռ�ͺϲ������ļ���Ƭ,OPTIMIZEĿǰֻ������MyIASM��BDB��)


mysql��ѯ�Ż���������

 1. �Բ�ѯ�Ż�Ӧ�ñ���ȫ���ѯ������Ҫ������where��order by �漰�����Ͻ�������
 2. ��������where�����ֶν���null�жϣ����򽫵����������ʹ������������ȫ��ɨ��    �У�select id from t where num is null
 3. Ӧ���������� where �Ӿ���ʹ��!=��<>�������������������ʹ������������ȫ��ɨ��

��ʾ��������

�鿴����
mysql> show index from tblname;
mysql> show keys from tblname;
�� Table
������ơ�
�� Non_unique
����������ܰ����ظ��ʣ���Ϊ0��������ԣ���Ϊ1��
�� Key_name
���������ơ�
�� Seq_in_index
�����е������кţ���1��ʼ��
�� Column_name
�����ơ�
�� Collation
����ʲô��ʽ�洢�������С���MySQL�У���ֵ��A�������򣩻�NULL���޷��ࣩ��
�� Cardinality
������Ψһֵ����Ŀ�Ĺ���ֵ��ͨ������ANALYZE TABLE��myisamchk -a���Ը��¡��������ݱ��洢Ϊ������ͳ�����������������Լ�ʹ����С�ͱ���ֵҲû�б�Ҫ�Ǿ�ȷ�ġ�����Խ�󣬵���������ʱ��MySQLʹ�ø������Ļ� ���Խ��
�� Sub_part
�����ֻ�Ǳ����ֵر�����������Ϊ�������������ַ�����Ŀ��������б�������������ΪNULL��
�� Packed
ָʾ�ؼ�����α�ѹ�������û�б�ѹ������ΪNULL��
�� Null
����к���NULL������YES�����û�У�����к���NO��
�� Index_type
�ù�������������BTREE, FULLTEXT, HASH, RTREE����
�� Comment


mysql���ţ�

(1)��¼����־
[mysqld]  
; enable the slow query log, default 10 seconds  
log-slow-queries  
; log queries taking longer than 5 seconds  
long_query_time = 5  
; log queries that don't use indexes even if they take less than long_query_time  
; MySQL 4.1 and newer only  
log-queries-not-using-indexes  

���ٲ�ѯ��־�������� MySQL ����Ŀ¼�У���Ϊ hostname-slow.log�����ϣ��ʹ��һ����ͬ�����ֻ�·���������� my.cnf ��ʹ��log-slow-queries = /new/path/to/file ʵ�ִ�Ŀ�ġ�

mysqldumpslow /data/mysql-db/slow_queries.log -t 10   ��ʾ������־

(2)�Բ�ѯ����

��query_cache_size = 32M ��ӵ� /etc/my.conf �п������� 32MB �Ĳ�ѯ����

SHOW STATUS LIKE 'qcache%'; 

������                 ˵��  
Qcache_free_blocks  �����������ڴ��ĸ�������Ŀ��˵����������Ƭ��FLUSH QUERY CACHE ��Ի����е���Ƭ���������Ӷ��õ�һ�����п顣  
Qcache_free_memory  �����еĿ����ڴ档  
Qcache_hits             ÿ�β�ѯ�ڻ���������ʱ������  
Qcache_inserts          ÿ�β���һ����ѯʱ���������д������Բ���������ǲ��б��ʣ��� 1 ��ȥ���ֵ���������ʡ���������������У���Լ�� 87% �Ĳ�ѯ���ڻ��������С�  
Qcache_lowmem_prunes    ��������ڴ治�㲢�ұ���Ҫ���������Ա�Ϊ�����ѯ�ṩ�ռ�Ĵ��������������ó�ʱ�������������������ڲ����������ͱ�ʾ������Ƭ�ǳ����أ������ڴ���١�������� free_blocks �� free_memory ���Ը��������������������  
Qcache_not_cached   ���ʺϽ��л���Ĳ�ѯ��������ͨ����������Щ��ѯ���� SELECT ��䡣  
Qcache_queries_in_cache ��ǰ����Ĳ�ѯ������Ӧ����������  
Qcache_total_blocks     �����п��������

(3)ǿ������(my.cn)

set-variable=max_connections=500  
������������ڷ�����û�б���֮ǰȷ��ֻ��������������Ŀ�����ӣ�Ҫȷ����������Ŀǰ���������������������ִ�У�
SHOW STATUS LIKE 'max_used_connections'

set-variable=wait_timeout=10 
 mysqld����ֹ�ȴ�ʱ�䣨����ʱ�䣩����10������ӡ��� LAMP Ӧ�ó����У��������ݿ��ʱ��ͨ������ Web �������������������ѵ�ʱ�䡣��ʱ��������ع��أ����ӻ���𣬲��һ�ռ�����ӱ�ռ䡣����ж�������û���ʹ���˵����ݿ�ĳ־����ӣ���ô�����ֵ���һ�㲢����ȡ��

max_connect_errors = 100 
���һ�����������ӵ�������ʱ�����⣬�����Ժܶ�κ��������ô��������ͻᱻ������ֱ��FLUSH HOSTS ֮��������С�Ĭ������£�10 ��ʧ�ܾ����Ե��������ˡ������ֵ�޸�Ϊ 100 ����������㹻��ʱ�����������лָ���������� 100 �ζ��޷��������ӣ���ôʹ���ٸߵ�ֵҲ������̫��������������������޷�����

(4)
����
�����Ŀ�� /etc/mysqld.conf �е� table_cache ָ��
SHOW STATUS LIKE 'open%tables';
�����ÿ������ִ�� SHOW TABLE LIKE "open%tables"��open_tables �ı仯�ܴ�˵���û���������ʲ���

�̻߳���
SHOW STATUS LIKE 'threads%'; 
������Ҫ��Threads_created������ظ�ִ��SHOW STATUS LIKE  "threads%"ʱ�����ֵ�����ķǳ��죬��ô����Կ�����my.cnf�мӴ��̻߳��棬���磺thread_cache = 40 

�ؼ��ֻ���
show status like '%key_read%';
Key_reads �������д��̵����������Key_read_requests �������� ���д��̵Ķ����������Զ������������ǲ��б��� ���� �ڱ�����ÿ 1,000 �����󣬴�Լ�� 0.6 ��û�������ڴ档���ÿ 1,000 �����������д��̵���Ŀ���� 1 ������Ӧ�ÿ�������ؼ��ֻ������ˡ����磬key_buffer = 384M �Ὣ����������Ϊ 384MB

��ʱ��ʹ��
SHOW STATUS LIKE 'created_tmp%';
 ÿ��ʹ����ʱ�������� Created_tmp_tables�����ڴ��̵ı�Ҳ������ Created_tmp_disk_tables������������ʣ���û��ʲô�ϸ�Ĺ�����Ϊ�����������漰�Ĳ�ѯ����ʱ��۲�Created_tmp_disk_tables ����ʾ�������Ĵ��̱�ı��ʣ�������ȷ�����õ�Ч�ʡ�tmp_table_size ��max_heap_table_size �����Կ�����ʱ�������С�������ȷ���� my.cnf �ж�������ֵ������������

����
SHOW STATUS LIKE "sort%";
���sort_merge_passes �ܴ󣬾ͱ�ʾ��Ҫע��sort_buffer_size�����磬 sort_buffer_size = 4M �����򻺳�������Ϊ 4MB��

��ѯ
SHOW STATUS LIKE "com_select";  
SHOW STATUS LIKE "handler_read_rnd_next"; 

Handler_read_rnd_next /Com_select �����ֵ���� 4000����Ӧ�ò鿴read_buffer_size������read_buffer_size = 4M�����������ֳ����� 8M������Щ��ѯ���е����ˣ�