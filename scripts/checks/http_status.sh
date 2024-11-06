#!/bin/bash
# name: HTTP Status Check
# desc: Checks the HTTP status and returns the result
# parameters: url<string>

# decode json and get the url 
url=$(echo $1 | jq -r '.url')

response=$(curl -s --write-out "%{json}" -o /dev/null $url)

printf '%s' $response
