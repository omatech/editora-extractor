<?php

require __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/conf/config.php';
require_once __DIR__.'/conf/bootstrap.php';

use GraphQL\GraphQL;
use Omatech\Editora\Extractor\EditoraData;
use Omatech\Editora\Extractor\EditoraSchema;
use Omatech\Editora\Extractor\Ferretizer;


$params = [
		'id' => '1'
	, 'lang' => 'ca'
];

 $query='query FetchHomeQuery ($id:Int, $lang:String) {
  instance(id: $id, lang: $lang) {
    id nom_intern link
    all_values (lang: $lang, filter: "small") {
			atri_tag
			text_val
    }
		
    relation1 (lang: $lang, tag: "carrousel")
		{
		  id tag direction limit
			instances {
			  id nom_intern link publishing_begins status creation_date
				all_values (lang: $lang, filter: "fields:title|subtitle_t") 
				{
			    atri_tag
			    text_val
        }
			}
		}
		
    relation2 (tag: "values", limit: 2)
		{
		  id tag direction limit
			instances {
			  id nom_intern link
				all_values (lang: $lang, filter: "small") 
				{
			    atri_tag
			    text_val
        }
			}
		}

    relation3 (lang: $lang, tag: "know_us", limit:1)
		{
		  id tag direction limit
			instances {
			  id nom_intern link publishing_begins status creation_date
				all_values (lang: $lang) 
				{
			    atri_tag
			    text_val
        }
			}
			


		}

  }
}';

EditoraData::set_connection($conn);
$result=GraphQL::execute(EditoraSchema::build(), $query, null, $params);
//print_r($result);
print_r(Ferretizer::Ferretize($result['data'], true));

