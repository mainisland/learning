#golang�����˿�http����

������
	package main

	import (
		"fmt"
		"log"
		"net/http"
		"strings"
	)

	func sayhelloName(w http.ResponseWriter, r *http.Request) {
		r.ParseForm() //��������,Ĭ���ǲ������
		fmt.Println(r.Form)
		fmt.Println("path", r.URL.Path)     //·��
		fmt.Println("scheme", r.URL.Scheme) //url����
		fmt.Println(r.Form["url_log"])
		for k, v := range r.Form {
			fmt.Println("key:", k)
			fmt.Println("value:", strings.Join(v, ""))
		}
		fmt.Fprintf(w, "hello daxia") //���ظ��ͻ���
	}

	func main() {
		http.HandleFunc("/", sayhelloName)     //���÷���·��
		err := http.ListenAndServe(":80", nil) //���ü����˿�
		if err != nil {
			log.Fatal("ListenAndServe:", err)
		}
	}
������
