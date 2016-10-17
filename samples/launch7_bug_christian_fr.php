<?php

$autoload_location=__DIR__.'/../vendor/autoload.php';
if (!is_file($autoload_location)) $autoload_location='../../../autoload.php';	

require_once $autoload_location;
require_once __DIR__.'/../conf/config.php';
require_once __DIR__.'/../conf/bootstrap.php';

use Omatech\Editora\Extractor\Extractor;


$inst_id = 1;
$language = "fr";
$preview = false;
	
$params = [
		'id'       => $inst_id,
		'lang'     => $language,
		'preview'  => $preview,
		'debug'    => true,
		'metadata' => true
];

 $query='query FetchHomeQuery ($id:Int, $lang:String, $debug:Boolean, $preview:Boolean) {
    instance(id: $id, lang: $lang, debug: $debug, preview: $preview) {id nom_intern link class_id class_tag class_name all_values {atri_tag text_val num_val}
        relation1 (tag: "home_banner") {id tag direction limit
            instances {id nom_intern link class_id class_tag class_name all_values {atri_tag text_val }}
        }
        relation2 (tag: "home_blocs") {id tag direction limit
            instances {id nom_intern link class_id class_tag class_name all_values {atri_tag text_val}
                relation1 (tag: "llista_vertical_element") {id tag direction limit
                    instances {id nom_intern link class_id class_tag class_name all_values {atri_tag text_val}}
                }
                relation2 (tag: "llista_rols_rol") {id tag direction limit
                    instances {id nom_intern link class_id class_tag class_name all_values {atri_tag text_val}}
                }
                relation3 (tag: "llista_horitzontal_element") {id tag direction limit
                    instances {id nom_intern link class_id class_tag class_name all_values {atri_tag text_val}}
                }
                relation4 (tag: "llista_horitzontal_element") {id tag direction limit
                    instances {id nom_intern link class_id class_tag class_name all_values {atri_tag text_val}}
                }
            }
        }
    }
}';
 
$extractor=new Extractor($conn);
$res=$extractor->extract($query, $params);

if ($params['debug'])
{
		echo "DEBUG\n";
		echo $extractor->debug_messages;
}

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