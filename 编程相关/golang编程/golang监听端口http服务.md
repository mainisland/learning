#golang监听端口http服务

···
	package main

	import (
		"fmt"
		"log"
		"net/http"
		"strings"
	)

	func sayhelloName(w http.ResponseWriter, r *http.Request) {
		r.ParseForm() //解析参数,默认是不会解析
		fmt.Println(r.Form)
		fmt.Println("path", r.URL.Path)     //路径
		fmt.Println("scheme", r.URL.Scheme) //url类型
		fmt.Println(r.Form["url_log"])
		for k, v := range r.Form {
			fmt.Println("key:", k)
			fmt.Println("value:", strings.Join(v, ""))
		}
		fmt.Fprintf(w, "hello daxia") //返回给客户端
	}

	func main() {
		http.HandleFunc("/", sayhelloName)     //设置访问路由
		err := http.ListenAndServe(":80", nil) //设置监听端口
		if err != nil {
			log.Fatal("ListenAndServe:", err)
		}
	}
···
