<?php

namespace App\Middleware;


/**
 * 
 */
class AuthMiddleware extends Middleware
{
	
	function __invoke($request, $response, $next)
	{

		if (!$this->c->user->isLoggedIn()) {

	         $this->c->flash->addMessage('error', 'Please sign in before going to that link!');

	        return $response->withRedirect($this->c->router->pathFor('login')); 
		}

		$response = $next($request, $response);

		return $response;
	}
}

?>