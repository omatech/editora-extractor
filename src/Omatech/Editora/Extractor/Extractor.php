<?php
namespace Omatech\Editora\Extractor;

use GraphQL\GraphQL;
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
								'driver' => 'pdo_mysql',
								'charset' => 'utf8'
						);
						$conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);						
				}
				EditoraData::set_connection($conn);
		}
		
		public function extract ($query, $params, $output='array', $ferretizer=true)
		{// output array as "array", default or json
				$result=GraphQL::execute(EditoraSchema::build(), $query, null, null, $params);
				
				if ($ferretizer)
				{
						$show_metadata=false;
						if ($params['metadata'])
						{
								$show_metadata=true;
						}
						$ferretizer_result=Ferretizer::Ferretize($result['data'], $show_metadata);
						
						if (!$ferretizer_result)
						{// en caso de error
								print_r($result);
						}
						else
						{// todo ok, preparamos el output
								$result=$ferretizer_result;
						}
				}
				
				if ($params['debug'])
				{
						self::$debug_messages=EditoraData::$debug_messages;
				}
				
				if ($output=='json')
				{
						return json_encode($result);
				}
				else
				{// caso normal, output array
						return $result;
				}				
		}		
}
