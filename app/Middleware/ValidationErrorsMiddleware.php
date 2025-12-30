<?php


namespace App\Middleware;
error_reporting(0);


/**
 * 
 */
class ValidationErrorsMiddleware extends Middleware
{
	
	public function __invoke($request, $response, $next)
	{
		$this->c->view->getEnvironment()->addGlobal('errors', $_SESSION['errors']);

		unset($_SESSION['errors']);

		$response = $next($request, $response);

		return $response;
	}
}

?>