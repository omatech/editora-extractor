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

 $query='query FetchListQuery ($id:String, $lang:String, $debug:Boolean) {
  instance_list (id: $id, lang: $lang, debug: $debug) {
		id nom_intern link publishing_begins status creation_date class_name class_tag class_id update_timestamp
    all_values (filter: "small") {atri_tag text_val}
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