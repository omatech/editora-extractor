<?php

namespace Omatech\Editora\Extractor;

class GraphQLPreprocessor {

		private static function get_filter_snippet ($array)
		{
				$graphql = ' (filter: "fields:';
				foreach ($array as $key => $value) {
						$graphql .= $value . '|';
				}
				$graphql = substr($graphql, 0, -1);
				$graphql .= '")';
				return $graphql;
		}
		
		public static function generate($query, $extract_null_values=false, $end = true, $counter = 1) {
				$graphql = $top_args = $top_filter_snippet= "";
				if ($extract_null_values)
				{
					$all_values='all_values_even_null';
				}
				else
				{
					$all_values='all_values';
				}

				if (isset($query['top_args'])) {
						$top_args = $query['top_args'];
				}
				
				if (isset($query['top_instance_all_values_filters'])) 
				{
						$top_filter_snippet=self::get_filter_snippet($query['top_instance_all_values_filters']);										
				}
				
				
				if (isset($query['type']) && $query['type'] === 'instance') {
						$graphql = '
                query FetchGraphQuery ($id:Int, $lang:String, $debug:Boolean, $preview:Boolean) {
                    instance(id: $id, lang: $lang, debug: $debug, preview: $preview' . $top_args . ') 
										{id nom_intern link publishing_begins class_id class_tag class_name '.$all_values.' '.$top_filter_snippet.' {atri_tag text_val num_val}';
				}
				
				if (isset($query['type']) && $query['type'] === 'instances_list') 
				{
					$graphql = '
					query FetchListQuery ($ids:String, $lang:String, $debug:Boolean, $preview:Boolean) {
						instances_list(ids: $ids, lang: $lang, debug: $debug, preview: $preview' . $top_args . ') {
							instances{id nom_intern link publishing_begins class_id class_tag class_name '.$all_values.' '.$top_filter_snippet.' {atri_tag text_val num_val}
					}';
				}


				if (isset($query['type']) && $query['type'] === 'class') {
						$graphql = '
                query FetchGraphQuery ($class_id:Int, $debug:Boolean, $lang:String, $preview:Boolean) {
                    class(class_id: $class_id, lang: $lang, debug: $debug, preview: $preview' . $top_args . ') 
										{
										  class_id tag
											instances {
													id nom_intern link publishing_begins status creation_date class_name class_tag class_id update_timestamp
													'.$all_values.'  '.$top_filter_snippet.' {atri_tag text_val num_val}
								';
				}
				
				if (isset($query['type']) && $query['type'] === 'search') {
						$graphql = '
                query FetchGraphQuery ($query:String, $class_id:Int, $debug:Boolean, $lang:String, $preview:Boolean) {
                    search (query: $query,class_id: $class_id, lang: $lang, debug: $debug, preview: $preview' . $top_args . ') 
										{
										  class_id tag
											instances {
													id nom_intern link publishing_begins status creation_date class_name class_tag class_id update_timestamp
													'.$all_values.'  '.$top_filter_snippet.'  {atri_tag text_val num_val}
								';
				}

				if (isset($query['relations'])) {
						foreach ($query['relations'] as $key => $value) {
								$tag = (is_numeric($key)) ? $value : $key;
								$graphql .= ' relation' . $counter++ . ' (tag: "' . $tag . '"';

								if (is_array($query['relations'][$key])) {
										foreach ($query['relations'][$key] as $key2 => $value2) {
												if ($key2 !== 'relations' && $key2 !== 'filters') {
														if (is_numeric($value2))
														{
																$graphql .= ", " . $key2 . ':'.$value2;
														}
														else
														{
																$graphql .= ", " . $key2 . ':"'.$value2.'"';																
														}
												}
										}
								}

								$graphql .= ') {id tag direction limit
                                instances {id nom_intern link class_id class_tag class_name update_timestamp '.$all_values;
								
								if (isset($query['relations'][$key]['filters'])) 
								{
										$graphql.=self::get_filter_snippet ($query['relations'][$key]['filters']);										
								}
								
								$graphql .= ' {atri_tag text_val num_val}';
								$graphql .= self::generate($value, $extract_null_values, false, 1);
								$graphql .= '}';
						}
				}

				$graphql .= "\n}";

				if (isset($query['type']) && ($query['type'] === 'class' || $query['type'] === 'search'))
				{// close extra bracket in case of class or search
						$graphql .= "\n}";
				}

				if ($end == true) {
						$graphql .= "\n}\n";
						$graphql = trim($graphql);
				}
				return $graphql;
		}

}
