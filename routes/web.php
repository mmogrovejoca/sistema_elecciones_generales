<?php

use App\Controllers\AdminController;
use App\Controllers\CategoryController;
use App\Controllers\HomeController;
use App\Controllers\NomineeController;
use App\Controllers\VoterController;
use App\Middleware\AdminGuestMiddleware;
use App\Middleware\AdminMiddleware;
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

$app->get('/', HomeController::class . ':index')->setName('home');
$app->get('/category/{slug}', HomeController::class . ':nominees')->setName('nominees');
$app->get('/results', HomeController::class . ':results')->setName('voting-results');
$app->get('/categories', HomeController::class . ':categories')->setName('categories');
$app->get('/graphs/nominees/{id}', HomeController::class . ':graphs')->setName('graphs');


$app->get('/logout', HomeController::class . ':logout')->setName('logout');


$app->group('', function () {

	$this->get('/login', HomeController::class . ':login')->setName('login');
	$this->post('/login', HomeController::class . ':postlogin')->setName('postlogin');

	$this->get('/register', HomeController::class . ':register')->setName('register');
	$this->post('/register', HomeController::class . ':postregister')->setName('postregister');

})->add(new GuestMiddleware($container));

$app->group('', function () {

	$this->get('/editprofile', HomeController::class . ':editprofile')->setName('editprofile');
	$this->post('/editname', HomeController::class . ':editname')->setName('editname');
	$this->post('/password', HomeController::class . ':password')->setName('password');

	$this->post('/vote', HomeController::class . ':vote')->setName('vote');

})->add(new AuthMiddleware($container));



$app->group('/admin', function () {

	$this->get('/login', AdminController::class . ':login')->setName('admin.login');
	$this->post('/login', AdminController::class . ':postLogin')->setName('admin.postLogin');

})->add(new AdminGuestMiddleware($container));


$app->group('/admin', function () {

	$this->get('/dashboard', AdminController::class . ':dashboard')->setName('admin.dashboard');
	$this->post('/dashboard/start', AdminController::class . ':start')->setName('admin.start');
	$this->post('/dashboard/update', AdminController::class . ':start_update')->setName('admin.start_update');
	$this->get('/dashboard/delete/{id}', AdminController::class . ':getdelete')->setName('admin.start_delete');
	$this->get('/dashboard/finish/{id}', AdminController::class . ':finish')->setName('admin.finish');
	$this->get('/dashboard/resume/{id}', AdminController::class . ':resume')->setName('admin.resume');

	$this->get('/logout', AdminController::class . ':logout')->setName('admin.logout');
	$this->get('/profile/show', AdminController::class . ':profile')->setName('admin.profile');
	$this->post('/profile/editname', AdminController::class . ':editname')->setName('admin.editname');
	$this->post('/profile/password', AdminController::class . ':password')->setName('admin.password');
	$this->post('/profile/image', AdminController::class . ':image')->setName('admin.image');

	// Settings
	$this->get('/settings', AdminController::class . ':settings')->setName('admin.settings');
	$this->post('/settings/details', AdminController::class . ':details')->setName('admin.site_details');
	$this->post('/settings/image', AdminController::class . ':siteimage')->setName('admin.site_image');

	// Add Categories
	$this->get('/categories', CategoryController::class . ':index')->setName('admin.categories');
	$this->get('/categories/add', CategoryController::class . ':add')->setName('admin.category_add');
	$this->post('/categories/add', CategoryController::class . ':postadd')->setName('admin.category_post');
	$this->get('/categories/edit/{id}', CategoryController::class . ':edit')->setName('admin.category_edit');
	$this->post('/categories/edit/post', CategoryController::class . ':postedit')->setName('admin.category_postedit');
	$this->get('/categories/delete/{id}', CategoryController::class . ':getdelete')->setName('admin.category_delete');
	$this->get('/categories/nominees/{id}', CategoryController::class . ':nominees')->setName('admin.category_nominees');

	// Nominees
	$this->get('/nominee/add', NomineeController::class . ':add')->setName('admin.nominee_add');
	$this->post('/nominee/add', NomineeController::class . ':postadd')->setName('admin.nominee_post');

	$this->get('/nominees', NomineeController::class . ':index')->setName('admin.nominees');
	$this->get('/nominees/edit/{id}', NomineeController::class . ':edit')->setName('admin.nominee_edit');
	$this->post('/nominees/edit/post', NomineeController::class . ':postedit')->setName('admin.nominee_postedit');
	$this->post('/nominees/edit/image', NomineeController::class . ':image')->setName('admin.nominee_image');
	$this->get('/nominees/delete/{id}', NomineeController::class . ':delete')->setName('admin.nominee_delete');
	$this->get('/results', NomineeController::class . ':results')->setName('admin.results');
	$this->get('/graphs', NomineeController::class . ':graphs')->setName('admin.graphs');

    // Voters
	$this->get('/registered', VoterController::class . ':index')->setName('admin.registered');
	$this->get('/approve/{id}', VoterController::class . ':approve')->setName('admin.approve');
	$this->get('/decline/{id}', VoterController::class . ':decline')->setName('admin.decline');
	$this->get('/voters', VoterController::class . ':voters')->setName('admin.voters');
	$this->get('/delete/{id}', VoterController::class . ':delete')->setName('admin.delete');
	$this->get('/voter_results', VoterController::class . ':voter')->setName('admin.voter_results');

})->add(new AdminMiddleware($container));

