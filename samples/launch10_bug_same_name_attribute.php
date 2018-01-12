<?php

$autoload_location=__DIR__.'/../vendor/autoload.php';
if (!is_file($autoload_location)) $autoload_location='../../../autoload.php';	

require_once $autoload_location;
require_once __DIR__.'/../conf/config.php';
require_once __DIR__.'/../conf/bootstrap.php';

use Omatech\Editora\Extractor\Extractor;
use Omatech\Editora\Extractor\GraphQLPreprocessor;

$params = [
    'id'       => 480,
    'preview'  => true,
    'lang'  => 'en',
    'metadata'  => true,
    'debug'    => true
];

$magic = [
    "type"      => "instance"
];


$query=GraphQLPreprocessor::generate($magic, true);
echo $query;


$extractor=new Extractor($conn);
$res=$extractor->extract($query, $params);

if ($params['debug'])
{
		echo "DEBUG\n";
		echo $extractor->debug_messages;
}

if ($res)
{
		echo "HA FUNCIONAT!!!";
		print_r($res);
}

$res=$extractor->extract($query, $params, "json");
if ($res)
{
		echo "HA FUNCIONAT!!!";
		echo ($res);
}
