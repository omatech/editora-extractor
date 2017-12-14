# Editora-Extractor

Utilities for extracting info from omatech Editora using GraphQL or a simplified array structure

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

Require in composer omatech/editora-extractor and you are get to go

Review the package omatech/editora-laravel-connector for easy Laravel integration

### Prerequisites

You need a valid connection to an editora database using Doctrine/DBAL

## Usage

### Instance

Get the instance information with the needed relations

$params = [
	'id' => '1'
	, 'lang' => 'ca'
	, 'debug' => true
	, 'metadata' => true
];

 $query='query FetchHomeQuery ($id:Int, $lang:String, $debug:Boolean) {
  instance(id: $id, lang: $lang, debug: $debug) {
		id nom_intern link publishing_begins status creation_date class_name class_tag class_id update_timestamp
    all_values_even_null (filter: "small") {atri_tag text_val}
		
    relation1 (tag: "carrousel", limit:2, alias: "mycarrousel")
		{
		  id tag direction limit
			instances {
				id nom_intern link publishing_begins status creation_date class_name class_tag class_id update_timestamp
				all_values_even_null (filter: "fields:title|subtitle_t") {atri_tag text_val}
			}
		}
		
    relation2 (tag: "news", limit: 5)
		{
		  id tag direction limit
			instances {
			  id nom_intern link
				all_values_even_null (filter: "small") {atri_tag, text_val}
			}
		}

    relation3 (tag: "people", limit:10)
		{
		  id tag direction limit
			instances {
			  id nom_intern link publishing_begins status creation_date
				all_values_even_null {atri_tag text_val}
			}
		}
  }
}';

$extractor=new Extractor($conn);
$res=$extractor->extract($query, $params, 'array', false);

### Call to extractor

$res=$extractor->extract($query, $params, $format, $ferretizer);

$query: is the GraphQL query
$params: array of valid params (see params array section)
$format: ('array' | 'json') Output format. Default 'array'
$ferretizer: (true | false) select if you want to simplify the result using the ferretizer post-processor, usually true. Default true


### Params Array

The available params include:

id: inst_id 
lang: language of the extraction, two letter language code or ALL ('ALL' | 'ca' | 'es' |...)
class_id: class you want to extract the instances from
tag: tag of the class that you want to extract the instance from
metadata: true if you want to extract extra metadata for each object, false otherwise, default false.
preview: true if you want to extract pending objects, false otherwise, default false.
debug: true if you want to get debug information on the extraction, false otherwise, default false. $extractor->debug_messages keeps the debug information


### Installing

TBD

## Contributing

TBD

## Versioning

TBD

## Authors

Agusti Pons
Christian Bohollo
Javier Mogollon


## License

This project is licensed under the MIT License 

