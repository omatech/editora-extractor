<?php

$autoload_location=__DIR__.'/../vendor/autoload.php';
if (!is_file($autoload_location)) $autoload_location='../../../autoload.php';	

require_once $autoload_location;
require_once __DIR__.'/../conf/config.php';
require_once __DIR__.'/../conf/bootstrap.php';

use Omatech\Editora\Extractor\Extractor;
use Omatech\Editora\Extractor\GraphQLPreprocessor;

$params = [
	'class_id' => '140'
	, 'lang' => 'ca'
	, 'debug' => true
	, 'metadata' => true
];

$magic = ["type" => "class",
	  "top_args" => ', order:"key_fields", order_direction:"desc"',
		"relations" => 
	    ["pages"=>
	      ["limit"=>10
				, "filters"=>["title","niceurl"]
				]
			]
];

$query=GraphQLPreprocessor::generate($magic);

echo $query;
/*$query='query FetchClassQuery ($class_id:Int, $debug:Boolean, $lang:String) 
{
  class(class_id: $class_id, lang: $lang, debug: $debug, order:"key_fields", order_direction:"desc") 
	{
    class_id tag
		instances
		{
				id nom_intern link publishing_begins status creation_date class_name class_tag class_id update_timestamp
				all_values (lang: $lang) {atri_tag text_val}
				
        relation1 (lang: $lang, debug: $debug, tag: "pages", limit:10)
				{
						id tag direction limit
						instances {
								id nom_intern link publishing_begins status creation_date class_name class_tag class_id update_timestamp
								all_values (lang: $lang, filter: "fields:title|niceurl") {atri_tag text_val}
						}
				}
		}
  }
}';
*/

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

foreach ($res['instances'] as $element)
{
		if (isset($element['title']))
		{
		  echo $element['title']."\n";
		}
}

