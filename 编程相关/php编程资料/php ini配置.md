以下都是php.ini的默认配置
engine=On
使 PHP scripting language engine（PHP 脚本语言引擎）在 Apache下有效；

short_open_tag = On
允许php短标识将被识别

asp_tags=Off
允许ASP-style tags

precision=14
浮点类型数显示时的有效位数

y2k_compliance=On
是否打开 2000年适应 (可能在非Y2K适应的浏览器中导致问题)

output_buffering=4096
输出缓存允许你甚至在输出正文内容之后发送 header（标头，包括cookies）行 
其代价是输出层减慢一点点速度。你可以使用输出缓存在运行时打开输出缓存， 
或者在这里将指示设为 On 而使得所有文件的输出缓存打开

output_handler = 
你可以重定向你的脚本的所有输出到一个函数， 
那样做可能对处理或以日志记录它有用。 
例如若你将这个output_handler 设为" ob_gzhandler" , 
则输出会被透明地为支持gzip或deflate编码的浏览器压缩。 
设一个输出处理器自动地打开输出缓冲

implicit_flush=Off
强制flush（刷新）让PHP 告诉输出层在每个输出块之后自动刷新自身数据。 
这等效于在每个 print() 或 echo() 调用和每个 HTML 块后调用flush()函数。 
打开这项设置会导致严重的运行时冲突，建议仅在debug过程中打开

安全模式配置
safe_mode=Off
safe_mode_gid=Off
safe_mode_include_dir=
safe_mode_exec_dir=
safe_mode_allowed_env_vars=PHP_
默认地，用户将仅能 设定以PHP_开头的环境变量，（如: PHP_FOO=BAR）。 
注意: 如果这一指示为空，PHP 将让用户更改任意环境变量
safe_mode_protected_env_vars=LD_LIBRARY_PATH

disable_functions=
disable_classes=
这条指示让你可以为了安全的原因让特定函数失效。 
它接受一个用逗号分隔的函数名列表。 
这条指示 *不受* 安全模式是否打开的影响

expose_php=On
决定 PHP 是否标示它装在服务器上的事实（例如：加在它 — PHP— 给Web服务 
; 发送的信号上）。 
（在出现什么power-by的header的时候，把这关掉。） 
它不会有安全上的威胁, 但它使检查你的服务器上是否安装了PHP成为了可能

max_execution_time=30
每个脚本的最大执行时间, 按秒计
max_input_time=60

memory_limit=128M
 一个脚本最大可使用的内存总量

error_reporting=E_ALL & ~E_DEPRECATED & ~E_STRICT
显示所有的错误，除了

display_errors=On
显示出错误信息(作为输出的一部分) 

display_startup_errors=On
甚至当display_erroes打开了，发生于PHP的启动的步骤中的错误也不会被显示。 
强烈建议保持使 display_startup_errors 关闭， 
除了在改错过程中。

log_errors=On
在日志文件里记录错误（服务器指定的日志，stderr标准错误输出，或error_log(下面的）） 
正如上面说明的那样，强烈建议你在最终发布的web站点以日志记录错误 
取代直接错误输出。

log_errors_max_len=1024
ignore_repeated_errors=Off
ignore_repeated_source=Off
report_memleaks=On
track_errors=On
保存最近一个 错误/警告 消息于变量 $php_errormsg (boolean)
html_errors=On
error_log=""

variables_order="GPCS"
这条指示描述了PHP 记录 
GET, POST, Cookie, Environment and Built-in 这些变量的顺序。 
（以 G, P, C, E & S 代表，通常以 EGPCS 或 GPC 的方式引用）。 
按从左到右记录，新值取代旧值。

register_globals=Off
是否将这些 EGPCS 变量注册为全局变量。 
若你不想让用户数据不在全局范围内混乱的话，你可能想关闭它。 
这和 track_vars 连起来用更有意义 — 这样你可以通过 
$HTTP_*_VARS[] 数组访问所有的GPC变量

register_argc_argv=Off
这条指示告诉 PHP 是否声明 argv和argc 变量 
（注：这里argv为数组,argc为变量数） 
（其中包含用GET方法传来的数据）。 
若你不想用这些变量，你应当关掉它以提高性能

post_max_size=8M
PHP将接受的POST数据最大大小

magic_quotes_gpc=Off
是否输入的GET/POST/Cookie数据里使用魔术引用

magic_quotes_runtime=Off
是否对运行时产生的数据使用魔术引用

magic_quotes_sybase=Off
是否采用 Sybase形式的魔术引用

auto_prepend_file=
auto_append_file=
自动在 PHP 文档之前和之后添加文件 

default_mimetype="text/html"
default_charset = "UTF-8"
PHP 默认地总是在 “ Content-type:” 头标输出一个字符的编码方式。 
让输出字符集失效，只要设置为空。 
PHP 的内建默认值是 text/html

include_path=".;E:\xampp\php\PEAR"
include 路径设置

doc_root=
php 页面的根路径，仅在非空时有效

user_dir=
告知 php 在使用 /~username 打开脚本时到哪个目录下去找，仅在非空时有效

extension_dir="E:\xampp\php\ext"
存放可加载的扩充库（模块）的目录 

enable_dl=On
是否使dl()有效。 
在多线程的服务器上 dl()函数*不能*很好地工作

file_uploads=On
是否允许HTTP方式文件上载 

upload_tmp_dir="E:\xampp\tmp"
存放用HTTP协议上载的文件的临时目录（在没指定时使用系统默认的）

upload_max_filesize=2M
文件上载默认地限制为2M

max_file_uploads=20
最多上传的文件数

allow_url_fopen=On
是否允许把URLs当作http:// 或把文件当作ftp://

allow_url_include=Off
default_socket_timeout=60