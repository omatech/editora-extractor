<?php

$autoload_location=__DIR__.'/../vendor/autoload.php';
if (!is_file($autoload_location)) $autoload_location='../../../autoload.php';	

require_once $autoload_location;
require_once __DIR__.'/../conf/config.php';
require_once __DIR__.'/../conf/bootstrap.php';

use Omatech\Editora\Extractor\Extractor;
	
$params = [
		'class_id' => '80'
		, 'lang' => 'ca'
		, 'preview' => true
		, 'debug' => true
	  , 'metadata' => true
];

$show_metadata=true;

 $query='query FetchClassQuery ($class_id:Int, $lang:String, $debug:Boolean, $preview:Boolean) 
{
  class(class_id: $class_id, lang: $lang, debug: $debug, preview:$preview) 
	{
    class_id tag
		instances
		{
				id nom_intern link publishing_begins status creation_date class_name class_tag class_id update_timestamp
				all_values {atri_tag text_val}
				relation1 (tag: "obra_actividad")
				{
				  id tag direction limit
					instances {id nom_intern link class_id all_values {atri_tag text_val num_val}}
				}
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
