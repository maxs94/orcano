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

pingResult=$(ping6 -w 3 -c 1 $ipv6 2>/dev/null)
pingResultCode=$?
pingTime=$(echo "$pingResult" | grep -oP 'time=\K\S+')

# LC_NUMERIC=C is needed to force printf to use a dot instead of a comma for the decimal separator
LC_NUMERIC=C printf '{"result":%d,"time":"%f"}' $pingResultCode $pingTime
