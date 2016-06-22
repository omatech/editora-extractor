<?php
require '../vendor/autoload.php';
require_once '../conf/config.php';
require_once '../conf/bootstrap.php';

use GraphQL\GraphQL;
use Omatech\Editora\Extractor\EditoraData;
use Omatech\Editora\Extractor\EditoraSchema;
use Omatech\Editora\Extractor\Ferretizer;

$params = [
		'class_id' => '150'
	, 'lang' => 'ca'
];

 $query='query FetchClassQuery ($class_id:Int, $lang:String) 
{
  class(class_id: $class_id, lang: $lang) 
	{
    class_id tag
		instances
		{
				id nom_intern link
				all_values (lang: $lang) 
				{
			    atri_tag
			    text_val
        }
				
    relation1 (lang: $lang, tag: "pages", limit:10)
		{
		  id tag direction limit
			instances {
			  id nom_intern link publishing_begins status creation_date
				all_values (lang: $lang, filter: "fields:title|niceurl") 
				{
			    atri_tag
			    text_val
        }
			}
		}





		}
  }
}';

EditoraData::set_connection($conn);
$result=GraphQL::execute(EditoraSchema::build(), $query, null, $params);
//print_r($result);
print_r(Ferretizer::Ferretize($result['data'], true));

