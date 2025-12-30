<?php

error_reporting(0);

use Respect\Validation\Validator as v;

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

// Using Medoo namespace
use App\Classes\Config;
use App\Classes\Session;
use App\Classes\User;
use Medoo\Medoo;

try {
    (new Dotenv\Dotenv(__DIR__ . '/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

$app = new Slim\App([
    'settings' => [
        'displayErrorDetails' => getenv('APP_DEBUG') === 'true',

        'db' => [
            'driver' => 'mysql',
            'host' => getenv('DB_HOST'),
            'database' => getenv('DB_DATABASE'),
            'username' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ],

        'app' => [
            'name' => getenv('APP_NAME')
        ],

        'views' => [
            'cache' => getenv('VIEW_CACHE_DISABLED') === 'true' ? false : __DIR__ . '/../storage/views'
        ]
    ],
]);

$container = $app->getContainer();


$container['db'] = function(){

    $database = new Medoo([
        'database_type' => getenv('DB_TYPE'),
        'server' => getenv('DB_HOST'),
        'database_name' => getenv('DB_DATABASE'),
        'username' => getenv('DB_USERNAME'),
        'password' => getenv('DB_PASSWORD')
    ]);

    return $database;

};



$container['graphs'] = function(){

    $graphs = new CMEN\GoogleChartsBundle\CMENGoogleChartsBundle();

    return $graphs;

};

$GLOBALS['config'] = array(
  'remember' => array(
    'cookie_name' => 'hash',
    'cookie_expiry' => 604800
  ),
  'language' => array(
    'cookie_name' => 'lang',
    'session_name' => 'lang',
    'cookie_expiry' => 604800
  ),
  'session' => array(
    'session_admin' => 'bestAdmin',
    'session_name' => 'bestUser',
    'token_name' => 'token'
  )
);



$container['admin'] = function($container){
    return new \App\Classes\Admin;
};

$container['user'] = function($container){
    return new \App\Classes\User;
};

$container['flash'] = function($container){
    return new \Slim\Flash\Messages;
};

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [
        'cache' => $container->settings['views']['cache']
    ]);

    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    $view->getEnvironment()->addGlobal('user', [
       'isLoggedIn' => $container->user->isLoggedIn(),
       'userid' => $container->user->data()["userid"],
       'usertype' => $container->user->data()["user_type"],
       'name' => $container->user->data()["name"],
       'imagelocation' => $container->user->data()["imagelocation"],
       'email' => $container->user->data()["email"],
       'verified' => $container->user->data()["verified"],
    ]);   

    $view->getEnvironment()->addGlobal('admin', [
       'isLoggedIn' => $container->admin->isLoggedIn(),
       'adminid' => $container->admin->data()["adminid"],
       'name' => $container->admin->data()["name"],
       'imagelocation' => $container->admin->data()["imagelocation"],
       'email' => $container->admin->data()["email"],
    ]);   

    $view->getEnvironment()->addGlobal('flash', $container->flash);

    return $view;
};

$container['mailer'] = function($container) {
    $twig = $container['view'];
    $mailer = new \Anddye\Mailer\Mailer($twig, [
        'host'      => getenv('SMTP_HOST'),  // SMTP Host
        'port'      => getenv('SMTP_PORT'),  // SMTP Port
        'username'  => getenv('SMTP_USERNAME'),  // SMTP Username
        'password'  => getenv('SMTP_PASSWORD'),  // SMTP Password
        'protocol'  => getenv('SMTP_PROTOCOL')   // SSL or TLS
    ]);
        
    // Set the details of the default sender
    $mailer->setDefaultFrom(getenv('EMAIL_FROM'), getenv('EMAIL_NAME'));
    
    return $mailer;
};

$container['validator'] = function($container){
    return new \App\Validation\Validator;
};

$container['csrf'] = function($container){
    return new \Slim\Csrf\Guard;
};


$app->add(new \App\Middleware\ValidationErrorsMiddleware($container));
$app->add(new \App\Middleware\OldInputMiddleware($container));
$app->add(new \App\Middleware\CsrfViewMiddleware($container));

$app->add($container->csrf);

v::with('App\\Validation\\Rules\\');

require_once __DIR__ . '/../routes/web.php';
