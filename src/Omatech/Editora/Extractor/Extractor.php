<?php
namespace Omatech\Editora\Extractor;

use GraphQL\GraphQL;
use Omatech\Editora\Extractor\EditoraData;
use Omatech\Editora\Extractor\EditoraSchema;
use Omatech\Editora\Extractor\Ferretizer;

class Extractor 
{		

		public function __construct($conn)
		{				
				EditoraData::set_connection($conn);
		}
		
		public function extract ($query, $params, $output='array', $ferretizer=true)
		{
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
				
				if ($output=='json')
				{
						return json_encode($result);
				}
				elseif ($output=='xml')
				{
						return json_encode($result);
				}
				else
				{// caso normal, output array
						return $result;
				}				
		}		
}
