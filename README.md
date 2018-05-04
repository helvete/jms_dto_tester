# jms_dto_tester
Test your DTOs before publishing

* grab JSON content
* deserialize it according to a JMS DTO provided
* then serialize it again
* finally compare the initial and the resulting string, while both are being recursively sorted the same way
* write two files (`Response_NameSpace_ClassName<epoch-timestamp>.out_a` and `Response_NameSpace_ClassName<epoch-timestamp>.out_b`) for easy diffing in case of mismatch
* dumps formatted resulting JSON by default

Usage:

1. Copy your DTO class to a Request or Response dir (or another one - based on the namespace used)
1. Run
```
./test_dto.php 'Response\NameSpace\ClassName' < /absolute/file/path.json
```
1. Or get the json directly from some API using 3rd party lib, that returns a valid JSON and pipe it through (the example uses [request](https://github.com/helvete/request) API client library)
```
/path/to/request/src/rq -nx /path/to/request/src/t-esb.api_request |./test_dto.php 'Response\NameSpace\ClassName'
```

TODOs:
1. allow windows-friendly mode, that supports second parameter that specifices a filename
1. change default behaviour of dumping formatted JSON string into a switchable option
1. preformat output JSON files to make the diffing even easier
