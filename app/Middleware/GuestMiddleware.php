<?php

namespace App\Middleware;


/**
 * 
 */
class GuestMiddleware extends Middleware
{
	
	function __invoke($request, $response, $next)
	{

		if ($this->c->user->isLoggedIn()) {

	        return $response->withRedirect($this->c->router->pathFor('home')); 
		}

		$response = $next($request, $response);

		return $response;
	}
}

?>