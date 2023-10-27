#!/bin/bash
# script will be called by orcano with parameters: $1 = host, $2 = ipv4, $3 = ipv6
#
# name: IPV4 Ping testing
# desc: Pings the host and returns result
#
# note: needs GNU grep because of the PCRE option (-oP) and should return ms
#
# ping result codes: 
# 0 = SUCCESS
# 1 = NO REPLY
# 2 = ERROR

GREP=/bin/grep
#GREP=/usr/local/bin/ggrep

PING="ping -w 3 -c 1"
#PING="ping -t 3 -c 1"

# use ipv4 and lastly the hostname
if [ -n "$2" ]; then
    address=$2
else
    address=$1
fi

pingResult=$($PING $address 2>/dev/null)
pingResultCode=$?
pingTime=$(echo "$pingResult" | $GREP -oP 'time=\K\S+')

printf 'ODATA: {"result":%d,"time":"%f"}' $pingResultCode $pingTime
