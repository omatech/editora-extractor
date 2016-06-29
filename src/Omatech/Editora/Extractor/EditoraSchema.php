<?php
namespace Omatech\Editora\Extractor;

use GraphQL\Schema;
use GraphQL\Type\Definition\EnumType;
use GraphQL\Type\Definition\InterfaceType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;


class EditoraSchema
{
    public static function build()
    {						
				$ValueType = new ObjectType([
            'name' => 'Value',
            'description' => 'Value',
            'fields' => [
                'id' => [
                    'type' => new NonNull(Type::int()),
                    'description' => 'The id of the value.',
                ],
                'atri_id' => [
                    'type' => Type::int(),
                    'description' => 'The attribute of the value.',
                ],
                'atri_name' => [
                    'type' => Type::string(),
                    'description' => 'Attribute name',
                ],
                'atri_tag' => [
                    'type' => Type::string(),
                    'description' => 'Attribute tag',
                ],
                'atri_type' => [
                    'type' => Type::string(),
                    'description' => 'Attribute Type',
                ],
                'atri_language' => [
                    'type' => Type::string(),
                    'description' => 'Attribute language',
                ],
                'inst_id' => [
                    'type' => Type::int(),
                    'description' => 'The instance_id in which the value is contained.',
                ],
                'text_val' => [
                    'type' => Type::string(),
                    'description' => 'The text_val of the value.',
                ],
                'num_val' => [
                    'type' => Type::string(),
                    'description' => 'The num_val of the value.',
                ],
                'date_val' => [
                    'type' => Type::string(),
                    'description' => 'The date_val of the value.',
                ],
                'img_info' => [
                    'type' => Type::string(),
                    'description' => 'Image info of the value, only valid for images.',
                ],
                'is_detail' => [
                    'type' => Type::string(),
                    'description' => 'Is detail? Y or N',
                ],
                'cache_time' => [
                    'type' => Type::int(),
                    'description' => 'Cache Time',
                ],
                'cache_status' => [
                    'type' => Type::string(),
                    'description' => 'Cache status (hit|miss)',
                ],
            ],
        ]);
						
				$RelationType = new ObjectType([
            'name' => 'Relation',
            'description' => 'Relation Instance',
            'fields' => [
                'id' => [
                    'type' => new NonNull(Type::int()),
                    'description' => 'The id of the relation.',
                ],
                'tag' => [
                    'type' => new NonNull(Type::string()),
                    'description' => 'Relation tag',
                ],
                'language' => [
                    'type' => Type::string(),
                    'description' => 'Relation language',
                ],
                'direction' => [
                    'type' => new NonNull(Type::string()),
                    'description' => 'Relation direction',
                ],
                'limit' => [
                    'type' => Type::int(),
                    'description' => 'limit of extraction',
                ],
                'inst_id' => [
                    'type' => Type::int(),
                    'description' => 'initial inst_id, the driver instance',
                ],
                'preview' => [
                    'type' => Type::boolean(),
                    'description' => 'Preview true or false, default false',
                ],
                'preview_date' => [
                    'type' => Type::string(),
                    'description' => 'Preview date in %Y%m%d%H%i%S format',
                ],
                
								'instances' => [
                    'type' => function () use (&$InstanceType) {
                        return Type::listOf($InstanceType);
                    },
                    'description' => 'The related children or parents of this relation.',
											
                    'args' => [
                        'filter' => [
                            'name' => 'filter',
                            'description' => 'filter some fields all|detail|resume default all',
                            'type' => Type::String()
                        ],
                        'lang' => [
                            'name' => 'lang',
                            'description' => 'Language of the extraction',
                            'type' => Type::String()
                        ],
												'limit' => [
														'type' => Type::int(),
														'description' => 'limit of extraction',
												],

												'preview' => [
														'type' => Type::boolean(),
														'description' => 'Preview true or false, default false',
												],
												'preview_date' => [
														'type' => Type::string(),
														'description' => 'Preview date in %Y%m%d%H%i%S format',
												],
											
											
                    ],
											
											
                    'resolve' => function ($relation, $args) {
												//echo "instance resolve\n";
												//print_r($instance);
                        $insts=EditoraData::getRelated($relation['direction'], $relation['id'], $relation['inst_id'], $args);
												//echo "attrs\n";
												//print_r($attrs);
												if ($insts) return $insts;
												return null;
                    },
								],

            ],
        ]);
						
				
				$ClassType = new ObjectType([
            'name' => 'class',
            'description' => 'Class',
            'fields' => [
                'class_id' => [
                    'type' => new NonNull(Type::int()),
                    'description' => 'The id of the relation.',
                ],
                'tag' => [
                    'type' => new NonNull(Type::string()),
                    'description' => 'Relation tag',
                ],
                'language' => [
                    'type' => Type::string(),
                    'description' => 'Relation language',
                ],
                'limit' => [
                    'type' => Type::int(),
                    'description' => 'limit of extraction',
                ],

                'preview' => [
                    'type' => Type::boolean(),
                    'description' => 'Preview true or false, default false',
                ],
                'preview_date' => [
                    'type' => Type::string(),
                    'description' => 'Preview date in %Y%m%d%H%i%S format',
                ],
							
								'instances' => [
                    'type' => function () use (&$InstanceType) {
                        return Type::listOf($InstanceType);
                    },
                    'description' => 'The instances of this class.',
											
                    'args' => [
                        'filter' => [
                            'name' => 'filter',
                            'description' => 'filter some fields all|detail|resume default all',
                            'type' => Type::String()
                        ],
                        'lang' => [
                            'name' => 'lang',
                            'description' => 'Language of the extraction',
                            'type' => Type::String()
                        ],
												'limit' => [
														'type' => Type::int(),
														'description' => 'limit of extraction',
												],

												'preview' => [
														'type' => Type::boolean(),
														'description' => 'Preview true or false, default false',
												],
												'preview_date' => [
														'type' => Type::string(),
														'description' => 'Preview date in %Y%m%d%H%i%S format',
												],
											
											
                    ],
											
                    'resolve' => function ($class, $args) {
												//echo "instance resolve\n";
												//print_r($class);
												$insts=EditoraData::getInstacesOfClass($class['class_id'], $class);
												//echo "attrs\n";
												//print_r($insts);
												if ($insts)	return $insts;
												return null;
                    },
								],

            ],
        ]);

										
				$InstanceType = new ObjectType([
            'name' => 'Instance',
            'description' => 'Instance of any class',
            'fields' => [
                'id' => [
                    'type' => new NonNull(Type::int()),
                    'description' => 'The id of instance.',
                ],
                'class_id' => [
                    'type' => new NonNull(Type::int()),
                    'description' => 'The class_id of instance.',
                ],
                'class_name' => [
                    'type' => new NonNull(Type::string()),
                    'description' => 'The class_name of instance.',
                ],
                'class_tag' => [
                    'type' => new NonNull(Type::string()),
                    'description' => 'The class_tag of instance.',
                ],
                'key_fields' => [
                    'type' => Type::string(),
                    'description' => 'The internal name of the instance.',
                ],
                'nom_intern' => [
                    'type' => Type::string(),
                    'description' => 'The internal name of the instance.',
                ],
                'status' => [
                    'type' => Type::string(),
                    'description' => 'The status of the instance.',
                ],
                'publishing_begins' => [
                    'type' => Type::string(),
                    'description' => 'The publishing start of the instance.',
                ],
                'publishing_ends' => [
                    'type' => Type::string(),
                    'description' => 'The publishing start of the instance.',
                ],
                'creation_date' => [
                    'type' => Type::string(),
                    'description' => 'The creation of the instance.',
                ],
                'update_date' => [
                    'type' => Type::string(),
                    'description' => 'The update of the instance.',
                ],							
                'update_timestamp' => [
                    'type' => Type::int(),
                    'description' => 'The update of the instance in unix timestamp.',
                ],
                'nice_url' => [
                    'type' => Type::string(),
                    'description' => 'The niceurl of the instance.',
                ],
                'link' => [
                    'type' => Type::string(),
                    'description' => 'The link to the the instance.',
                ],
                'all_values' => [
                    'type' => Type::listOf($ValueType),
                    'description' => 'The attributes of the instance.',
                    'args' => [
                        'filter' => [
                            'name' => 'filter',
                            'description' => 'filter some fields all|detail|resume default all',
                            'type' => Type::String()
                        ],
                        'lang' => [
                            'name' => 'lang',
                            'description' => 'Language of the extraction',
                            'type' => Type::String()
                        ]
                    ],
                    'resolve' => function ($instance, $args) {
												//print_r($args);
												//echo "Instance in EditoraSchema\n";
												//print_r($instance);
												//die;
											  $attrs=EditoraData::getValues($instance['id'], $instance['update_timestamp'], $args);	
												//print_r($attrs);
												
												if ($attrs) return $attrs;
												return null;
                    },
                ],
                'relation1' => [
                    'type' => Type::listOf($RelationType),
                    'description' => 'The children of the instance.',
                    'args' => [
                        'tag' => [
                            'name' => 'tag',
                            'description' => 'tag of the child relation',
                            'type' => new NonNull(Type::String())
                        ],
                        'limit' => [
                            'name' => 'limit',
                            'description' => 'number of children to get, default 1000',
                            'type' => Type::Int()
                        ],
                        'filter' => [
                            'name' => 'filter',
                            'description' => 'filter some fields all|detail|resume default all',
                            'type' => Type::String()
                        ],
                        'lang' => [
                            'name' => 'lang',
                            'description' => 'Language of the extraction',
                            'type' => Type::String()
                        ],
											
												'preview' => [
														'type' => Type::boolean(),
														'description' => 'Preview true or false, default false',
												],
												'preview_date' => [
														'type' => Type::string(),
														'description' => 'Preview date in %Y%m%d%H%i%S format',
												],										
                    ],
                    'resolve' => function ($instance, $args) {
												//echo "aqui tinc aquests args\n";
												//print_r($args);
                        $related=EditoraData::getRelations($instance['id'], $instance['class_id'], $args);
												//print_r($related);
												if ($related) return $related;
												return null;
                    },
                ],
											
                'relation2' => [
                    'type' => Type::listOf($RelationType),
                    'description' => 'The children of the instance.',
                    'args' => [
                        'tag' => [
                            'name' => 'tag',
                            'description' => 'tag of the child relation',
                            'type' => new NonNull(Type::String())
                        ],
                        'limit' => [
                            'name' => 'limit',
                            'description' => 'number of children to get, default 1000',
                            'type' => Type::Int()
                        ],
                        'filter' => [
                            'name' => 'filter',
                            'description' => 'filter some fields all|detail|resume default all',
                            'type' => Type::String()
                        ],
                        'lang' => [
                            'name' => 'lang',
                            'description' => 'Language of the extraction',
                            'type' => Type::String()
                        ],
											
												'preview' => [
														'type' => Type::boolean(),
														'description' => 'Preview true or false, default false',
												],
												'preview_date' => [
														'type' => Type::string(),
														'description' => 'Preview date in %Y%m%d%H%i%S format',
												],
											
											
                    ],
                    'resolve' => function ($instance, $args) {
                        $related=EditoraData::getRelations($instance['id'], $instance['class_id'], $args);
												//print_r($related);
												if ($related) return $related;
												return null;
                    },
                ],

                'relation3' => [
                    'type' => Type::listOf($RelationType),
                    'description' => 'The children of the instance.',
                    'args' => [
                        'tag' => [
                            'name' => 'tag',
                            'description' => 'tag of the child relation',
                            'type' => new NonNull(Type::String())
                        ],
                        'limit' => [
                            'name' => 'limit',
                            'description' => 'number of children to get, default 1000',
                            'type' => Type::Int()
                        ],
                        'filter' => [
                            'name' => 'filter',
                            'description' => 'filter some fields all|detail|resume default all',
                            'type' => Type::String()
                        ],
                        'lang' => [
                            'name' => 'lang',
                            'description' => 'Language of the extraction',
                            'type' => Type::String()
                        ],
											
												'preview' => [
														'type' => Type::boolean(),
														'description' => 'Preview true or false, default false',
												],
												'preview_date' => [
														'type' => Type::string(),
														'description' => 'Preview date in %Y%m%d%H%i%S format',
												],
											
											
                    ],
                    'resolve' => function ($instance, $args) {
                        $related=EditoraData::getRelations($instance['id'], $instance['class_id'], $args);
												//print_r($related);
												if ($related) return $related;
												return null;
                    },
                ],
											
                'relation4' => [
                    'type' => Type::listOf($RelationType),
                    'description' => 'The children of the instance.',
                    'args' => [
                        'tag' => [
                            'name' => 'tag',
                            'description' => 'tag of the child relation',
                            'type' => new NonNull(Type::String())
                        ],
                        'limit' => [
                            'name' => 'limit',
                            'description' => 'number of children to get, default 1000',
                            'type' => Type::Int()
                        ],
                        'filter' => [
                            'name' => 'filter',
                            'description' => 'filter some fields all|detail|resume default all',
                            'type' => Type::String()
                        ],
                        'lang' => [
                            'name' => 'lang',
                            'description' => 'Language of the extraction',
                            'type' => Type::String()
                        ],
											
												'preview' => [
														'type' => Type::boolean(),
														'description' => 'Preview true or false, default false',
												],
												'preview_date' => [
														'type' => Type::string(),
														'description' => 'Preview date in %Y%m%d%H%i%S format',
												],
											
											
                    ],
                    'resolve' => function ($instance, $args) {
                        $related=EditoraData::getRelations($instance['id'], $instance['class_id'], $args);
												//print_r($related);
												if ($related) return $related;
												return null;
                    },
                ],
											
                'relation5' => [
                    'type' => Type::listOf($RelationType),
                    'description' => 'The children of the instance.',
                    'args' => [
                        'tag' => [
                            'name' => 'tag',
                            'description' => 'tag of the child relation',
                            'type' => new NonNull(Type::String())
                        ],
                        'limit' => [
                            'name' => 'limit',
                            'description' => 'number of children to get, default 1000',
                            'type' => Type::Int()
                        ],
                        'filter' => [
                            'name' => 'filter',
                            'description' => 'filter some fields all|detail|resume default all',
                            'type' => Type::String()
                        ],
                        'lang' => [
                            'name' => 'lang',
                            'description' => 'Language of the extraction',
                            'type' => Type::String()
                        ],
											
												'preview' => [
														'type' => Type::boolean(),
														'description' => 'Preview true or false, default false',
												],
												'preview_date' => [
														'type' => Type::string(),
														'description' => 'Preview date in %Y%m%d%H%i%S format',
												],
											
											
											
                    ],
                    'resolve' => function ($instance, $args) {
                        $related=EditoraData::getRelations($instance['id'], $instance['class_id'], $args);
												//print_r($related);
												if ($related) return $related;
												return null;
                    },
                ],
            ],
        ]);


										
        $queryType = new ObjectType([
            'name' => 'Query',
            'fields' => [
                'instance' => [
                    'type' => $InstanceType,
                    'args' => [
                        'id' => [
                            'name' => 'id',
                            'description' => 'id of the Instance',
                            'type' => Type::Int()
                        ],
                        'lang' => [
                            'name' => 'lang',
                            'description' => 'Language of the extraction',
                            'type' => Type::String()
                        ],

                        'debug' => [
                            'name' => 'debug',
                            'description' => 'Sets the debug flag if 1 or true is passed',
                            'type' => Type::boolean()
                        ],

											'preview' => [
														'type' => Type::boolean(),
														'description' => 'Preview true or false, default false',
												],
												'preview_date' => [
														'type' => Type::string(),
														'description' => 'Preview date in %Y%m%d%H%i%S format',
												],
					
                    ],
									
									
                    'resolve' => function ($root, $args) {
                        $instance = EditoraData::getInstance($args);
												if ($instance)  return $instance;
												return null;
                        //return isset($instance[$args['id']]) ? $instance[$args['id']] : null;
                    }
                ],									
									
                'class' => [
                    'type' => $ClassType,
                    'args' => [
                        'class_id' => [
                            'name' => 'class_id',
                            'description' => 'id of the Class',
                            'type' => Type::Int()
                        ],
                        'tag' => [
                            'name' => 'tag',
                            'description' => 'tag of the Class',
                            'type' => Type::String()
                        ],
                        'lang' => [
                            'name' => 'lang',
                            'description' => 'Language of the extraction',
                            'type' => Type::String()
                        ],
											
                        'debug' => [
                            'name' => 'debug',
                            'description' => 'Sets the debug flag if 1 or true is passed',
                            'type' => Type::boolean()
                        ],

											'preview' => [
														'type' => Type::boolean(),
														'description' => 'Preview true or false, default false',
												],
												'preview_date' => [
														'type' => Type::string(),
														'description' => 'Preview date in %Y%m%d%H%i%S format',
												],
									
									
									
                    ],
                    'resolve' => function ($root, $args) {
                        $class = EditoraData::getClass($args);
//												echo "Al query type\n";
//												print_r($class);
//												die;
												if ($class) return $class;
												return null;
                        //return isset($instance[$args['id']]) ? $instance[$args['id']] : null;
                    }
                ],									
									
            ]
        ]);
								
								
								

        return new Schema($queryType);
    }
}
