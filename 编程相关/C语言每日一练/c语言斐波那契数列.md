#c����쳲���������

������1,1,2,3,5,8,13,21

�ݹ鷨��fun(n-1) + fun(n-2);
	
	#include <stdio.h>
	int fun(int n) {
		if(n <= 1)
			return n;
		else 
			return fun(n-1) + fun(n-2);
	} 
	int main() {
		int n;
		fun(n);
		return 0;
	}