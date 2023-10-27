% check scripts
scripts will be called by orcano with the following parameters:

```bash
$1 = hostname 
$2 = Ipv4 address 
$3 = Ipv6 address
```

Example:
 `/scripts/checks/./ping4.sh localhost 127.0.0.1 ::fff`

After adding/updating a script, refresh the scripts by typing:
`bin/console orcano:script:refresh`

This will register and update the scripts in the database.
