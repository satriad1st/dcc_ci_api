# CodeIgniter-3-DCC API

Simple Codeigniter, REST Server, Pengimplementasian JWT dengan database MySQL


Setup using this repo
=====

Requirement
=======
[PHP] (https://www.php.net/)  Version 7.2 or Highest

[MySQL] (https://www.mysql.com/)

[XAMPP] (https://www.apachefriends.org/index.html) Or Another Web Server



Set up project on php server (XAMPP/Linux). 


* `encryption_key` in `application\config\config.php`  

```
$config['encryption_key'] = '';
```  

* `jwt_key` in `application\config\jwt.php`

```
$config['jwt_key']	= '';
```

* **For Timeout** `token_timeout` in `application\config\jwt.php`

```
$config['token_timeout']	= ;
```


Setup for existing projects
=====


You will need following files:

**/application/config/jwt.php** <= Add **jwt_key** here
**/application/helpers/authorization_helper.php
/application/helpers/jwt_helper.php**

In **/application/config/autoload.php** add 
```
$autoload['helper'] = array('url', 'form', 'jwt', "authorization");
$autoload['config'] = array('jwt');
```
