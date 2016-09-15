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

$context = [	
	'lang' => 'ca'
	, 'debug' => true
];

$params = [
		'id' => '1'
];

$show_metadata=true;

 $query='query FetchHomeQuery ($id:Int, $lang:String, $debug:Boolean) {
  instance(id: $id, lang: $lang, debug: $debug) {
		id nom_intern link publishing_begins status creation_date class_name class_tag class_id update_timestamp
    all_values (filter: "small") {atri_tag text_val}
		
    relation1 (tag: "carrousel", limit:2, alias: "mycarrousel")
		{
		  id tag direction limit
			instances {
				id nom_intern link publishing_begins status creation_date class_name class_tag class_id update_timestamp
				all_values (filter: "fields:title|subtitle_t") {atri_tag text_val}
			}
		}
		
    relation2 (tag: "values", limit: 5)
		{
		  id tag direction limit
			instances {
			  id nom_intern link
				all_values (filter: "small") {atri_tag, text_val}
			}
		}

    relation3 (tag: "know_us", limit:1)
		{
		  id tag direction limit
			instances {
			  id nom_intern link publishing_begins status creation_date
				all_values {atri_tag text_val}
			}
		}
  }
}';

EditoraData::set_connection($conn);
$result=GraphQL::execute(EditoraSchema::build(), $query, null, $context, $params);
//print_r($result);die;
$ferretizer_result=Ferretizer::Ferretize($result['data'], $show_metadata);
if ($ferretizer_result)
{// todo ok 
  print_r($ferretizer_result);		
}
else
{// algun error
  print_r($result);		
}

