#!/bin/bash
# name: IPV4 Ping
# desc: Pings the host and returns result
# parameters: ipv4<string>
#
# note: needs GNU grep because of the PCRE option (-oP) and should return ms
#
# ping result codes: 
# 0 = SUCCESS
# 1 = NO REPLY
# 2 = ERROR

# decode json and get the ip 
ipv4=$(echo $1 | jq -r '.ipv4')

if [ -z "${ipv4}" ]; then
    echo '{"result":"ERROR", "message":"no ipv4 address"}'
    exit 2
fi

GREP=/bin/grep
##GREP=/usr/local/bin/ggrep

PING="ping -w 3 -c 1"
##PING="ping -t 3 -c 1"


pingResult=$($PING $ipv4 2>/dev/null)
pingResultCode=$?
pingTime=$(echo "$pingResult" | $GREP -oP 'time=\K\S+')

# LC_NUMERIC=C is needed to force printf to use a dot instead of a comma for the decimal separator
LC_NUMERIC=C printf '{"result":%d,"time":"%f"}' $pingResultCode $pingTime
