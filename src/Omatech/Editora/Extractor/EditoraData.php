<?php
namespace Omatech\Editora\Extractor;

class EditoraData
{
		private static $preview=false;
		private static $preview_date='NOW()';
		private static $sql_preview="";
		private static $id=null;
		private static $lang='ALL';
		private static $limit=10000;
		private static $class_id=null;
		private static $tag='';
		private static $sql_tag="";
		private static $sql_class_id="";
		private static $conn;
		
		static function set_connection($conn)
		{
				//var_dump($conn);
				self::$conn=$conn;
		}
	
		static function parse_args($args)
		{
				if (isset($args['id']))
				{
						self::$id=$args['id'];
				}
				
				if (isset($args['lang']))
				{
						self::$lang=$args['lang'];
				}

				if (isset($args['tag']))
				{
						self::$tag=$args['tag'];
						self::$sql_tag="and c.tag='".$args['tag']."'";
				}
				if (isset($args['class_id']))
				{
						self::$class_id=$args['class_id'];
						self::$sql_class_id="and c.id=".$args['class_id'];
				}

				if (isset($args['limit']))
				{
						self::$limit=$args['limit'];
				}

				if (isset($args['preview']))
				{
					self::$preview=$args['preview'];
					if (isset($args['preview_date']))
					{
							self::$preview_date=$args['preview_date'];
					}
				  
					self::$sql_preview=self::get_preview_status_condition()."
				  and DATE_FORMAT(i.publishing_begins,'%Y%m%d%H%i%S') <= ".self::$preview_date."+0
				  and IFNULL(DATE_FORMAT(i.publishing_ends,'%Y%m%d%H%i%S'),now()+1) > ".self::$preview_date."+0
					";
				}				
		}

    static function getInstance($args)
    {		
				//print_r($args);
				self::parse_args($args);
				
				$sql="select i.*, c.name class_name, c.tag class_tag, i.key_fields nom_intern 
				from omp_instances i 
				, omp_classes c
				where i.id=".self::$id."
				and c.id=i.class_id
				".self::$sql_preview."
				";
				//echo $sql;die;
				//$row=Model::get_one($sql);
				$row=self::$conn->fetchAssoc($sql);
				//print_r($row);die;
				$row['args']=$args;
				
				$sql="select niceurl
				from omp_niceurl
				where inst_id=".self::$id."
				and language='".self::$lang."'";
				//$niceurl_row=Model::get_one($sql);
				$niceurl_row=self::$conn->fetchAssoc($sql);
				if ($niceurl_row)
				{
						if (self::$lang=='ALL')
						{
						  $row['link']='/'.$niceurl_row['niceurl'];
						}
						else
						{
						  $row['link']='/'.self::$lang.'/'.$niceurl_row['niceurl'];								
						}
				}
				else
				{
						$row['link']='/'.self::$id;
				}
				
				//print_r([$id=>$row]);
				return $row;
    }


    static function getClass($args)
    {
				
				self::parse_args($args);
				
				if (self::$sql_tag=='' && self::$sql_class_id=='')
				{
						echo "Debes seleccionar un tag o un id de class\n";
						return null;						
				}
								
				$sql="select c.id class_id, c.name, c.tag
				from omp_classes c
				where 1=1
				".self::$sql_tag."
				".self::$sql_class_id."
				";
				//echo $sql;die;
				//$row=Model::get_one($sql);
				$row=self::$conn->fetchAssoc($sql);
				$row['lang']=self::$lang;
				$row['limit']=self::$limit;
				$row['preview']=self::$preview;
				$row['preview_date']=self::$preview_date;
//				echo $sql;
//				print_r($row);
//				die;
				return $row;
    }
		
		
    static function getValues($id, $args)
    {// $id = inst_id 
		// $lang = ALL | es | ca | en ...
		// $filter = detail | resume | small | only-X | except-Y | thinnier-than-i | bigger-than-i | fields:fieldname1|fieldname2
		// where 
		// "detail" are values of attributes marked as detail='Y' in this particular class
		// "resume"  are values of attributes marked as detail='N' in this particular class
		// "small" are values less than 200 characters long
		// "only-X" are values only of the attribute_type=X
		// "except-Y"  are values excluding attribute_type=Y
		// "thinnier-than-i" are values that is length is less than i 
		// "bigger-than-i" are values that is length is bigger than i 
								
				self::parse_args($args);
				
				$filter='all';
				if (isset($args['filter'])) $filter=$args['filter'];
				
				//echo $filter;die;
				$add_sql='';
				if ($filter=='detail')
				{
						$add_sql="
						and ca.detail='Y'
						";
				}
				if ($filter=='resume')
				{
						$add_sql="
						and ca.detail='N'
						";
				}
				if ($filter=='small')
				{
						$add_sql="
						and char_length(v.text_val)<200
						";
				}
				if (substr($filter, 0,5)=='only-')
				{
						$add_sql="
						and a.type='".substr($filter,5)."'
						";
				}				
				if (substr($filter, 0,7)=='except-')
				{
						$add_sql="
						and a.type!='".substr($filter,7)."'
						";
				}
				if (substr($filter, 0,14)=='thinnier-than-')
				{
						$add_sql="
						and char_length(v.text_val)<'".substr($filter,14)."'
						";
				}				
				if (substr($filter, 0,12)=='bigger-than-')
				{
						$add_sql="
						and char_length(v.text_val)>'".substr($filter,12)."'
						";
				}
				
				if (substr($filter, 0,7)=='fields:')
				{
						$field_list_str=substr($filter,7);
						$fields_arr=explode('|', $field_list_str);
						
						$add_sql="
						and a.tag in ('".implode("','", $fields_arr)."')
						";
				}
				
				

				$sql="select v.*, a.name atri_name, a.tag atri_tag, a.type atri_type, a.language atri_language, ca.detail is_detail
				from omp_values v
				, omp_attributes a
				, omp_class_attributes ca
				, omp_instances i
				where v.inst_id=$id
				and v.atri_id=a.id
				and a.language in ('ALL', '".self::$lang."')
				and v.inst_id=i.id
				and i.class_id=ca.class_id
				and a.id=ca.atri_id
				$add_sql
				";
				//echo $sql;die;
				//$attrs=Model::get_data($sql);
				$attrs=self::$conn->fetchAll($sql);
				return $attrs;
    }

		static function getRelations($inst_id, $class_id, $args)
    {// $inst_id = inst_id 
		// $args is an array with
		// lang = ALL | es | ca | en ...
		// tag = tag of the relation
		// limit = number of elements to get
		// filter = TBD				

				self::parse_args($args);
				
				//echo "getRelations $inst_id, $class_id, \n";
				//print_r($args);
				
				$add_sql='';

				$sql="select r.id, r.tag, r.language
				from omp_relations r
				where r.parent_class_id = $class_id
				and r.tag='".self::$tag."'
				";
				//print_r($sql);die;
				//$rel_row=Model::get_one($sql);
				$rel_row=self::$conn->fetchAssoc($sql);
				if ($rel_row)
				{// la instancie es pare, treiem els fills
						$rel_row['direction']='childs';
						$rel_row['limit']=self::$limit;
						$rel_row['inst_id']=$inst_id;
						//$rel_row['limit']=$args['limit'];
						//print_r($rel_row);die;
						return [$rel_row];
				}
				else
				{// mirem si va al reves, si la instancia es filla
						
						$sql="select r.id, r.tag, r.language
						from omp_relations r
						where r.child_class_id = ".self::$class_id."
						and r.tag='".self::$tag."'
						";
						//$rel_row=Model::get_one($sql);
						$rel_row=self::$conn->fetchAssoc($sql);
						if ($rel_row)
						{
								$rel_row['direction']='parents';
								$rel_row['limit']=self::$limit;
								$rel_row['inst_id']=$inst_id;
								//$rel_row['limit']=$args['limit'];
								//print_r($rel_row);die;
								return [$rel_row];
						}
						else
						{// no es cap relacio valida amb la class i el tag donats
								return null;
						}
				}
    }
		
		function getInstacesOfClass($class_id, $args)
		{
				self::parse_args($args);

				$sql="select i.*, c.name class_name, c.tag class_tag, i.key_fields nom_intern 
				from omp_instances i
				, omp_classes c
				where i.class_id=c.id
				and c.id=$class_id

				".self::$sql_preview." 
						
				order by update_date desc
				limit ".self::$limit."
				";
//				echo "getInstancesOfClass $class_id\n";
//				print_r($args);
//				echo "$sql\n";
				return self::$conn->fetchAll($sql);				
		}	
		
		function getRelated ($direction, $rel_id, $inst_id, $args)
		{
				self::parse_args($args);
				//echo "getRelated $direction, $rel_id, $inst_id, $limit\n";die;
				if ($direction=='childs')
				{
				  return self::get_childs ($rel_id, $inst_id, $args);
				}
				if ($direction=='parents')
				{
				  return self::get_parents ($rel_id, $inst_id, $args);
				}
				
		}
		
		function get_childs ($rel_id, $inst_id, $args)
		{
				self::parse_args($args);
				
				$sql="select i.*, c.name class_name, c.tag class_tag, i.key_fields nom_intern 
				from omp_relation_instances ri
				, omp_instances i
				, omp_classes c
				where ri.rel_id=$rel_id
				and ri.parent_inst_id=$inst_id
			  and ri.child_inst_id=i.id
				and i.class_id=c.id
				
				".self::$sql_preview."
						
				order by weight
				limit ".self::$limit."
				";
				//echo "get_childs $rel_id, $inst_id\n";
				//print_r($args);
				//echo $sql."\n";
				return self::$conn->fetchAll($sql);
		}
		
		function get_preview_status_condition ()
		{
				if (!self::$preview)
				{
					return "
					and i.status = 'O'
					";
				}
				return "
				";
		}
		
		function get_parents ($rel_id, $inst_id, $args)
		{
				
				self::parse_args($args);				
				
				$sql="select i.*, c.name class_name, c.tag class_tag, i.key_fields nom_intern 
				from omp_relation_instances ri
				, omp_instances i
				, omp_classes c
				where ri.rel_id=$rel_id
				and ri.child_inst_id=$inst_id
			  and ri.child_inst_id=i.id
				
				".self::$sql_preview."

				and i.class_id=c.id
				order by weight
				limit ".self::$limit."
				";
				return self::$conn->fetchAll($sql);
		}

		
}