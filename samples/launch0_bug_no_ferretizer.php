<?php

$autoload_location=__DIR__.'/../vendor/autoload.php';
if (!is_file($autoload_location)) $autoload_location='../../../autoload.php';	

require_once $autoload_location;
require_once __DIR__.'/../conf/config.php';
require_once __DIR__.'/../conf/bootstrap.php';

use Omatech\Editora\Extractor\Extractor;

$params = ['id' =>100005, 'lang' => 'ca', 'debug'=>false , 'metadata' => true];
	$query='query FetchHomeQuery ($id:Int, $lang:String, $debug:Boolean) {
				instance(id: $id, lang: $lang, debug: $debug) {
						id nom_intern link class_id
						all_values {atri_tag text_val}
		
					relation1 (tag: "obra_temporada"){
						id tag direction limit
						instances {id nom_intern link class_id all_values {atri_tag text_val}
							relation1 (tag: "obra_filtro"){
								id tag direction limit
								instances {id nom_intern link class_id all_values {atri_tag text_val}
								}
							}
						}
					}
					relation2 (tag: "temporada_filtro"){
						id tag direction limit
						instances {id nom_intern link class_id all_values {atri_tag text_val}
						}
					}
				}
			}';


$extractor=new Extractor($conn);
$instance=$extractor->extract($query, $params, 'array', true);

if ($params['debug'])
{
		echo "DEBUG\n";
		echo $extractor->debug_messages;
}

echo '<pre>';
print_r($instance);
echo '</pre>';

