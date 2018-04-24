<?php

$autoload_location = '/vendor/autoload.php';
while (!is_file(__DIR__.$autoload_location)) { $autoload_location='/..'.$autoload_location;}

$config_location = '/conf/config.php';
while (!is_file(__DIR__.$config_location)) { $config_location='/..'.$config_location;}

$bootstrap_location = '/conf/bootstrap.php';
while (!is_file(__DIR__.$bootstrap_location)) { $bootstrap_location='/..'.$bootstrap_location;}

require_once __DIR__.$autoload_location;
require_once __DIR__.$config_location;
require_once __DIR__.$bootstrap_location;

use Omatech\Editora\Extractor\Extractor;
use Omatech\Editora\Extractor\GraphQLPreprocessor;

$start = microtime(true);
$params = ['id' => 25116, 'lang' => 'ca', 'debug' => true, 'metadata' => true];
$query = 'query FetchGraphQuery ($id:Int, $lang:String, $debug:Boolean, $preview:Boolean) {instance(id: $id, lang: $lang, debug: $debug, preview: $preview) 
  {id nom_intern link publishing_begins class_id class_tag class_name all_values_even_null  {atri_tag text_val num_val} 
	relation1 (tag: "obc_section_pages", alias:"submenu", limit:1) {id tag direction limit 
	instances {id nom_intern link class_id class_tag class_name update_timestamp all_values_even_null {atri_tag text_val num_val} 
		relation1 (tag: "obc_section_pages", alias:"pages") {id tag direction limit
		instances {id nom_intern link class_id class_tag class_name update_timestamp all_values_even_null {atri_tag text_val num_val}
	}}
	}} 
	relation2 (tag: "page_groupsmusicians", alias:"groups") {id tag direction limit
    instances {id nom_intern link class_id class_tag class_name update_timestamp all_values_even_null {atri_tag text_val num_val} 
		relation1 (tag: "group_musicians", alias:"musicians") {id tag direction limit
        instances {id nom_intern link class_id class_tag class_name update_timestamp all_values_even_null {atri_tag text_val num_val}
		}}
	}}
}}';


$extractor = new Extractor($conn);
$instance = $extractor->extract($query, $params, 'array', true);

if ($params['debug']) {
	echo "DEBUG\n";
	echo $extractor->debug_messages;
}

echo '<pre>';
print_r($instance);
echo '</pre>';

$end = microtime(true);
$total = $end - $start;
echo "Tiempo total $total segundos";
