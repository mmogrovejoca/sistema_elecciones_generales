<?php

namespace App\Controllers;

use App\Controllers\Controller;
use Psr\Http\Message\{
    ServerRequestInterface as Request,
    ResponseInterface as Response
};
use Respect\Validation\Validator as v;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;

class NomineeController extends Controller
{
    public function index(Request $request, Response $response)
    {

       $route = $request->getAttribute('route');
       $route_name = $route->getName();
       $settings = $this->c->db->select('settings', '*', ["id" => 1]);
       foreach ($settings as $web) {}
       $start = $this->c->db->has('start', []);

       $nominees = $this->c->db->select('nominees', '*', ["ORDER" => ["date_added" => "DESC"]]);


        return $this->c->view->render($response, 'backend/nominees.twig', [
            'route_name' => $route_name,
            'web' => $web,
            'start' => $start,
            'nominees' => $nominees,
        ]);
    }

    public function add(Request $request, Response $response)
    {

       $route = $request->getAttribute('route');
       $route_name = $route->getName();
       $settings = $this->c->db->select('settings', '*', ["id" => 1]);
       foreach ($settings as $web) {}
       $start = $this->c->db->has('start', []);

       $categories = $this->c->db->select('categories', '*', []);


        return $this->c->view->render($response, 'backend/nominee_add.twig', [
            'route_name' => $route_name,
            'web' => $web,
            'start' => $start,
            'categories' => $categories,
        ]);
    }

    public function postadd(Request $request, Response $response)
    {

      $validation = $this->c->validator->validate($request, [
           'name' => v::notEmpty(),
           'tag_line' => v::notEmpty(),
      ]);

      if ($validation->failed()) {
        return $response->withRedirect($this->c->router->pathFor('admin.nominee_add'));
      }

      $id = $request->getParam('category_id');
      $categories = $this->c->db->select('categories', '*', ["id" => $id]);
      foreach ($categories as $cat) {}

      // Check image if uploaded
      $files = $request->getUploadedFiles();
      if (empty($files['photoimg'])) {
          $this->c->flash->addMessage('error', 'Image is empty.');
          return $response->withRedirect($this->c->router->pathFor('admin.nominee_add'));
      }

      //Random String
      function rando($length = 10){
        $str = "";
        $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
          $rand = mt_rand(0, $max);
          $str .= $characters[$rand];
        }
        return $str;
      }

      // Upload Image
      $myFile = $files['photoimg'];
      if ($myFile->getError() === UPLOAD_ERR_OK) {

          $extension = pathinfo($myFile->getClientFilename(), PATHINFO_EXTENSION);
          $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
          $uploadFileName = sprintf('%s.%0.8s', $basename, $extension);

          $myFile->moveTo('uploads/nominees/' . $uploadFileName);
      }

      //Insert
       $insert = $this->c->db->insert('nominees', [
         'name' => $request->getParam('name'),
         'tag_line' => $request->getParam('tag_line'),
         'imagelocation' => $uploadFileName,
         'category_id' => $id,
         'category' => $cat["title"],
         'date_added' => date('Y-m-d H:i:s'),
        ]);

      if ($insert->rowCount() == 1) {

        $this->c->flash->addMessage('success', 'Nominee successfully added!');
        return $response->withRedirect($this->c->router->pathFor('admin.nominee_add'));
      }else{

        $this->c->flash->addMessage('warning', 'Something went wrong with posting of data!');
        return $response->withRedirect($this->c->router->pathFor('admin.nominee_add'));
      }


    }

    public function edit(Request $request, Response $response, $args)
    {

       $route = $request->getAttribute('route');
       $route_name = $route->getName();
       $settings = $this->c->db->select('settings', '*', ["id" => 1]);
       foreach ($settings as $web) {}
       $start = $this->c->db->has('start', []);

       $id = $args['id'];
       $has = $this->c->db->has('nominees', ["id" => $id]);
       if ($has === false) {
        return $response->withRedirect($this->c->router->pathFor('admin.nominees'));
       }

       $nominees = $this->c->db->select('nominees', '*', ["AND" => ["id" => $id]]);
       foreach ($nominees as $nominee) {}
       $categories = $this->c->db->select('categories', '*', []);


        return $this->c->view->render($response, 'backend/nominee_edit.twig', [
            'route_name' => $route_name,
            'web' => $web,
            'start' => $start,
            'nominee' => $nominee,
            'categories' => $categories,
        ]);
    }

    public function postedit(Request $request, Response $response)
    {

      $id = $request->getParam('id');

      $category_id = $request->getParam('category_id');
      $categories = $this->c->db->select('categories', '*', ["id" => $category_id]);
      foreach ($categories as $cat) {}


      $validation = $this->c->validator->validate($request, [
           'name' => v::notEmpty(),
           'tag_line' => v::notEmpty(),
      ]);

      if ($validation->failed()) {
        return $response->withRedirect($this->c->router->pathFor('admin.nominee_edit', ['id' => $id]));
      }


      //Update
      $update = $this->c->db->update('nominees',[
         'name' => $request->getParam('name'),
         'tag_line' => $request->getParam('tag_line'),
         'category_id' => $category_id,
         'category' => $cat["title"],
      ],[
          'id' => $id
        ]);

      if ($update->rowCount() == 1) {

        $this->c->flash->addMessage('success', 'Nominee successfully updated!');
        return $response->withRedirect($this->c->router->pathFor('admin.nominee_edit', ['id' => $id]));
      }else{

        $this->c->flash->addMessage('warning', 'No changes made!');
        return $response->withRedirect($this->c->router->pathFor('admin.nominee_edit', ['id' => $id]));
      }


    }

      public function image(Request $request, Response $response)
    {

      $id = $request->getParam('id');

      // Check image if uploaded
      $files = $request->getUploadedFiles();
      if (empty($files['photoimg'])) {
          $this->c->flash->addMessage('error', 'Image is empty.');
          return $response->withRedirect($this->c->router->pathFor('admin.nominee_edit', ['id' => $id]));
      }

      // Unlink Image
      $imagelocation = 'uploads/nominees/' . $request->getParam('imagelocation');

      if (file_exists($imagelocation)) {
          unlink($imagelocation);
      }
      else {
            $this->c->flash->addMessage('error', 'Image file does not exist.');
            return $response->withRedirect($this->c->router->pathFor('admin.nominee_edit', ['id' => $id]));
      }

      // Upload Image
      $myFile = $files['photoimg'];
      if ($myFile->getError() === UPLOAD_ERR_OK) {

          $extension = pathinfo($myFile->getClientFilename(), PATHINFO_EXTENSION);
          $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
          $uploadFileName = sprintf('%s.%0.8s', $basename, $extension);

          $myFile->moveTo('uploads/nominees/' . $uploadFileName);
      }

      //Update
      $update = $this->c->db->update('nominees',[
         'imagelocation' => $uploadFileName,
      ],[
          'id' => $id
        ]);

      if ($update->rowCount() == 1) {

        $this->c->flash->addMessage('success', 'Nominee Image successfully updated!');
        return $response->withRedirect($this->c->router->pathFor('admin.nominee_edit', ['id' => $id]));
      }else{

        $this->c->flash->addMessage('warning', 'No changes made!');
        return $response->withRedirect($this->c->router->pathFor('admin.nominee_edit', ['id' => $id]));
      }


    }

    public function delete(Request $request, Response $response, $args)
    {

      // get id value
      $id = $args['id'];

     $nominees = $this->c->db->select('nominees', '*', ["AND" => ["id" => $id]]);
     foreach ($nominees as $nominee) {}

      // Unlink Image
      $imagelocation = 'uploads/nominees/' . $nominee["imagelocation"];

      if (file_exists($imagelocation)) {
          unlink($imagelocation);
      }
      else {
            $this->c->flash->addMessage('error', 'Image file does not exist.');
            return $response->withRedirect($this->c->router->pathFor('admin.nominees'));
      }

       // delete the entry
      $delete = $this->c->db->delete('nominees', ['id' => $id]);

      if ($delete->rowCount() == 1) {

        $this->c->flash->addMessage('success', 'Nominee deleted successfully!');
        return $response->withRedirect($this->c->router->pathFor('admin.nominees'));
      }else{

        $this->c->flash->addMessage('error', 'Unable to delete!');
        return $response->withRedirect($this->c->router->pathFor('admin.nominees'));
      }


    }

    public function results(Request $request, Response $response)
    {

       $route = $request->getAttribute('route');
       $route_name = $route->getName();
       $settings = $this->c->db->select('settings', '*', ["id" => 1]);
       foreach ($settings as $web) {}
       $start = $this->c->db->has('start', []);

       // Fetch detailed votes
       $votes = $this->c->db->select('votes', [
           '[>]nominees' => ['nominee' => 'id']
       ], [
           'votes.name',
           'votes.dni',
           'votes.dob',
           'votes.signature',
           'votes.created_at',
           'nominees.name(nominee_name)'
       ], [
           "ORDER" => ["votes.created_at" => "DESC"]
       ]);

        return $this->c->view->render($response, 'backend/results.twig', [
            'route_name' => $route_name,
            'web' => $web,
            'start' => $start,
            'votes' => $votes,
       ]);
    }

    public function graphs(Request $request, Response $response)
    {

       $route = $request->getAttribute('route');
       $route_name = $route->getName();
       $settings = $this->c->db->select('settings', '*', ["id" => 1]);
       foreach ($settings as $web) {}
       $start = $this->c->db->has('start', []);

       $categories = $this->c->db->select('categories', '*', []);
       foreach ($categories as $row) {

           $nominees = $this->c->db->select('nominees', '*', ["category_id" => $row["id"]]);
           foreach ($nominees as $nom) {

             $total_votes = $this->c->db->count('votes', ["category_id" => $row["id"]]);
             $g = $this->c->db->count('votes', ["nominee" => $nom["id"]]);

             $percentage = $g * 100 / $total_votes;


              $count[] = [
                   "label" => $nom["name"].' of category - '.$row["title"],
                   "y" => $percentage,
              ];

           }
            //$cat[] = $count;
            //$count = [];

       }

        $data = array(
            array("y" => 7,"label" => "March" ),
            array("y" => 12,"label" => "April" ),
            array("y" => 28,"label" => "May" ),
            array("y" => 18,"label" => "June" ),
            array("y" => 41,"label" => "July" )
        );

        $dataPoints = json_encode($data);
        $da = json_encode($count);
        //$pa = json_encode($cat);

        return $this->c->view->render($response, 'backend/graphs.twig', [
            'route_name' => $route_name,
            'web' => $web,
            'start' => $start,
            'dataPoints' => $dataPoints,
            'count' => $da,
            //'cat' => $pa,
            'categories' => $categories,
        ]);
    }

}
