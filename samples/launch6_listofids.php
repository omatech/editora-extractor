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
		'ids' => '(784, 787, 100104)'
	, 'lang' => 'ca'
	, 'debug' => true
];
$show_metadata=true;

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