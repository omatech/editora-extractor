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


$query = [
            "type"      => "instance",
            "relations" => [
                'Home_blocs'=> [
                    'relations' => [
                        'Blocimatges_destinationpage1_fila1' => [
                            "relations" => [
                                "portal_subseccio" => [
                                    "relations" => [
                                        "Subseccio_paginaSubSeccio"
                                    ]
                                ]
                            ]
                        ],
                        'Blocimatges_destinationpage2_fila1' => [
                            "relations" => [
                                "portal_subseccio" => [
                                    "relations" => [
                                        "Subseccio_paginaSubSeccio"
                                    ]
                                ]
                            ]
                        ],
                        'Blocimatges_destinationpage3_fila1' => [
                            "relations" => [
                                "portal_subseccio" => [
                                    "relations" => [
                                        "Subseccio_paginaSubSeccio"
                                    ]
                                ]
                            ]
                        ],
                        'Blocimatges_destinationpage1_fila2' => [
                            "relations" => [
                                "portal_subseccio" => [
                                    "relations" => [
                                        "Subseccio_paginaSubSeccio"
                                    ]
                                ]
                            ]
                        ],
                        'Blocimatges_destinationpage2_fila2' => [
                            "relations" => [
                                "portal_subseccio" => [
                                    "relations" => [
                                        "Subseccio_paginaSubSeccio"
                                    ]
                                ]
                            ]
                        ],
                        'Bloclink_destinationpage'

                    ]
                ],
                'Home_Seccionoticies' => ['limit' => 1],
                'Home_paginas' => ['limit' => 1]
            ]
        ];


				$query=\Omatech\Editora\Extractor\GraphQLPreprocessor::generate($query, true);	
	

$extractor=new Extractor($conn);
$res=$extractor->extract($query, $params, 'array', true);

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

