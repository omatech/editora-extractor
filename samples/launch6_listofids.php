<?php

$autoload_location=__DIR__.'/../vendor/autoload.php';
if (!is_file($autoload_location)) $autoload_location='../../../autoload.php';	

require_once $autoload_location;
require_once __DIR__.'/../conf/config.php';
require_once __DIR__.'/../conf/bootstrap.php';

use Omatech\Editora\Extractor\Extractor;

$params = [
		'ids' => '(784, 787, 100104)'
	, 'lang' => 'ca'
	, 'debug' => false
  , 'metadata' => true	
];

 $query='query FetchListQuery ($ids:String, $lang:String, $debug:Boolean) {
  instances_list (ids: $ids, lang: $lang, debug: $debug) 
	{
	  instances 
		{
				id nom_intern link publishing_begins status update_timestamp
				all_values (filter: "fields:title"){atri_tag text_val}				
		}
  }
}';
 
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