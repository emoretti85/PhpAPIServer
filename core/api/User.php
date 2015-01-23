<?php
require_once 'API.php';
/**
 * Php Api Server
 *
 * This class is an API class for example, when you create your API remember to always create the function __construct and getDesc
 *
 * PHP version 5
 *
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @author 	Ettore Moretti <ettoremoretti27{at}gmail{dot}com>
 * @copyright	Ettore Moretti 2015
 * @version	1.0
 * @since  	2015
 */
class User implements API{
	public function __construct(){}
	
	public function login($username,$password){
		//Example use db//
		/*
		 * $bind=array();
		 * $DB=new DB(DB_DSN,DB_USER,DB_PASSWORD);
		 * $DB->select("userTable",null,$bind);
		 */
		echo "Response from login API: username=".$username." e password=".$password;
	}
	
	public function register(){
		echo "Response from login API!";
	}
	
	public static function getDesc(){
		return "User class, used for login and register a user";
	}
}