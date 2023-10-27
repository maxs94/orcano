#!/bin/bash
# script will be called by orcano with parameters: $1 = host, $2 = ipv4, $3 = ipv6
#
# name: IPV6 Ping
# desc: Pings the host and returns result
#
# note: needs GNU grep because of the PCRE option (-oP) and should return ms
#
# ping result codes: 
# 0 = SUCCESS
# 1 = NO REPLY
# 2 = ERROR

# use ipv6 and lastly the hostname
if [ -n "$3" ]; then
    address=$3
else
    address=$1
fi

pingResult=$(ping6 -w 3 -c 1 $1 2>/dev/null)
pingResultCode=$?
pingTime=$(echo "$pingResult" | grep -oP 'time=\K\S+')

printf 'ODATA: {"result":%d,"time":"%f"}' $pingResultCode $pingTime
