<?php

$autoload_location=__DIR__.'/../vendor/autoload.php';
if (!is_file($autoload_location)) $autoload_location='../../../autoload.php';	

require_once $autoload_location;
require_once __DIR__.'/../conf/config.php';
require_once __DIR__.'/../conf/bootstrap.php';

use Omatech\Editora\Extractor\Extractor;

$params = [
	'class_id' => '80'
	, 'query' => 'Brossa'
	, 'lang' => 'ca'
	, 'preview' => true
	, 'debug' => true
	, 'metadata' => true	
];


$query='query FetchSearchQuery ($query:String, $class_id:Int, $lang:String, $debug:Boolean, $preview:Boolean) 
{
  search(query: $query, class_id: $class_id, lang: $lang, debug: $debug, preview:$preview) 
	{
    instances
		{
				id nom_intern link publishing_begins status creation_date class_name class_tag class_id update_timestamp
				all_values (filter: "fields:title|subtitle"){atri_tag text_val}				
		}
  }
}';

$extractor=new Extractor($conn);
$res=$extractor->extract($query, $params);
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
