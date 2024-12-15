#!/bin/bash
# name: IPV6 Ping
# desc: Pings the host and returns result
# parameters: ipv6<string>
#
# note: needs GNU grep because of the PCRE option (-oP) and should return ms
#
# ping result codes: 
# 0 = SUCCESS
# 1 = NO REPLY
# 2 = ERROR

# decode json and get the ip 
ipv6=$(echo $1 | jq -r '.ipv6')

if [ -z "${ipv6}" ]; then
    printf '{"result":"ERROR", "message": "no ipv6 address"}'
    exit 2
fi

GREP=/bin/grep
##GREP=/usr/local/bin/ggrep

PING6="ping6 -w 3 -c 1"
##PING6="ping6 -t 3 -c 1"

pingResult=$($PING6 $ipv6 2>/dev/null)
pingResultCode=$?
pingTime=$(echo "$pingResult" | $GREP -oP 'time=\K\S+')

PRINTF="/usr/bin/printf"

# LC_ALL=C is needed to force printf to use a dot instead of a comma for the decimal separator
LC_ALL=C $PRINTF '{"ipv4":"%s","result":%d,"time":"%f","unit":"s"}' $ipv4 $pingResultCode $pingTime
