<?php

$autoload_location=__DIR__.'/../vendor/autoload.php';
if (!is_file($autoload_location)) $autoload_location='../../../autoload.php';	

require_once $autoload_location;
require_once __DIR__.'/../conf/config.php';
require_once __DIR__.'/../conf/bootstrap.php';

use Omatech\Editora\Extractor\Extractor;

$params = [
	'class_id' => '140'
	, 'lang' => 'ca'
	, 'debug' => false
	, 'metadata' => true
];

 $query='query FetchClassQuery ($class_id:Int, $debug:Boolean, $lang:String) 
{
  class(class_id: $class_id, lang: $lang, debug: $debug, order:"key_fields", order_direction:"desc") 
	{
    class_id tag
		instances
		{
				id nom_intern link publishing_begins status creation_date class_name class_tag class_id update_timestamp
				all_values_even_null (lang: $lang) {atri_tag text_val}
				
        relation1 (lang: $lang, debug: $debug, tag: "pages", limit:10)
				{
						id tag direction limit
						instances {
								id nom_intern link publishing_begins status creation_date class_name class_tag class_id update_timestamp
								all_values_even_null (lang: $lang, filter: "fields:title|niceurl") {atri_tag text_val}
						}
				}
		}
  }
}';

$extractor=new Extractor($conn);
$res=$extractor->extract($query, $params, 'array', true);

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

foreach ($res['instances'] as $element)
{
		if (isset($element['title']))
		{
		  echo $element['title']."\n";
		}
}

