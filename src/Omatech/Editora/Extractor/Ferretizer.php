<?php
namespace Omatech\Editora\Extractor;
class Ferretizer {
		//put your code here
		
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
							echo "Inner Inst\n";
							print_r($inner_inst);
							if ($inner_inst)
							{
								echo "Entro!\n";
							  $una_rel['instances'][]=self::FerretizeInstance($inner_inst, $metadata);
							}
						}
				}
				return $una_rel;		
		}

		static function FerretizeInstance ($instance, $metadata=false)
		{
				echo "pintant la instancia al ferretizer\n";
				print_r($instance);
				$una_instancia=array();
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
						$relation=$instance['relation4'][0];
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
						  $una_class['instances'][]=self::FerretizeInstance($inner_inst, $metadata);
						}
				}
				return $una_class;
		}
		
		static function Ferretize ($data, $metadata=false)
		{
				if (isset($data['class'])) return self::FerretizeClass($data['class'], $metadata);
				if (isset($data['instance'])) return self::FerretizeInstance($data['instance'], $metadata);	
				
				echo "ERROR IN FERRETIZER ESTRUCTURA INCORRECTA!\n";
				var_dump($data);
				return false;
		}
}
