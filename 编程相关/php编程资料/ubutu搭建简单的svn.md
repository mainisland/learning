ubuntu�svn������

����:ubuntu 12.04.5
?apt-get install subversion
?�Ҹ�Ŀ¼��Ϊsvn�Ĳֿ� mkdir svn svnadmin create svn/phpcms
?�޸�svnserve.conf�ļ� �򿪻������� anon-access = read auth-access = write
password-db = passwd
authz-db = authz
?�޸�passwd �����û���������: [user] username=password //�û������Լ���дusername��password
?�޸�authz ���ڽ���ͻ���svn��֤ʧ�ܵ����� [/]*= rw
?����svn:svnserve -d -r /opt/svn