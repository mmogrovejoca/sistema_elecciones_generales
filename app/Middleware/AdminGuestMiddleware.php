<?php

namespace App\Middleware;


/**
 * 
 */
class AdminGuestMiddleware extends Middleware
{
	
	function __invoke($request, $response, $next)
	{

		if ($this->c->admin->isLoggedIn()) {

	        return $response->withRedirect($this->c->router->pathFor('admin.dashboard')); 
		}

		$response = $next($request, $response);

		return $response;
	}
}

?>