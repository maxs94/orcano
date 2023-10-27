#!/bin/bash
# script will be called by orcano with parameters: $1 = host, $2 = ipv4, $3 = ipv6
#
# name: HTTP Status Check
# desc: Checks the HTTP/S status and returns the result

httpCode=$(curl -s -o /dev/null -w "%{http_code}" http://$1)
httpsCode=$(curl -s -o /dev/null -w "%{http_code}" https://$1)

printf 'ODATA: {"http":%d, "https":%d}' $httpCode $httpsCode
