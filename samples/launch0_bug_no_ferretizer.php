<?php

$autoload_location=__DIR__.'/../vendor/autoload.php';
if (!is_file($autoload_location)) $autoload_location='../../../autoload.php';	

require_once $autoload_location;
require_once __DIR__.'/../conf/config.php';
require_once __DIR__.'/../conf/bootstrap.php';

use Omatech\Editora\Extractor\Extractor;

$inst_id=100068;
$lang='ca';

$params = ['id' => $inst_id, 'lang' => $lang, 'debug'=>false, 'preview' => false, 'metadata' => true];
$query='query FetchHomeQuery ($id:Int, $lang:String, $debug:Boolean, $preview:Boolean) {
			instance(id: $id, lang: $lang, debug: $debug, preview:$preview) {id nom_intern link class_id all_values {atri_tag text_val num_val}

				relation1 (tag: "obra_actividad", limit:1){id tag direction limit
					instances {id nom_intern link class_id all_values {atri_tag text_val num_val}
						relation1 (tag: "obra_temporada", limit:1){id tag direction limit
							instances {id nom_intern link class_id all_values (filter: "fields:title|subtitle_t"){atri_tag text_val}}
						}
					}
				}
				relation2 (tag: "actividad_multimedia", alias: "multimedia"){id tag direction limit
					instances {id nom_intern link class_id class_name all_values{atri_tag text_val}}
				}
				relation3 (tag: "actividad_actividad"){id tag direction limit
					instances {id nom_intern link class_id class_name all_values{atri_tag text_val}}
				}
				relation4 (tag: "temporada_actividades", limit:1){id tag direction limit
					instances {id nom_intern link class_id class_name all_values{atri_tag text_val}}
				}
			}
		}';

$extractor=new Extractor($conn);
$instance=$extractor->extract($query, $params);

echo '<pre>';
print_r($instance);
echo '</pre>';

