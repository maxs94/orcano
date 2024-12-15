#!/bin/bash
# name: API Response
# desc: calls an API Endpoint and returns the content
# parameters: url<string>

# decode json and get the url 
url=$(echo $1 | jq -r '.url')

response=$(curl -s $url)

printf '%s' $response
