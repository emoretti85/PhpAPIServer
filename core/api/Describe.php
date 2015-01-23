<?php
/**
 * Php Api Server
 *
 * This class is a "system API" and provides to retrieve information about an API passed input
 *
 * PHP version 5
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author 	Ettore Moretti <ettoremoretti27{at}gmail{dot}com>
 * @copyright	Ettore Moretti 2015
 * @version	1.0
 * @since  	2015
 */
class Describe {
	public function __construct($nomeApi) {
		$this->apiCheck($nomeApi);
		$met = get_class_methods ( $nomeApi );
		foreach ( $met as $k => $v ) {
			if ($v == "__construct" || $v == "getDesc")
				unset ( $met [$k] );
		}
		foreach ( $met as $key => $value ) {
			$metodi [$key] = array (
					"Name" => $value 
			);
		}
		foreach ( $metodi as $key => $value ) {
			$myReflection = new ReflectionMethod ( $nomeApi, $value ['Name'] );
			$tmp [$key] = $myReflection->getParameters ();
		}
		foreach ( $tmp as $key => $value ) {
			foreach ( $value as $key2 => $value2 ) {
				$metodi [$key] ['Parameter'] [] = $value2->name;
			}
		}
		$return = [ 
				'Api name' => $nomeApi,
				'Callable Method' => $metodi,
				'Short description' => $nomeApi::getDesc () 
		];
		print_r ( json_encode ( $return ) );
		exit ();
	}
	private function apiCheck($nomeApi) {
		$filename =  dirname(__FILE__) ."/".$nomeApi . ".php";
		if (file_exists ( $filename )) {
			include_once $filename;
			if (! class_exists ( $nomeApi, false )) {
				print_r ( json_encode ( [
				'Response' => '0',
				'Error' => 'Malformed or Deprecated API request, "' . $nomeApi . '" isn\'t a valid API.'
						] ) );
						exit ();
			}
		} else {
			print_r ( json_encode ( [
			'Response' => '0',
			'Error' => 'Malformed API request, "' . $nomeApi . '" isn\'t a valid API.'
					] ) );
					exit ();
		}
	}
}