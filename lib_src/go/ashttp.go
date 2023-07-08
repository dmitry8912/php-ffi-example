package main

import (
	"C"
	"encoding/json"
	"net/http"
	"strings"
)

//export getStatus
func getStatus(urls *C.char) *C.char {
	urlList := strings.Split(C.GoString(urls), ",")
	c := make(chan urlStatus)
	for _, url := range urlList {
		go checkUrl(url, c)
	}

	result := make([]urlStatus, len(urlList))
	for i, _ := range result {
		result[i] = <-c
	}

	data, err := json.Marshal(result)
	if err != nil {
		return C.CString("")
	}

	return C.CString(string(data))
}

func checkUrl(url string, c chan urlStatus) {
	_, err := http.Get(url)
	if err != nil {
		c <- urlStatus{url, false}
	} else {
		c <- urlStatus{url, true}
	}
}

func main() {}

type urlStatus struct {
	Url    string `json:"url"`
	Status bool   `json:"status"`
}
