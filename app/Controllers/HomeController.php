<?php

namespace App\Controllers;

use App\Classes\Hash;
use App\Classes\User;
use App\Controllers\Controller;
use Psr\Http\Message\{
    ServerRequestInterface as Request,
    ResponseInterface as Response
};
use Respect\Validation\Validator as v;

class HomeController extends Controller
{

    public function index(Request $request, Response $response)
    {

       $route = $request->getAttribute('route');
       $route_name = $route->getName();
       $settings = $this->c->db->select('settings', '*', ["id" => 1]);
       foreach ($settings as $web) {}

       $start_array = $this->c->db->select('start', '*', ["LIMIT" => 1, "ORDER" =>["id" => "DESC"]]);
       foreach ($start_array as $start) {}

       $date_to_start = strftime("%b %d, %Y", strtotime($start["date_to_start"])); 
       $date_to_end = strftime("%b %d, %Y", strtotime($start["date_to_end"])); 

       $now = date('Y-m-d H:i:s');
       $date_to_now = strftime("%b %d, %Y", strtotime($now)); 

       $categories = $this->c->db->select('categories', '*', []);


        return $this->c->view->render($response, 'frontend/index.twig', [
            'route_name' => $route_name,
            'web' => $web,
            'start' => $start,
            'categories' => $categories,
            'date_to_start' => $date_to_start,
            'date_to_end' => $date_to_end,
            'date_to_now' => $date_to_now,
        ]);
    }

    public function nominees(Request $request, Response $response, $args)
    {

       $slug = $args['slug'];

       $has = $this->c->db->has('categories', ["slug" => $slug]);
       if ($has === false) {
        return $response->withRedirect($this->c->router->pathFor('home'));
       }

       $ct = $this->c->db->select('categories', '*', ["slug" => $slug]);
       foreach ($ct as $cate) {}

       $nominees = $this->c->db->select('nominees', '*', ["category_id" => $cate["id"]]);
       foreach ($nominees as $row) {

         $g = $this->c->db->count('votes', ["nominee" => $row["id"]]);
          
          $count[] = [
               "nomineeid" => $row["id"],
               "g" => $g,
          ];

       }   

       $value = max(array_column($count, 'g')); 

       $has_voted = $this->c->db->has('votes', ["AND" => ["userid" => $this->c->user->data()["userid"], "category_id" => $cate["id"]]]);
       if ($has_voted === true) {

         $q1 = $this->c->db->select('votes', '*', ["AND" => ["userid" => $this->c->user->data()["userid"], "category_id" => $cate["id"]]]);
         foreach ($q1 as $r1) {}        
       }



       $settings = $this->c->db->select('settings', '*', ["id" => 1]);
       foreach ($settings as $web) {}

       $start_array = $this->c->db->select('start', '*', ["LIMIT" => 1, "ORDER" =>["id" => "DESC"]]);
       foreach ($start_array as $start) {}

       $date_to_start = strftime("%b %d, %Y", strtotime($start["date_to_start"])); 
       $date_to_end = strftime("%b %d, %Y", strtotime($start["date_to_end"])); 

       $now = date('Y-m-d H:i:s');
       $date_to_now = strftime("%b %d, %Y", strtotime($now)); 

       $categories = $this->c->db->select('categories', '*', []);


        return $this->c->view->render($response, 'frontend/nominees.twig', [
            'web' => $web,
            'start' => $start,
            'categories' => $categories,
            'cate' => $cate,
            'nominees' => $nominees,
            'slug' => $slug,
            'date_to_start' => $date_to_start,
            'date_to_end' => $date_to_end,
            'date_to_now' => $date_to_now,
            'has_voted' => $has_voted,
            'r1' => $r1,
            'count' => $count,
            'value' => $value,
        ]);
    }
    


    public function results(Request $request, Response $response)
    {

       $route = $request->getAttribute('route');
       $route_name = $route->getName();
       $settings = $this->c->db->select('settings', '*', ["id" => 1]);
       foreach ($settings as $web) {}
       $start = $this->c->db->has('start', []);

       $categories = $this->c->db->select('categories', '*', []);

       $start_array = $this->c->db->select('start', '*', ["LIMIT" => 1, "ORDER" =>["id" => "DESC"]]);
       foreach ($start_array as $start) {}

       $date_to_start = strftime("%b %d, %Y", strtotime($start["date_to_start"])); 
       $date_to_end = strftime("%b %d, %Y", strtotime($start["date_to_end"])); 

       $now = date('Y-m-d H:i:s');
       $date_to_now = strftime("%b %d, %Y", strtotime($now)); 

       $nominees = $this->c->db->select('nominees', '*', ["ORDER" => ["date_added" => "DESC"]]);
       foreach ($nominees as $row) {

         $g = $this->c->db->count('votes', ["nominee" => $row["id"]]);
          
          $count[] = [
               "nomineeid" => $row["id"],
               "g" => $g,
          ];

       }

        return $this->c->view->render($response, 'frontend/results.twig', [
            'route_name' => $route_name,
            'web' => $web,
            'start' => $start,
            'nominees' => $nominees,
            'count' => $count,
            'categories' => $categories,
            'date_to_start' => $date_to_start,
            'date_to_end' => $date_to_end,
            'date_to_now' => $date_to_now,
        ]);
    } 
    
    

    public function categories(Request $request, Response $response)
    {

       $route = $request->getAttribute('route');
       $route_name = $route->getName();
       $settings = $this->c->db->select('settings', '*', ["id" => 1]);
       foreach ($settings as $web) {}
       $start = $this->c->db->has('start', []);

       $categories = $this->c->db->select('categories', '*', ["ORDER" => ["date_added" => "DESC"]]);

       $start_array = $this->c->db->select('start', '*', ["LIMIT" => 1, "ORDER" =>["id" => "DESC"]]);
       foreach ($start_array as $start) {}

       $date_to_start = strftime("%b %d, %Y", strtotime($start["date_to_start"])); 
       $date_to_end = strftime("%b %d, %Y", strtotime($start["date_to_end"])); 

       $now = date('Y-m-d H:i:s');
       $date_to_now = strftime("%b %d, %Y", strtotime($now)); 


        return $this->c->view->render($response, 'frontend/categories.twig', [
            'route_name' => $route_name,
            'web' => $web,
            'start' => $start,
            'categories' => $categories,
            'date_to_start' => $date_to_start,
            'date_to_end' => $date_to_end,
            'date_to_now' => $date_to_now,
        ]);
    }    
    


    public function graphs(Request $request, Response $response, $args)
    {

       $route = $request->getAttribute('route');
       $route_name = $route->getName();
       $settings = $this->c->db->select('settings', '*', ["id" => 1]);
       foreach ($settings as $web) {}
       $start = $this->c->db->has('start', []);

       $start_array = $this->c->db->select('start', '*', ["LIMIT" => 1, "ORDER" =>["id" => "DESC"]]);
       foreach ($start_array as $start) {}

       $date_to_start = strftime("%b %d, %Y", strtotime($start["date_to_start"])); 
       $date_to_end = strftime("%b %d, %Y", strtotime($start["date_to_end"])); 

       $now = date('Y-m-d H:i:s');
       $date_to_now = strftime("%b %d, %Y", strtotime($now)); 

       $cat = $this->c->db->select('categories', '*', ["ORDER" => ["date_added" => "DESC"]]);

       $id = $args['id'];
       $has = $this->c->db->has('categories', ["id" => $id]);
       if ($has === false) {
        return $response->withRedirect($this->c->router->pathFor('admin.categories'));
       }
        
       $categories = $this->c->db->select('categories', '*', ["AND" => ["id" => $id]]);
       foreach ($categories as $row) {

           $nominees = $this->c->db->select('nominees', '*', ["category_id" => $row["id"]]);
           foreach ($nominees as $nom) {

             $total_votes = $this->c->db->count('votes', ["category_id" => $row["id"]]);
             $g = $this->c->db->count('votes', ["nominee" => $nom["id"]]);

             $percentage = $g * 100 / $total_votes;   


              $count[] = [
                   "label" => $nom["name"]. ' - votes('. $g .')' ,
                   "y" => $percentage,
              ];
         
           }       

        }  
        
        $da = json_encode($count);      
        


        return $this->c->view->render($response, 'frontend/graphs.twig', [
            'route_name' => $route_name,
            'web' => $web,
            'start' => $start,
            'category' => $row,
            'categories' => $cat,
            'count' => $da,
            'date_to_start' => $date_to_start,
            'date_to_end' => $date_to_end,
            'date_to_now' => $date_to_now,
        ]);
    }   
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

   public function login($request, $response)
   {

       $settings = $this->c->db->select('settings', '*', ["id" => 1]);
       foreach ($settings as $web) {}

       

        return $this->c->view->render($response, 'frontend/login.twig', [
            'web' => $web,
        ]);
   }

   public function postlogin($request, $response)
   {

      $validation = $this->c->validator->validate($request, [
           'email' => v::noWhitespace()->notEmpty()->email(),
           'password' => v::noWhitespace()->notEmpty(),
      ]); 

      if ($validation->failed()) {
        return $response->withRedirect($this->c->router->pathFor('login'));
      }

      $auth = $this->c->user->login(
         $request->getParam('email'),
         $request->getParam('password')
      );
      
      if (!$auth) {

        $this->c->flash->addMessage('error', 'Please check your email or password!');
        return $response->withRedirect($this->c->router->pathFor('login'));
      }


       return $response->withRedirect($this->c->router->pathFor('home'));
   } 

   public function register($request, $response)
   {

       $settings = $this->c->db->select('settings', '*', ["id" => 1]);
       foreach ($settings as $web) {}

       

        return $this->c->view->render($response, 'frontend/register.twig', [
            'web' => $web,
        ]);
   }

    //Unique ID
    private function uniqueid()
    {
      $un = substr(number_format(time() * rand(),0,'',''),0,12);
      return $un;
    }   


    public function postregister(Request $request, Response $response)
    {

      $validation = $this->c->validator->validate($request, [
           'name' => v::notEmpty(),
           'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable($this->c->db),
           'password' => v::notEmpty(),
      ]); 

      if ($validation->failed()) {
        return $response->withRedirect($this->c->router->pathFor('register'));
      }
      
      $userid = $this->uniqueid();
      $salt = Hash::salt(32);

      //Insert
       $insert = $this->c->db->insert('user', [
         'userid' => $userid,
         'name' => $request->getParam('name'),
         'email' => $request->getParam('email'),
         'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
         'salt' => $salt,
         'joined' => date('Y-m-d H:i:s'),
        ]); 

      if ($insert->rowCount() == 1) {
        
        $this->c->flash->addMessage('success', 'You have successfully registered, Admin will verify your account then you can start to vote!');
        return $response->withRedirect($this->c->router->pathFor('login'));
      }else{

        $this->c->flash->addMessage('warning', 'Something went wrong with posting of data!');
        return $response->withRedirect($this->c->router->pathFor('login'));
      }


    }      

  public function logout($request, $response)
   {

     $this->c->user->logout();

     return $response->withRedirect($this->c->router->pathFor('home'));
   }  

    public function editprofile(Request $request, Response $response)
    {

       $route = $request->getAttribute('route');
       $route_name = $route->getName();
       $settings = $this->c->db->select('settings', '*', ["id" => 1]);
       foreach ($settings as $web) {}

       $start_array = $this->c->db->select('start', '*', ["LIMIT" => 1, "ORDER" =>["id" => "DESC"]]);
       foreach ($start_array as $start) {}

       $categories = $this->c->db->select('categories', '*', []);


        return $this->c->view->render($response, 'frontend/editprofile.twig', [
            'route_name' => $route_name,
            'web' => $web,
            'start' => $start,
            'categories' => $categories,
        ]);
    }

    public function editname(Request $request, Response $response)
    {

      $validation = $this->c->validator->validate($request, [
           'name' => v::notEmpty(),
           'email' => v::noWhitespace()->notEmpty()->email(),
      ]); 

      if ($validation->failed()) {
        return $response->withRedirect($this->c->router->pathFor('editprofile'));
      }

      //Update
      $update = $this->c->db->update('user',[
         'name' => $request->getParam('name'),
         'email' => $request->getParam('email'),
      ],[
          'userid' => $this->c->user->data()["userid"]
        ]);

      if ($update->rowCount() == 1) {
        
        $this->c->flash->addMessage('success', 'The details have been successfully updated!');
        return $response->withRedirect($this->c->router->pathFor('editprofile'));
      }else{

        $this->c->flash->addMessage('warning', 'You have made no changes!');
        return $response->withRedirect($this->c->router->pathFor('editprofile'));
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
        return $response->withRedirect($this->c->router->pathFor('editprofile'));
      }
    
      if (password_verify($request->getParam('current_password'), $this->c->user->data()["password"])) {

        //Update
        $update = $this->c->db->update('user',[
          'password' => password_hash($request->getParam('new_password'), PASSWORD_DEFAULT),
          'salt' => $salt
        ],[
            'userid' => $this->c->user->data()["userid"]
          ]);

        if ($update->rowCount() == 1) {
          
          $this->c->flash->addMessage('success', 'You have changed your password successfully!');
          return $response->withRedirect($this->c->router->pathFor('editprofile'));
        }else{

          $this->c->flash->addMessage('warning', 'You have made no changes!');
          return $response->withRedirect($this->c->router->pathFor('editprofile'));
        }
        
      } else {

        $this->c->flash->addMessage('error', 'Your current password does not match!');
        return $response->withRedirect($this->c->router->pathFor('editprofile'));
       
      }
    }



   public function vote($request, $response)
   {

      $nominee = $request->getParam('nominee');

      $nominees = $this->c->db->select('nominees', '*', ["id" => $nominee]);
      foreach ($nominees as $nom) {}

      $categories = $this->c->db->select('categories', '*', ["id" => $nom["category_id"]]);
      foreach ($categories as $cat) {}

      //Insert
       $insert = $this->c->db->insert('votes', [
         'userid' => $this->c->user->data()["userid"],
         'nominee' => $nominee,
         'category_id' => $nom["category_id"],
        ]); 

      if ($insert->rowCount() == 1) {
        
        $this->c->flash->addMessage('success', 'You have successfully Voted!');
        return $response->withRedirect($this->c->router->pathFor('nominees', ['slug' => $cat["slug"]]));
      }else{

        $this->c->flash->addMessage('warning', 'Something went wrong with posting of data!');
        return $response->withRedirect($this->c->router->pathFor('nominees', ['slug' => $cat["slug"]]));
      }
   }             

}
