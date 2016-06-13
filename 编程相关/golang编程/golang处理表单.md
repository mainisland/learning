#golang�����

������
package main

import (
	"fmt"
	"html/template"
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

func login(w http.ResponseWriter, r *http.Request) {
	r.ParseForm()
	fmt.Println("method:", r.Method) //��ȡ�������
	if r.Method == "GET" {
		t, _ := template.ParseFiles("login.html")
		t.Execute(w, nil)
	} else {
		//�����ǵ�¼����
		//fmt.Println(r.Form)
		fmt.Println("username:", r.Form["username"])
		fmt.Println("password:", r.Form["password"])
	}
}

func main() {
	http.HandleFunc("/", sayhelloName) //���÷���·��
	http.HandleFunc("/login", login)
	err := http.ListenAndServe(":80", nil) //���ü����˿�
	if err != nil {
		log.Fatal("ListenAndServe:", err)
	}
}
������