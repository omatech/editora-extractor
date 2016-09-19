<?php
namespace Omatech\Editora\Extractor;
class Ferretizer 
{

		static function debug($str)
		{
				global $debug;
				if (is_array($str))
				{
						//$self::$debug_info.=print_r($str, true);
						if ($debug)
						{
								$debug->debug(print_r($str, true));
						}
						else
						{
								print_r($str);
						}
				}
				else
				{// cas normal, es un string
						//$self::$debug_info.=$str;
						if ($debug)
						{
								$debug->debug($str);
						}
						else
						{
								echo($str);
						}
				}
		}
		
		
		static function FerretizeRel ($relation, $metadata=false)
		{
				$una_rel=array();
				if ($metadata)
				{
						foreach ($relation as $key=>$val)
						{
								if (!is_array($val))
								{
									$una_rel['metadata'][$key]=$val;
								}
						}
				}
				//print_r($relation['instances']);die;
				if (isset($relation['instances']))
				{
						foreach ($relation['instances'] as $inner_inst)
						{
							//echo "Inner Inst\n";
							//print_r($inner_inst);
							if ($inner_inst)
							{
								//echo "Entro!\n";
							  $una_rel['instances'][]=self::FerretizeInstance($inner_inst, $metadata);
							}
						}
				}
				return $una_rel;		
		}

		static function FerretizeInstance ($instance, $metadata=false)
		{
				//echo "pintant la instancia al ferretizer\n";
				//print_r($instance);
				$una_instancia=array();
				
				if (empty($instance['id']) || empty($instance['class_id']) || !is_numeric($instance['id']) || !is_numeric($instance['class_id'])) 
				{
						self::debug("Se debe extraer como mínimo id y class_id de la instancia para poder usar el Ferretizer\n");
						return null;
				}
				
				$una_instancia['id']=$instance['id'];
				if (isset($instance['link']))	$una_instancia['link']=$instance['link'];
				if ($metadata && is_array($instance))
				{
						foreach ($instance as $key=>$val)
						{
								if (!is_array($val))
								{
									$una_instancia['metadata'][$key]=$val;
								}
						}
				}
				//echo "!!! info de la instancia al ferretizer\n";
				//print_r($instance);
				if (isset($instance['all_values']))
				{
						foreach ($instance['all_values'] as $attr_key=>$attr_value)
						{
								if (isset($attr_value['text_val']) && $attr_value['text_val']!='')
								{
										$real_value=$attr_value['text_val'];
								}
								elseif (isset($attr_value['num_val']) && $attr_value['num_val']!='')
								{
										$real_value=$attr_value['num_val'];										
								}
								//echo "--- Al ferretizer tag=".$attr_value['atri_tag']." value=$real_value\n";
								if (isset($attr_value['atri_tag']) && (isset($real_value)))
								{
										$una_instancia[$attr_value['atri_tag']]=$real_value;
								}
						}
				}
				
				if (isset($instance['relation1']))
				{
						$relation=$instance['relation1'][0];						
						$una_instancia['relations'][$relation['tag']]=self::FerretizeRel($relation, $metadata);
				}
				if (isset($instance['relation2']))
				{
						$relation=$instance['relation2'][0];
						$una_instancia['relations'][$relation['tag']]=self::FerretizeRel($relation, $metadata);
				}
				if (isset($instance['relation3']))
				{
						$relation=$instance['relation3'][0];
						$una_instancia['relations'][$relation['tag']]=self::FerretizeRel($relation, $metadata);
				}
				if (isset($instance['relation4']))
				{
						$relation=$instance['relation4'][0];
						$una_instancia['relations'][$relation['tag']]=self::FerretizeRel($relation, $metadata);
				}
				if (isset($instance['relation5']))
				{
						$relation=$instance['relation5'][0];
						$una_instancia['relations'][$relation['tag']]=self::FerretizeRel($relation, $metadata);
				}
				if (isset($instance['relation6']))
				{
						$relation=$instance['relation6'][0];
						$una_instancia['relations'][$relation['tag']]=self::FerretizeRel($relation, $metadata);
				}
				if (isset($instance['relation7']))
				{
						$relation=$instance['relation7'][0];
						$una_instancia['relations'][$relation['tag']]=self::FerretizeRel($relation, $metadata);
				}
				if (isset($instance['relation8']))
				{
						$relation=$instance['relation8'][0];
						$una_instancia['relations'][$relation['tag']]=self::FerretizeRel($relation, $metadata);
				}
				if (isset($instance['relation9']))
				{
						$relation=$instance['relation9'][0];
						$una_instancia['relations'][$relation['tag']]=self::FerretizeRel($relation, $metadata);
				}
				if (isset($instance['relation10']))
				{
						$relation=$instance['relation10'][0];
						$una_instancia['relations'][$relation['tag']]=self::FerretizeRel($relation, $metadata);
				}

				return $una_instancia;
		}
		
		static function FerretizeClass ($class, $metadata=false)
		{
				$una_class=array();
				$una_class['id']=$class['class_id'];
				if ($metadata)
				{
						foreach ($class as $key=>$val)
						{
								if (!is_array($val))
								{
									$una_class['metadata'][$key]=$val;
								}
						}
				}

				if (isset($class['instances']))
				{
						foreach ($class['instances'] as $inner_inst)
						{
						  $tmp_inst=self::FerretizeInstance($inner_inst, $metadata);
							if ($tmp_inst) $una_class['instances'][]=$tmp_inst;
						}
				}
				return $una_class;
		}
		
		static function FerretizeInstancesList ($instances_list, $metadata=false)
		{
				$una_instance_list=array();
				if ($metadata)
				{
						foreach ($instances_list as $key=>$val)
						{
								if (!is_array($val))
								{
									$una_instance_list['metadata'][$key]=$val;
								}
						}
				}

				if (isset($instances_list['instances']))
				{
						foreach ($instances_list['instances'] as $inner_inst)
						{
						  //$una_instance_list['instances'][]=self::FerretizeInstance($inner_inst, $metadata);
							$tmp_inst=self::FerretizeInstance($inner_inst, $metadata);
							if ($tmp_inst) $una_instance_list['instances'][]=$tmp_inst;

						}
				}
				return $una_instance_list;
		}

		static function FerretizeSearch ($search, $metadata=false)
		{
				$una_search=array();
				if (isset($search['query'])) $una_search['query']=$search['query'];
				if (isset($search['class_id'])) $una_search['class_id']=$search['class_id'];
				
				if ($metadata)
				{
						foreach ($search as $key=>$val)
						{
								if (!is_array($val))
								{
									$una_class['metadata'][$key]=$val;
								}
						}
				}

				if (isset($search['instances']))
				{
						foreach ($search['instances'] as $inner_inst)
						{
						  //$una_search['instances'][]=self::FerretizeInstance($inner_inst, $metadata);
							$tmp_inst=self::FerretizeInstance($inner_inst, $metadata);
							if ($tmp_inst) $una_search['instances'][]=$tmp_inst;
						}
				}
				return $una_search;
		}

		static function Ferretize ($data, $metadata=false)
		{
				if (isset($data['class'])) return self::FerretizeClass($data['class'], $metadata);
				if (isset($data['search'])) return self::FerretizeSearch($data['search'], $metadata);
				if (isset($data['instances_list'])) return self::FerretizeInstancesList($data['instances_list'], $metadata);
				if (isset($data['instance'])) return self::FerretizeInstance($data['instance'], $metadata);	
				
				self::debug("ERROR IN FERRETIZER ESTRUCTURA INCORRECTA!\n");
				self::debug($data);
				return false;
		}
}
