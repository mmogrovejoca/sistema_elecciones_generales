<?php

namespace App\Middleware;


/**
 * 
 */
class AdminMiddleware extends Middleware
{
	
	function __invoke($request, $response, $next)
	{

		if (!$this->c->admin->isLoggedIn()) {

	         $this->c->flash->addMessage('error', 'Please sign in in order to go to that link!');

	        return $response->withRedirect($this->c->router->pathFor('admin.login')); 
		}

		$response = $next($request, $response);

		return $response;
	}
}

?>