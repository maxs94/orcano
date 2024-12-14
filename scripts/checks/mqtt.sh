#!/bin/bash
# name: MQTT Check
# desc: Checks a MQTT topic 
# parameters: port<int>,topic<string>,user<string>,password<string>

# requires mosiquitto-client to be installed 

# decode json and get the url 
host=$(echo $1 | jq -r '.host')
port=$(echo $1 | jq -r '.port')
topic=$(echo $1 | jq -r '.topic')
username=$(echo $1 | jq -r '.username')
password=$(echo $1 | jq -r '.password')

# run command and return json output
mosquitto_sub -t $topic -h $host -p $port -u $username -P $password -v -C 1 -F "%j"

