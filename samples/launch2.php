<?php

$autoload_location=__DIR__.'/../vendor/autoload.php';
if (!is_file($autoload_location)) $autoload_location='../../../autoload.php';	

require_once $autoload_location;
require_once __DIR__.'/../conf/config.php';
require_once __DIR__.'/../conf/bootstrap.php';

use GraphQL\GraphQL;
use Omatech\Editora\Extractor\EditoraData;
use Omatech\Editora\Extractor\EditoraSchema;
use Omatech\Editora\Extractor\Ferretizer;

$params = [
		'class_id' => '140'
	, 'lang' => 'ca'
	, 'debug' => true
];
$show_metadata=false;


 $query='query FetchClassQuery ($class_id:Int, $debug:Boolean, $lang:String) 
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

EditoraData::set_connection($conn);
$result=GraphQL::execute(EditoraSchema::build(), $query, null, $params);
$ferretizer_result=Ferretizer::Ferretize($result['data'], $show_metadata);
if ($ferretizer_result)
{// todo ok 
  print_r($ferretizer_result);		
}
else
{// algun error
  print_r($result);		
}

foreach ($ferretizer_result['instances'] as $element)
{
		if (isset($element['title']))
		{
		  echo $element['title']."\n";
		}
}

