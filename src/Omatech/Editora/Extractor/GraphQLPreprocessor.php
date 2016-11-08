<?php
namespace Omatech\Editora\Extractor;


class GraphQLPreprocessor {
		
    public static function generate($query, $end = true, $counter = 1) {
       $graphql = "";

        if(isset($query['type']) && $query['type'] === 'instance') {
            $graphql = '
                query FetchGraphQuery ($id:Int, $lang:String, $debug:Boolean, $preview:Boolean) {
                    instance(id: $id, lang: $lang, debug: $debug, preview: $preview) {id nom_intern link class_id class_tag class_name all_values {atri_tag text_val num_val}';
        }
        if(isset($query['relations'])) {
            foreach ($query['relations'] as $key => $value) {
                $tag = (is_numeric($key)) ? $value : $key;
                $graphql .= ' relation'.$counter++.' (tag: "'.$tag.'"';

                if(is_array($query['relations'][$key])) {
                    foreach($query['relations'][$key] as $key2 => $value2) {
                        if($key2 !== 'relations' && $key2 !== 'filters') {
                            $graphql .= ", ".$key2.':"'.$value2.'"';
                        }
                    }
                }

                $graphql .= ') {id tag direction limit
                                instances {id nom_intern link class_id class_tag class_name all_values';
                if(isset($query['relations'][$key]['filters'])) {
                    $graphql .= ' (filter: "fields:"';
                    foreach ($query['relations'][$key]['filters'] as $key => $value) {
                        $graphql .= $value.'|';
                    }
                    $graphql = substr($graphql, 0, -1);
                    $graphql .= '")';
                }
                $graphql .= ' {atri_tag text_val}';
                $graphql .= self::generate($value, false, 1);
                $graphql .= '}';
            }
        }

        $graphql .= "}";
        if($end == true) {
            $graphql .= "}";
            $graphql = trim($graphql);
        }
        return $graphql;
    }
		
		
}
