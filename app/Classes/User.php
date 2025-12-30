<?php

namespace App\Classes;

// Using Medoo namespace
use App\Classes\Config;
use App\Classes\Hash;
use App\Classes\Session;
use Medoo\Medoo;

class User {
	protected $_db;
	protected $_sessionName;
	private $_data;
	
	public function __construct($user = null) 
	{
	 
	 		

	}

	public function database(){
		 $_db = new Medoo([
	        'database_type' => getenv('DB_TYPE'),
	        'server' => getenv('DB_HOST'),
	        'database_name' => getenv('DB_DATABASE'),
	        'username' => getenv('DB_USERNAME'),
	        'password' => getenv('DB_PASSWORD')
		]);
		return $_db;
	}

	public function sessionName(){
		$_sessionName = Config::get('session/session_name');
		return $_sessionName;
	}

	public function find($user = null)
	{
	  if ($user) {
		 $field = (is_numeric($user)) ? 'userid' : 'email';
		 $datas = User::database()->has("user", [$field => $user]);
         		 
		 if($datas === true){

			 return true; 
		 }elseif ($datas === false) {
		 	return false;
		 }
	  }	
	 return false;	
	}

	public function get($user = null)
	{
	  if ($user) {
		 $field = (is_numeric($user)) ? 'userid' : 'email';
		 $datas = User::database()->has("user", [$field => $user]);
         		 
		 if($datas === true){
             
             $q1 = User::database()->select("user", "*", [$field => $user]);
             foreach($q1 as $r1){}

             $_data = $r1; 	
			 
			 return $_data;  
		 }elseif ($datas === false) {

		 	return false;
		 }
	  }	
	 return false;	
	}	

    public function isLoggedIn()
	{
	    
			if (Session::exists(User::sessionName())) {
			  $user = Session::get(User::sessionName());
			  if (User::find($user) == true) {
				 return true;
			  }else {
				  return false;
			  }	
			  return false;	
			}else{
			  return false;	
			}

		 
	}

	public function data()
	{  
	  $user = Session::get(User::sessionName());
	  $_data = User::get($user);
	  return $_data;
	}	

	public function login($email, $password)
	{
	 
		 
	  $user = User::find($email);
	  if ($user == true) {			  
		  
		 if (password_verify($password, User::get($email)["password"])) {

		 	$_SESSION[User::sessionName()] = User::get($email)["userid"];


			return true;	
				
			  }		  
		 } 
	  return false;
	}

	public function logout()
	{
	  Session::delete(User::sessionName());
	}	
		
	
	
}




?>