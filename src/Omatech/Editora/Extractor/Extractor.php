<?php
namespace Omatech\Editora\Extractor;

use GraphQL\GraphQL;
use GraphQL\Language\AST\NamedTypeNode;
use GraphQL\Language\AST\NonNullTypeNode;
use GraphQL\Language\AST\OperationDefinitionNode;
use GraphQL\Language\Parser;
use Omatech\Editora\Extractor\EditoraData;
use Omatech\Editora\Extractor\EditoraSchema;
use Omatech\Editora\Extractor\Ferretizer;

class Extractor 
{		
		public $debug_messages='';

		public function __construct($conn)
		{				
				if (is_array($conn))
				{
						$config = new \Doctrine\DBAL\Configuration();
						//..
						$connectionParams = array(
								'dbname' => $conn['dbname'],
								'user' => $conn['dbuser'],
								'password' => $conn['dbpass'],
								'host' => $conn['dbhost'],
								'port' => $conn['dbport'] ?? 3306,
								'driver' => 'pdo_mysql',
								'charset' => 'utf8mb4'
						);
						$conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);						
				}
				EditoraData::set_connection($conn);
		}
		
		public function extract ($query, $params, $output='array', $ferretizer=true)
		{// output array as "array", default or json
				$result=GraphQL::executeQuery(EditoraSchema::build(), $query, null, null, self::coerce_variables($query, $params))->toArray();
				$extra_error_message='';
				
				if ($ferretizer && isset($result['data']))
				{
						$show_metadata=false;
						if (!empty($params['metadata']))
						{
								$show_metadata=true;
						}
						//print_r($result['data']);
						$ferretizer_result=Ferretizer::Ferretize($result['data'], $show_metadata);
						
						if (!$ferretizer_result)
						{// en caso de error
								//print_r($result);
							$extra_error_message=print_r($result, true);
							$result=false;
						}
						else
						{// todo ok, preparamos el output
							$result=$ferretizer_result;
						}
				}
				
				if (!empty($params['debug']))
				{
						$this->debug_messages=EditoraData::$debug_messages;
						$this->debug_messages.=$extra_error_message;
				}
				EditoraData::reset_values();
				
				if ($output=='json')
				{
						return json_encode($result);
				}
				else
				{// caso normal, output array
						return $result;
				}				
		}		

		// graphql-php 15 no accepta "123" per a una variable Int; adaptem els params als tipus declarats a la query
		// amb les mateixes regles que feia servir graphql-php 0.7 (parseValue d'Int, Boolean i String)
		private static function coerce_variables($query, $params)
		{
				$variables=[];
				foreach (Parser::parse($query)->definitions as $definition)
				{
						if (!$definition instanceof OperationDefinitionNode) continue;
						foreach ($definition->variableDefinitions as $variable)
						{
								$name=$variable->variable->name->value;
								if (!array_key_exists($name, $params)) continue;
								$type=$variable->type instanceof NonNullTypeNode ? $variable->type->type : $variable->type;
								$value=$params[$name];
								if ($value!==null && $type instanceof NamedTypeNode)
								{
										switch ($type->name->value)
										{
												case 'Int':
														if (is_bool($value) || (is_numeric($value) && $value<=PHP_INT_MAX && $value>=PHP_INT_MIN)) $value=(int)$value;
														break; // si no és vàlid es deixa tal qual i graphql-php el rebutja, com la 0.7
												case 'Boolean': $value=(bool)$value; break;
												case 'String':
														if (is_bool($value)) $value=$value ? 'true' : 'false';
														elseif (is_scalar($value)) $value=(string)$value;
														break;
										}
								}
								$variables[$name]=$value;
						}
				}
				return $variables;
		}
}
