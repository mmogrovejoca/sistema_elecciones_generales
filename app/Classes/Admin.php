<?php

namespace App\Classes;

// Using Medoo namespace
use App\Classes\Config;
use App\Classes\Hash;
use App\Classes\Session;
use Medoo\Medoo;

class Admin {
	protected $_db;
	protected $_sessionName;
	private $_data;
	
	public function __construct($admin = null) 
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
		$_sessionName = Config::get('session/session_admin');
		return $_sessionName;
	}

	public function find($admin = null)
	{
	  if ($admin) {
		 $field = (is_numeric($admin)) ? 'adminid' : 'email';
		 $datas = Admin::database()->has("admin", [$field => $admin]);
         		 
		 if($datas === true){

			 return true; 
		 }elseif ($datas === false) {
		 	return false;
		 }
	  }	
	 return false;	
	}

	public function get($admin = null)
	{
	  if ($admin) {
		 $field = (is_numeric($admin)) ? 'adminid' : 'email';
		 $datas = Admin::database()->has("admin", [$field => $admin]);
         		 
		 if($datas === true){
             
             $q1 = Admin::database()->select("admin", "*", [$field => $admin]);
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
	    
			if (Session::exists(Admin::sessionName())) {
			  $admin = Session::get(Admin::sessionName());
			  if (Admin::find($admin) == true) {
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
	  $admin = Session::get(Admin::sessionName());
	  $_data = Admin::get($admin);
	  return $_data;
	}	

	public function login($email, $password)
	{
	 
		 
	  $admin = Admin::find($email);
	  if ($admin == true) {			  
		  
		 if (password_verify($password, Admin::get($email)["password"])) {

		 	$_SESSION[Admin::sessionName()] = Admin::get($email)["adminid"];


			return true;	
				
			  }		  
		 } 
	  return false;
	}

	public function logout()
	{
	  Session::delete(Admin::sessionName());
	}	
		
	
	
}




?>