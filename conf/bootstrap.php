<?php
//à



$config = new \Doctrine\DBAL\Configuration();
//..
$connectionParams = array(
    'dbname' => dbname,
    'user' => dbuser,
    'password' => dbpass,
    'host' => dbhost,
    'driver' => 'pdo_mysql',
);
$conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);


