# EasyCurl

## Installation

Uploader is available via Composer:

```bash
"klebervmv/easycurl": "1.0.*"
```

or run

```bash
composer require klebervmv/easycurl
```

## Documentation

The easyCurl was developed to facilitate the use of curl communication for simple requests, supporting methods such as
GET, POST, PUT and DELEATE. Still allows sending images via CURL

### Details of Construct:

#### The construct has 3 parameters, with only one mandatory:

1° parameter is the Base URL;<br>
2° is ssl verification - default true;<br>
3° is the json or xml post type - default json.

From version 1.0.10 onwards, the possibility of making requests and not waiting for them to return was implemented. To
do this, insert the false option within the send method: send(false).

```PHP
<?php
use klebervmv\EasyCurl;
 $easyCurl = new EasyCurl("route url", true, "json");
```

### Example of a simple request:

```PHP
<?php
use klebervmv\EasyCurl;
 $easyCurl = new EasyCurl("route url");
 $easyCurl->render("GET", "/ednpoint")->send();
 
 //If there is an error in the communication, it will be returned in the getError() method;
 if($easyCurl->getError()){
 var_dump($easyCurl->getError());
 return;
 }
 //Through the getHttpCode() method you can validate the return http code
 if($easyCurl->getHttpCode() !== 200){
  var_dump($easyCurl->getResult());
  return;
 }
//the result will be returned in the getResult() method in the array format
 var_dump($easyCurl->getResult());
```

### Simple example of post request passing parameters:

#### Parameters can be passed as an array or stdClass, as they will be converted to Json:

```PHP
<?php
use klebervmv\EasyCurl;
 $easyCurl = new EasyCurl("route url");
 
 
$param = new stdClass();
$param->firstName = "Kleberton";
$param->lastName = "Vilela";
$param->email = "exemple@exemple.com";
 
 $easyCurl->render("POST", "/ednpoint", $param)->send();
 
 //If there is an error in the communication, it will be returned in the getError() method;
 if($easyCurl->getError()){
 var_dump($easyCurl->getError());
 return;
 }
 //Through the getHttpCode() method you can validate the return http code
 if($easyCurl->getHttpCode() !== 200){
  var_dump($easyCurl->getResult());
  return;
 }
//the result will be returned in the getResult() method in the array format
 var_dump($easyCurl->getResult());
```

### Inserting header

```PHP
<?php

use klebervmv\EasyCurl;
 $easyCurl = new EasyCurl("route url");
 $easyCurl->render("GET", "/ednpoint")
          ->setHeader("Authorization:Bearer TOKEN")
          ->send();

```

### Reset end inserting a new header

#### You can clear the entire header and insert new header parameters:

```PHP
<?php

use klebervmv\EasyCurl;
 $easyCurl = new EasyCurl("route url");
 $easyCurl->render("GET", "/ednpoint")
          ->resetHeader()
          ->setHeader("lang:pt-BR")
          ->setHeader("Authorization:Bearer TOKEN")
          ->send();
```
