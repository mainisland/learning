#ð������

```
#include <stdio.h>

/**
* �򵥵�ð�������㷨�� 
* ������ûһ��ѭ��ȷ��һ������ʹ�ý�������t 
* t=a;a=b;b=t; 
*/
int main() {
	int a[100],i,j,t,n;
	printf("�����������");
	scanf("%d",&n);
	printf("����������:"); 
	for(i=1;i<=n;i++) {
		scanf("%d",&a[i]);
	}
	for(i=1;i<=n;i++) {
		for(j=1;j<=n;j++) {
			if(a[j]<a[j+1]) {
				t = a[j];
				a[j] = a[j+1];
				a[j+1] = t;
			}
		}
	}
	for(i=1;i<=n;i++) {
		printf("%d\t",a[i]);
	}
	return 0;
} 
```