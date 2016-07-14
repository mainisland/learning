#golangç›‘å¬ç«¯å£httpæœåŠ¡

<<<<<<< HEAD
¡¤¡¤¡¤
	package main
=======
```
package main
>>>>>>> 0095545f229498ac7f9e1228df585b0ff42a79ea

	import (
		"fmt"
		"log"
		"net/http"
		"strings"
	)

<<<<<<< HEAD
	func sayhelloName(w http.ResponseWriter, r *http.Request) {
		r.ParseForm() //½âÎö²ÎÊı,Ä¬ÈÏÊÇ²»»á½âÎö
		fmt.Println(r.Form)
		fmt.Println("path", r.URL.Path)     //Â·¾¶
		fmt.Println("scheme", r.URL.Scheme) //urlÀàĞÍ
		fmt.Println(r.Form["url_log"])
		for k, v := range r.Form {
			fmt.Println("key:", k)
			fmt.Println("value:", strings.Join(v, ""))
		}
		fmt.Fprintf(w, "hello daxia") //·µ»Ø¸ø¿Í»§¶Ë
	}

	func main() {
		http.HandleFunc("/", sayhelloName)     //ÉèÖÃ·ÃÎÊÂ·ÓÉ
		err := http.ListenAndServe(":80", nil) //ÉèÖÃ¼àÌı¶Ë¿Ú
		if err != nil {
			log.Fatal("ListenAndServe:", err)
		}
	}
¡¤¡¤¡¤
=======
func sayhelloName(w http.ResponseWriter, r *http.Request) {
	r.ParseForm() //è§£æå‚æ•°,é»˜è®¤æ˜¯ä¸ä¼šè§£æ
	fmt.Println(r.Form)
	fmt.Println("path", r.URL.Path)     //è·¯å¾„
	fmt.Println("scheme", r.URL.Scheme) //urlç±»å‹
	fmt.Println(r.Form["url_log"])
	for k, v := range r.Form {
		fmt.Println("key:", k)
		fmt.Println("value:", strings.Join(v, ""))
	}
	fmt.Fprintf(w, "hello daxia") //è¿”å›ç»™å®¢æˆ·ç«¯
}

func main() {
	http.HandleFunc("/", sayhelloName)     //è®¾ç½®è®¿é—®è·¯ç”±
	err := http.ListenAndServe(":80", nil) //è®¾ç½®ç›‘å¬ç«¯å£
	if err != nil {
		log.Fatal("ListenAndServe:", err)
	}
}
```
>>>>>>> 0095545f229498ac7f9e1228df585b0ff42a79ea
