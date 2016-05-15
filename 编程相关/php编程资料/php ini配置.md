���¶���php.ini��Ĭ������
engine=On
ʹ PHP scripting language engine��PHP �ű��������棩�� Apache����Ч��

short_open_tag = On
����php�̱�ʶ����ʶ��

asp_tags=Off
����ASP-style tags

precision=14
������������ʾʱ����Чλ��

y2k_compliance=On
�Ƿ�� 2000����Ӧ (�����ڷ�Y2K��Ӧ��������е�������)

output_buffering=4096
������������������������������֮���� header����ͷ������cookies���� 
���������������һ����ٶȡ������ʹ���������������ʱ��������棬 
���������ｫָʾ��Ϊ On ��ʹ�������ļ�����������

output_handler = 
������ض�����Ľű������������һ�������� 
���������ܶԴ��������־��¼�����á� 
�������㽫���output_handler ��Ϊ" ob_gzhandler" , 
������ᱻ͸����Ϊ֧��gzip��deflate����������ѹ���� 
��һ������������Զ��ش��������

implicit_flush=Off
ǿ��flush��ˢ�£���PHP �����������ÿ�������֮���Զ�ˢ���������ݡ� 
���Ч����ÿ�� print() �� echo() ���ú�ÿ�� HTML ������flush()������ 
���������ûᵼ�����ص�����ʱ��ͻ���������debug�����д�

��ȫģʽ����
safe_mode=Off
safe_mode_gid=Off
safe_mode_include_dir=
safe_mode_exec_dir=
safe_mode_allowed_env_vars=PHP_
Ĭ�ϵأ��û������� �趨��PHP_��ͷ�Ļ�������������: PHP_FOO=BAR���� 
ע��: �����һָʾΪ�գ�PHP �����û��������⻷������
safe_mode_protected_env_vars=LD_LIBRARY_PATH

disable_functions=
disable_classes=
����ָʾ�������Ϊ�˰�ȫ��ԭ�����ض�����ʧЧ�� 
������һ���ö��ŷָ��ĺ������б� 
����ָʾ *����* ��ȫģʽ�Ƿ�򿪵�Ӱ��

expose_php=On
���� PHP �Ƿ��ʾ��װ�ڷ������ϵ���ʵ�����磺������ �� PHP�� ��Web���� 
; ���͵��ź��ϣ��� 
���ڳ���ʲôpower-by��header��ʱ�򣬰���ص����� 
�������а�ȫ�ϵ���в, ����ʹ�����ķ��������Ƿ�װ��PHP��Ϊ�˿���

max_execution_time=30
ÿ���ű������ִ��ʱ��, �����
max_input_time=60

memory_limit=128M
 һ���ű�����ʹ�õ��ڴ�����

error_reporting=E_ALL & ~E_DEPRECATED & ~E_STRICT
��ʾ���еĴ��󣬳���

display_errors=On
��ʾ��������Ϣ(��Ϊ�����һ����) 

display_startup_errors=On
������display_erroes���ˣ�������PHP�������Ĳ����еĴ���Ҳ���ᱻ��ʾ�� 
ǿ�ҽ��鱣��ʹ display_startup_errors �رգ� 
�����ڸĴ�����С�

log_errors=On
����־�ļ����¼���󣨷�����ָ������־��stderr��׼�����������error_log(����ģ��� 
��������˵����������ǿ�ҽ����������շ�����webվ������־��¼���� 
ȡ��ֱ�Ӵ��������

log_errors_max_len=1024
ignore_repeated_errors=Off
ignore_repeated_source=Off
report_memleaks=On
track_errors=On
�������һ�� ����/���� ��Ϣ�ڱ��� $php_errormsg (boolean)
html_errors=On
error_log=""

variables_order="GPCS"
����ָʾ������PHP ��¼ 
GET, POST, Cookie, Environment and Built-in ��Щ������˳�� 
���� G, P, C, E & S ����ͨ���� EGPCS �� GPC �ķ�ʽ���ã��� 
�������Ҽ�¼����ֵȡ����ֵ��

register_globals=Off
�Ƿ���Щ EGPCS ����ע��Ϊȫ�ֱ����� 
���㲻�����û����ݲ���ȫ�ַ�Χ�ڻ��ҵĻ����������ر����� 
��� track_vars �������ø������� �� ���������ͨ�� 
$HTTP_*_VARS[] ����������е�GPC����

register_argc_argv=Off
����ָʾ���� PHP �Ƿ����� argv��argc ���� 
��ע������argvΪ����,argcΪ�������� 
�����а�����GET�������������ݣ��� 
���㲻������Щ��������Ӧ���ص������������

post_max_size=8M
PHP�����ܵ�POST��������С

magic_quotes_gpc=Off
�Ƿ������GET/POST/Cookie������ʹ��ħ������

magic_quotes_runtime=Off
�Ƿ������ʱ����������ʹ��ħ������

magic_quotes_sybase=Off
�Ƿ���� Sybase��ʽ��ħ������

auto_prepend_file=
auto_append_file=
�Զ��� PHP �ĵ�֮ǰ��֮������ļ� 

default_mimetype="text/html"
default_charset = "UTF-8"
PHP Ĭ�ϵ������� �� Content-type:�� ͷ�����һ���ַ��ı��뷽ʽ�� 
������ַ���ʧЧ��ֻҪ����Ϊ�ա� 
PHP ���ڽ�Ĭ��ֵ�� text/html

include_path=".;E:\xampp\php\PEAR"
include ·������

doc_root=
php ҳ��ĸ�·�������ڷǿ�ʱ��Ч

user_dir=
��֪ php ��ʹ�� /~username �򿪽ű�ʱ���ĸ�Ŀ¼��ȥ�ң����ڷǿ�ʱ��Ч

extension_dir="E:\xampp\php\ext"
��ſɼ��ص�����⣨ģ�飩��Ŀ¼ 

enable_dl=On
�Ƿ�ʹdl()��Ч�� 
�ڶ��̵߳ķ������� dl()����*����*�ܺõع���

file_uploads=On
�Ƿ�����HTTP��ʽ�ļ����� 

upload_tmp_dir="E:\xampp\tmp"
�����HTTPЭ�����ص��ļ�����ʱĿ¼����ûָ��ʱʹ��ϵͳĬ�ϵģ�

upload_max_filesize=2M
�ļ�����Ĭ�ϵ�����Ϊ2M

max_file_uploads=20
����ϴ����ļ���

allow_url_fopen=On
�Ƿ������URLs����http:// ����ļ�����ftp://

allow_url_include=Off
default_socket_timeout=60