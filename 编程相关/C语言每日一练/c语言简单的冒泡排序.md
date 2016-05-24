#冒泡排序

```
#include <stdio.h>

/**
* 简单的冒泡排序算法； 
* 描述：没一次循环确定一个数，使用交换变量t 
* t=a;a=b;b=t; 
*/
int main() {
	int a[100],i,j,t,n;
	printf("请输入个数：");
	scanf("%d",&n);
	printf("请输入数字:"); 
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