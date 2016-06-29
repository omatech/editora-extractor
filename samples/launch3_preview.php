<?php

require '../../../autoload.php';
require_once __DIR__.'/../conf/config.php';
require_once __DIR__.'/../conf/bootstrap.php';

use GraphQL\GraphQL;
use Omatech\Editora\Extractor\EditoraData;
use Omatech\Editora\Extractor\EditoraSchema;
use Omatech\Editora\Extractor\Ferretizer;


$params = [
		'class_id' => '150'
	, 'lang' => 'ca'
	, 'preview' => true
	, 'debug' => true
];
$show_metadata=false;

 $query='query FetchClassQuery ($class_id:Int, $lang:String, $debug:Boolean, $preview:Boolean) 
{
  class(class_id: $class_id, lang: $lang, debug: $debug, preview:$preview) 
	{
    class_id tag
		instances
		{
				id nom_intern link publishing_begins status creation_date class_name class_tag class_id update_timestamp
				all_values {atri_tag text_val}
				
				relation1 (tag: "pages", limit:2)
				{
					id tag direction limit
					instances {
						id nom_intern link publishing_begins status creation_date class_name class_tag class_id update_timestamp
						all_values (lang: $lang, filter: "fields:title|niceurl"){atri_tag text_val}
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