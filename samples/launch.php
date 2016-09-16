<?php

$autoload_location=__DIR__.'/../vendor/autoload.php';
if (!is_file($autoload_location)) $autoload_location='../../../autoload.php';	

require_once $autoload_location;
require_once __DIR__.'/../conf/config.php';
require_once __DIR__.'/../conf/bootstrap.php';

use Omatech\Editora\Extractor\Extractor;

$params = [
	'id' => '1'
	, 'lang' => 'ca'
	, 'debug' => false
	, 'metadata' => true
];

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

$extractor=new Extractor($conn);
$res=$extractor->extract($query, $params, false);
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

