<?php

$autoload_location=__DIR__.'/../vendor/autoload.php';
if (!is_file($autoload_location)) $autoload_location='../../../autoload.php';	

require_once $autoload_location;
require_once __DIR__.'/../conf/config.php';
require_once __DIR__.'/../conf/bootstrap.php';

use Omatech\Editora\Extractor\Extractor;

$actividades_ids='100441, 100050, 100067, 100066, 100079, 100078, 100069, 100070, 100071, 100482, 100485, 100072, 100081, 100080, 100487, 100486, 100068, 100083, 100082, 100073, 100085, 100086, 100084, 100074, 100075, 100088, 100087, 100483, 100092, 100484, 100089, 100090, 100099, 100076, 100077';
$params = ['ids' => $actividades_ids, 'lang' => 'ca', 'debug' => true , 'metadata' => true];
$query ='query FetchListQuery ($ids:String, $lang:String, $debug:Boolean) {
			instances_list (ids: $ids, lang: $lang, debug: $debug) {
				instances {
					id nom_intern link class_id publishing_begins status update_timestamp
					all_values {atri_tag text_val}				
						relation1 (tag: "actividad_filtro"){
							id tag direction limit
							instances {id nom_intern link class_id all_values {atri_tag text_val}}
						}
						relation2 (tag: "obra_actividad", limit:1){
							id tag direction limit
							instances {id nom_intern link class_id all_values {atri_tag text_val num_val}}
						}
					}
				}
			}';



$extractor=new Extractor($conn);
$instance=$extractor->extract($query, $params, 'array', true);

echo '<pre>';
print_r($instance);
echo '</pre>';

