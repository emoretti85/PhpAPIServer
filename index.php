<?php
require_once ("config.php");
require_once (CORE . "router.php");
require_once (DB   . "database.php");

// Recupero il get in una var separata per poter fare dei controlli di sicurezza (DA IMPLEMENTARE)
// Retrieve the get in a separate var to do security checks (BE IMPLEMENTED)
$req = $_GET;

// Controllo che il parametro api sia presente
// Check that the parameter APIs is present
if (isset ( $req ['api'] )) {
	$api_request = $req ['api'];
	unset ( $req ['api'] );
} else {
	print_r ( json_encode ( [ 
			'Response' => '0',
			'Error' => 'Request not available.' 
			 ] ) );
	exit ();
}
// Chiamata alla router class per risolvere la richiesta
// Call the router class to resolve the request
$my_router = new Router ( $api_request, $req );