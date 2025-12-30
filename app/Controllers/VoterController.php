<?php

namespace App\Controllers;

use App\Classes\User;
use App\Controllers\Controller;
use Psr\Http\Message\{
    ServerRequestInterface as Request,
    ResponseInterface as Response
};

class VoterController extends Controller
{
    public function index(Request $request, Response $response)
    {

       $route = $request->getAttribute('route');
       $route_name = $route->getName();
       $settings = $this->c->db->select('settings', '*', ["id" => 1]);
       foreach ($settings as $web) {}
       $start = $this->c->db->has('start', []);

       $voters = $this->c->db->select('user', '*', ["ORDER" => ["joined" => "DESC"]]);


        return $this->c->view->render($response, 'backend/verify_voters.twig', [
            'route_name' => $route_name,
            'web' => $web,
            'start' => $start,
            'voters' => $voters,
        ]);

    } 


    public function approve(Request $request, Response $response, $args)
    {

      // get id value
      $id = $args['id'];
        
       $users = $this->c->db->select('user', '*', ["userid" => $id]);
       foreach ($users as $user) {}

     $has = $this->c->db->has('user', ["userid" => $id]);
     if ($has === false) {
      return $response->withRedirect($this->c->router->pathFor('admin.registered'));
     }

      //Update
      $update = $this->c->db->update('user',[
         'verified' => 1,
      ],[
          'userid' => $id
        ]);

      if ($update->rowCount() == 1) {
          

            $this->c->mailer->sendMessage('emails/vote.twig', ['user' => $user], function($message) use($user) {
                $message->setTo($user['email'], $user['name']);
                $message->setSubject('You have been accepted to Vote!');
            }); 

            //$response->getBody()->write('Mail sent!');          
        
        $this->c->flash->addMessage('success', 'Voter Approved!');
        return $response->withRedirect($this->c->router->pathFor('admin.registered'));
      }else{

        $this->c->flash->addMessage('warning', 'No changes made!');
        return $response->withRedirect($this->c->router->pathFor('admin.registered'));
      }


    }  


    public function decline(Request $request, Response $response, $args)
    {

      // get id value
      $id = $args['id'];
        
       $users = $this->c->db->select('user', '*', ["userid" => $id]);
       foreach ($users as $user) {}

     $has = $this->c->db->has('user', ["userid" => $id]);
     if ($has === false) {
      return $response->withRedirect($this->c->router->pathFor('admin.registered'));
     }

      //Update
      $update = $this->c->db->update('user',[
         'verified' => 2,
      ],[
          'userid' => $id
        ]);

      if ($update->rowCount() == 1) {
          

            $this->c->mailer->sendMessage('emails/declined.twig', ['user' => $user], function($message) use($user) {
                $message->setTo($user['email'], $user['name']);
                $message->setSubject('You have been declined to Vote!');
            });
        
        $this->c->flash->addMessage('success', 'Voter Declined!');
        return $response->withRedirect($this->c->router->pathFor('admin.registered'));
      }else{

        $this->c->flash->addMessage('warning', 'No changes made!');
        return $response->withRedirect($this->c->router->pathFor('admin.registered'));
      }


    }   

    public function voters(Request $request, Response $response)
    {

       $route = $request->getAttribute('route');
       $route_name = $route->getName();
       $settings = $this->c->db->select('settings', '*', ["id" => 1]);
       foreach ($settings as $web) {}
       $start = $this->c->db->has('start', []);

       $voters = $this->c->db->select('user', '*', ["verified" => 1, "ORDER" => ["joined" => "DESC"]]);
       foreach ($voters as $row) {

         $g = $this->c->db->count('votes', ["userid" => $row["userid"]]);
          
          $count[] = [
               "userid" => $row["userid"],
               "g" => $g,
          ];

       }   


        return $this->c->view->render($response, 'backend/voters.twig', [
            'route_name' => $route_name,
            'web' => $web,
            'start' => $start,
            'voters' => $voters,
            'votes' => $count,
        ]);

    }  

    public function voter(Request $request, Response $response)
    {

       $route = $request->getAttribute('route');
       $route_name = $route->getName();
       $settings = $this->c->db->select('settings', '*', ["id" => 1]);
       foreach ($settings as $web) {}
       $start = $this->c->db->has('start', []);

       $voters = $this->c->db->select('user', '*', ["verified" => 1, "ORDER" => ["joined" => "DESC"]]);
       $nominees = $this->c->db->select('nominees', '*', []);
       $votes = $this->c->db->select('votes', '*', []);


        return $this->c->view->render($response, 'backend/voter_results.twig', [
            'route_name' => $route_name,
            'web' => $web,
            'start' => $start,
            'voters' => $voters,
            'nominees' => $nominees,
            'votes' => $votes,
        ]);

    }  


    public function delete(Request $request, Response $response, $args)
    {

      // get id value
      $id = $args['id'];

     $has = $this->c->db->has('user', ["userid" => $id]);
     if ($has === false) {
      return $response->withRedirect($this->c->router->pathFor('admin.voters'));
     }
       
       // delete the entry
      $delete = $this->c->db->delete('user', ['userid' => $id]);

      if ($delete->rowCount() == 1) {
        
        $this->c->flash->addMessage('success', 'Voter deleted successfully!');
        return $response->withRedirect($this->c->router->pathFor('admin.voters'));
      }else{

        $this->c->flash->addMessage('error', 'Unable to delete!');
        return $response->withRedirect($this->c->router->pathFor('admin.voters'));
      }


    }   
 

}
