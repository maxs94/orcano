% check scripts
scripts will be called by orcano with a JSON string containing the following:

```json
{
    "host": "hostname",
    "ipv4": "127.0.0.1",
    "ipv6": "::fff",

    ... aditional data based on check ...
}
```

Example:
 `/scripts/checks/./ping4.sh '{"host":"google.com","ipv4":"127.0.0.1"}'`

After adding/updating a script, refresh the scripts by typing:
`bin/console orcano:script:refresh`

This will register and update the scripts in the database.
