<?php
define('DBHOST', getenv('DBHOST'));
define('DBUSER', getenv('DBUSER'));
define('DBPASS', getenv('DBPASS'));
define('DBNAME', getenv('DBNAME'));

require __DIR__ . '/vendor/j4mie/idiorm/idiorm.php';
ORM::configure('mysql:host=' . DBHOST . ';dbname=' . DBNAME);
ORM::configure('username', DBUSER);
ORM::configure('password', DBPASS);
