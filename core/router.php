<?php
/**
 * Php Api Server
 * [IT]
 * Questa classe permette di effettuare i controlli sulla richiesta giunta al server, 
 * controlla l'esistenza dell'API, del metodo richiesto e degli argomenti passati in input 
 * (vengono controllati sia il numero degli argomenti che il loro nome che devono coincidere 
 * con quanto presente nella classe API apposita)
 * 
 * [EN]
 * This class allows you to carry out checks on the request come to the server, 
 * checks for the existence of the API, the requested method and of the arguments passed in input 
 * (are monitored both the number of arguments that their name which must match 
 * with what is present in the class API special)
 * PHP version 5
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author 	Ettore Moretti <ettoremoretti27{at}gmail{dot}com>
 * @copyright	Ettore Moretti 2015
 * @version	1.0
 * @since  	2015
 */
class Router {
	// Passed api name
	private $api_name;
	// Passed method name
	private $method_name;
	// Passed args
	private $args;
	// Api class instance
	private $Api;
	// Instance of class ReflectionMethod used for retrieve some method info
	private $myReflection;
	
	// Costruttore della classe Router, richiede una stringa request e un array $args (o null)
	// Constructor of Router class, requires a request String and $args Array (or null)
	public function __construct($request, $args) {
		// Explode la request per recuperare il nome api e il metodo chiamato
		// Explode the request for retrieve the api name and the method called
		$tmp = explode ( "/", $request );
		
		// Recupero la API invocata
		// Retrieve the API invoked
		$this->api_name = ucfirst ( $tmp [0] );
		
		/**
		 * Describe GOTO 'O.o
		 * I know, that sucks!!!
		 */
		if ($this->api_name== "Describe" && isset ( $tmp [1] )) {
			include_once (API_PATH . $this->api_name . ".php");
			$this->Api = new $this->api_name ( ucfirst($tmp [1]) );
			exit ();
		}
		 
		// Controllo che la API invocata sia presente nel server
		// Checking that the API is invoked on the server
		$this->apiCheck ();
		
		// Controllo che sia presente un metodo da invocare
		// Check that there is a method to invoke
		if (isset ( $tmp [1] ) && trim ( $tmp [1] ) != "")
			$this->method_name = strtolower ( $tmp [1] );
		else
			print_r ( json_encode ( [ 
					'Response' => '0',
					'Error' => 'Malformed API request, You havn\'t invoked any action for API: ' . $this->api_name 
			] ) );
			
			// Recupero i parametri per la chiamata
			// Retrieve the parameters for the call
		$this->args = $args;
		
		// Controllo che il metodo passato sia presente ed invocabile
		// Check that the method is present and past callable
		$this->checkMethod ();
		
		// Invoco il metodo della API richiesta con i parametri necessari
		// Invoke the method of the API request with the necessary parameters
		call_user_func_array ( array (
				$this->Api,
				$this->method_name 
		), $this->args );
	}
	
	// Vari Controlli per la chiamata alla API richiesta
	// Various Control for the call to the API request
	private function apiCheck() {
		// Controllo che il file esista
		// Check that the file exists
		$filename = API_PATH . $this->api_name . ".php";
		
		if (file_exists ( $filename )) {
			include_once $filename;
			// Controllo che la classe esista
			// Check that the class exists
			if (! class_exists ( $this->api_name, false )) {
				print_r ( json_encode ( [ 
						'Response' => '0',
						'Error' => 'Malformed or Deprecated API request, "' . $this->api_name . '" isn\'t a valid API.' 
				] ) );
				exit ();
			} else
				$this->Api = new $this->api_name ();
		} else {
			print_r ( json_encode ( [ 
					'Response' => '0',
					'Error' => 'Malformed API request, "' . $this->api_name . '" isn\'t a valid API.' 
			] ) );
			exit ();
		}
	}
	
	// Vari Controlli sul metodo dell'api chiamata
	// Various controls on the method of API call
	private function checkMethod() {
		// Controllo che il metodo esista
		// Checking that the method exists
		if (! method_exists ( $this->Api, $this->method_name )) {
			print_r ( json_encode ( [ 
					'Response' => '0',
					'Error' => 'Malformed API request, "' . $this->api_name . '" havn\'t an "' . $this->method_name . '" action.' 
			] ) );
			exit ();
		} else {
			// Verifico che il numero delle chiavi passate in args siano quanto atteso dall'API
			// I verify that the number of keys passed in args are as expected by the API
			$this->myReflection = new ReflectionMethod ( $this->api_name, $this->method_name );
			if (count ( $this->args ) != $this->myReflection->getNumberOfRequiredParameters ()) {
				print_r ( json_encode ( [ 
						'Response' => '0',
						'Error' => 'Malformed API request, number of parameters (' . count ( $this->args ) . ') passed to the method "' . $this->method_name . '" of "' . $this->api_name . '" API, are incorrect. Please use "describe" API for more information about the correct API call.' 
				] ) );
				exit ();
			} else {
				$methodParams = $this->myReflection->getParameters ();
				// Verifico i nomi dei parametri passati siano quanto atteso dall'API
				// I verify the names of the parameters are passed as expected by the API
				foreach ( $methodParams as $key => $value ) {
					if (! array_key_exists ( $value->name, $this->args )) {
						print_r ( json_encode ( [ 
								'Response' => '0',
								'Error' => 'Malformed API request, parameter "' . $value->name . '" not passed to the method "' . $this->method_name . '" of "' . $this->api_name . '" API. Please use "describe" API for more information about the correct API call.' 
						] ) );
						exit ();
					}
				}
			}
		}
	}
}