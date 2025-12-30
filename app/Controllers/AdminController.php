<?php

namespace App\Controllers;

use App\Classes\Hash;
use App\Controllers\Controller;
use Psr\Http\Message\{
    ServerRequestInterface as Request,
    ResponseInterface as Response
};

use Respect\Validation\Validator as v;

class AdminController extends Controller
{
    public function dashboard(Request $request, Response $response)
    {

       $route = $request->getAttribute('route');
       $route_name = $route->getName();
       $settings = $this->c->db->select('settings', '*', ["id" => 1]);
       foreach ($settings as $web) {}

       $start = $this->c->db->has('start', []);
       $start_array = $this->c->db->select('start', '*', ["LIMIT" => 1, "ORDER" =>["id" => "DESC"]]);

       $count_voters = $this->c->db->count('user', []);
       $count_nominees = $this->c->db->count('nominees', []);
       $count_categories = $this->c->db->count('categories', []);

        return $this->c->view->render($response, 'backend/dashboard.twig', [
            'route_name' => $route_name,
            'web' => $web,
            'start' => $start,
            'start_array' => $start_array,
            'count_voters' => $count_voters,
            'count_nominees' => $count_nominees,
            'count_categories' => $count_categories,
        ]);
    }

    public function start(Request $request, Response $response)
    {

      $validation = $this->c->validator->validate($request, [
           'title' => v::notEmpty(),
           'date_to_start' => v::notEmpty(),
           'date_to_end' => v::notEmpty(),
           'description' => v::notEmpty(),
      ]); 

      if ($validation->failed()) {
        return $response->withRedirect($this->c->router->pathFor('admin.dashboard'));
      }

      //Insert
       $insert = $this->c->db->insert('start', [
         'title' => $request->getParam('title'),
         'date_to_start' => $request->getParam('date_to_start'),
         'date_to_end' => $request->getParam('date_to_end'),
         'description' => $request->getParam('description'),
        ]); 

      if ($insert->rowCount() == 1) {
        
        $this->c->flash->addMessage('success', 'You have successfully started your voting system!');
        return $response->withRedirect($this->c->router->pathFor('admin.dashboard'));
      }else{

        $this->c->flash->addMessage('warning', 'Something went wrong with posting of data!');
        return $response->withRedirect($this->c->router->pathFor('admin.dashboard'));
      }


    }

    public function start_update(Request $request, Response $response)
    {

      $validation = $this->c->validator->validate($request, [
           'title' => v::notEmpty(),
           'date_to_start' => v::notEmpty(),
           'date_to_end' => v::notEmpty(),
           'description' => v::notEmpty(),
      ]); 

      if ($validation->failed()) {
        return $response->withRedirect($this->c->router->pathFor('admin.dashboard'));
      }

      //Update
      $update = $this->c->db->update('start',[
         'title' => $request->getParam('title'),
         'date_to_start' => $request->getParam('date_to_start'),
         'date_to_end' => $request->getParam('date_to_end'),
         'description' => $request->getParam('description'),
      ],[
          'id' => $request->getParam('start_id')
        ]);

      if ($update->rowCount() == 1) {
        
        $this->c->flash->addMessage('success', 'You have successfully updated your voting system!');
        return $response->withRedirect($this->c->router->pathFor('admin.dashboard'));
      }else{

        $this->c->flash->addMessage('warning', 'No Changes were made!');
        return $response->withRedirect($this->c->router->pathFor('admin.dashboard'));
      }


    }   

    public function getdelete(Request $request, Response $response, $args)
    {

      // get id value
      $id = $args['id'];

     $nominees = $this->c->db->select('nominees', '*', []);
     foreach ($nominees as $nominee) {

        // Unlink Image
        $imagelocation = 'uploads/nominees/' . $nominee["imagelocation"];

        if (file_exists($imagelocation)) {
            unlink($imagelocation);
        }

     }

       
       // delete the entry
      $delete = $this->c->db->delete('start', ['id' => $id]);
      $delete_nominees = $this->c->db->delete('nominees', []);
      $delete_categories = $this->c->db->delete('categories', []);

      if ($delete->rowCount() == 1) {
        
        $this->c->flash->addMessage('success', 'Voting deleted successfully!');
        return $response->withRedirect($this->c->router->pathFor('admin.dashboard'));
      }else{

        $this->c->flash->addMessage('error', 'Unable to delete!');
        return $response->withRedirect($this->c->router->pathFor('admin.dashboard'));
      }


    }  

    public function finish(Request $request, Response $response, $args)
    {

      // get id value
      $id = $args['id'];
       
      //Update
      $update = $this->c->db->update('start',[
         'end' => 1,
         'date_ended' => date('Y-m-d'),
      ],[
          'id' => $id
        ]);

      if ($update->rowCount() == 1) {
        
        $this->c->flash->addMessage('success', 'The Voting has now Ended!');
        return $response->withRedirect($this->c->router->pathFor('admin.dashboard'));
      }else{

        $this->c->flash->addMessage('warning', 'No Changes were made!');
        return $response->withRedirect($this->c->router->pathFor('admin.dashboard'));
      }


    } 

    public function resume(Request $request, Response $response, $args)
    {

      // get id value
      $id = $args['id'];
       
      //Update
      $update = $this->c->db->update('start',[
         'end' => 0,
      ],[
          'id' => $id
        ]);

      if ($update->rowCount() == 1) {
        
        $this->c->flash->addMessage('success', 'The Voting has been resumed!');
        return $response->withRedirect($this->c->router->pathFor('admin.dashboard'));
      }else{

        $this->c->flash->addMessage('warning', 'No Changes were made!');
        return $response->withRedirect($this->c->router->pathFor('admin.dashboard'));
      }


    } 

    public function login(Request $request, Response $response)
    {
       $settings = $this->c->db->select('settings', '*', ["id" => 1]);
       foreach ($settings as $web) {}

        return $this->c->view->render($response, 'backend/login.twig', [
            'web' => $web,
        ]);
    }

    public function postLogin(Request $request, Response $response)
    {

      $validation = $this->c->validator->validate($request, [
           'email' => v::noWhitespace()->notEmpty()->email(),
           'password' => v::noWhitespace()->notEmpty(),
      ]); 

      if ($validation->failed()) {
        return $response->withRedirect($this->c->router->pathFor('admin.login'));
      }


      $auth = $this->c->admin->login(
         $request->getParam('email'),
         $request->getParam('password')
      );
      
      if (!$auth) {
        
        $this->c->flash->addMessage('error', 'Sorry we could not sign you in with those details!');
        return $response->withRedirect($this->c->router->pathFor('admin.login'));
      }


       return $response->withRedirect($this->c->router->pathFor('admin.dashboard'));
    }

	  public function logout($request, $response)
	   {

	     $this->c->admin->logout();

	     return $response->withRedirect($this->c->router->pathFor('admin.login'));
	   } 

    public function profile(Request $request, Response $response)
    {


       $route = $request->getAttribute('route');
       $route_name = $route->getName();
       $settings = $this->c->db->select('settings', '*', ["id" => 1]);
       foreach ($settings as $web) {}

       $start = $this->c->db->has('start', []);

        return $this->c->view->render($response, 'backend/profile.twig', [
            'route_name' => $route_name,
            'web' => $web,
            'start' => $start,
        ]);
    }

    public function editname(Request $request, Response $response)
    {

      $validation = $this->c->validator->validate($request, [
           'name' => v::notEmpty(),
           'email' => v::noWhitespace()->notEmpty()->email(),
      ]); 

      if ($validation->failed()) {
        return $response->withRedirect($this->c->router->pathFor('admin.profile'));
      }

      //Update
      $update = $this->c->db->update('admin',[
         'name' => $request->getParam('name'),
         'email' => $request->getParam('email'),
      ],[
          'adminid' => $this->c->admin->data()["adminid"]
        ]);

      if ($update->rowCount() == 1) {
        
        $this->c->flash->addMessage('success', 'The details have been successfully updated!');
        return $response->withRedirect($this->c->router->pathFor('admin.profile'));
      }else{

        $this->c->flash->addMessage('warning', 'You have made no changes!');
        return $response->withRedirect($this->c->router->pathFor('admin.profile'));
      }


    }

    public function password(Request $request, Response $response)
    {

      $validation = $this->c->validator->validate($request, [
           'current_password' => v::notEmpty(),
           'new_password' => v::notEmpty(),
           'confirm_password' => v::notEmpty()->confirmPassword($request->getParam('new_password'), $request->getParam('confirm_password')),
      ]); 

      if ($validation->failed()) {
        return $response->withRedirect($this->c->router->pathFor('admin.profile'));
      }
    
      if (password_verify($request->getParam('current_password'), $this->c->admin->data()["password"])) {

        //Update
        $update = $this->c->db->update('admin',[
          'password' => password_hash($request->getParam('new_password'), PASSWORD_DEFAULT),
        ],[
            'adminid' => $this->c->admin->data()["adminid"]
          ]);

        if ($update->rowCount() == 1) {
          
          $this->c->flash->addMessage('success', 'You have changed your password successfully!');
          return $response->withRedirect($this->c->router->pathFor('admin.profile'));
        }else{

          $this->c->flash->addMessage('warning', 'You have made no changes!');
          return $response->withRedirect($this->c->router->pathFor('admin.profile'));
        }

      } else {

        $this->c->flash->addMessage('error', 'Your current password does not match!');
        return $response->withRedirect($this->c->router->pathFor('admin.profile'));
       
      }
    }  


    public function image(Request $request, Response $response)
    {

        // Check image if uploaded
        $files = $request->getUploadedFiles();
        if (empty($files['photoimg'])) {
            $this->c->flash->addMessage('error', 'Image is empty.');
            return $response->withRedirect($this->c->router->pathFor('admin.profile'));
        }

      // Unlink Image
      $imagelocation = 'uploads/profile/' . $this->c->admin->data()["imagelocation"];

      if (file_exists($imagelocation)) {
          unlink($imagelocation);
      } 
      else {
            $this->c->flash->addMessage('error', 'Image file does not exist.');
            return $response->withRedirect($this->c->router->pathFor('admin.profile'));
      }

        // Upload Image 
        $myFile = $files['photoimg'];
        if ($myFile->getError() === UPLOAD_ERR_OK) {

          $extension = pathinfo($myFile->getClientFilename(), PATHINFO_EXTENSION);
          $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
          $uploadFileName = sprintf('%s.%0.8s', $basename, $extension);

            $myFile->moveTo('uploads/profile/' . $uploadFileName);
        }

        $update = $this->c->db->update("admin",
            [
              "imagelocation" => $uploadFileName
            ],['adminid' => $this->c->admin->data()["adminid"]]);

        $this->c->flash->addMessage('success', 'Successfully updated your image.');
        return $response->withRedirect($this->c->router->pathFor('admin.profile'));

    } 


    public function settings(Request $request, Response $response)
    {

       $route = $request->getAttribute('route');
       $route_name = $route->getName();
       $settings = $this->c->db->select('settings', '*', ["id" => 1]);
       foreach ($settings as $web) {}

       $start = $this->c->db->has('start', []);

        return $this->c->view->render($response, 'backend/settings.twig', [
            'route_name' => $route_name,
            'web' => $web,
            'start' => $start,
        ]);
    } 



    public function details(Request $request, Response $response)
    {

      $validation = $this->c->validator->validate($request, [
           'sitename' => v::notEmpty(),
           'title' => v::notEmpty(),
      ]); 

      if ($validation->failed()) {
        return $response->withRedirect($this->c->router->pathFor('admin.settings'));
      }

      //Update
      $update = $this->c->db->update('settings',[
         'sitename' => $request->getParam('sitename'),
         'title' => $request->getParam('title'),
      ],[
          'id' => 1
        ]);

      if ($update->rowCount() == 1) {
        
        $this->c->flash->addMessage('success', 'The details have been successfully updated!');
        return $response->withRedirect($this->c->router->pathFor('admin.settings'));
      }else{

        $this->c->flash->addMessage('warning', 'You have made no changes!');
        return $response->withRedirect($this->c->router->pathFor('admin.settings'));
      }


    } 

    public function siteimage(Request $request, Response $response)
    {

        // Check image if uploaded
        $files = $request->getUploadedFiles();
        if (empty($files['photoimg'])) {
            $this->c->flash->addMessage('error', 'Image is empty.');
            return $response->withRedirect($this->c->router->pathFor('admin.settings'));
        }

      // Unlink Image
      $imagelocation = 'uploads/settings/' . $request->getParam('imagelocation');

      if (file_exists($imagelocation)) {
          unlink($imagelocation);
      } 
      else {
            $this->c->flash->addMessage('error', 'Image file does not exist.');
            return $response->withRedirect($this->c->router->pathFor('admin.settings'));
      }

        // Upload Image 
        
        $myFile = $files['photoimg'];
        if ($myFile->getError() === UPLOAD_ERR_OK) {

          $extension = pathinfo($myFile->getClientFilename(), PATHINFO_EXTENSION);
          $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
          $uploadFileName = sprintf('%s.%0.8s', $basename, $extension);

            $myFile->moveTo('uploads/settings/' . $uploadFileName);
        }

        $update = $this->c->db->update("settings",
            [
              "imagelocation" => $uploadFileName
            ],['id' => 1]);

        $this->c->flash->addMessage('success', 'Successfully updated your image.');
        return $response->withRedirect($this->c->router->pathFor('admin.settings'));

    }                 

 

}
