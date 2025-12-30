<?php

namespace App\Controllers;

use App\Controllers\Controller;
use Psr\Http\Message\{
    ServerRequestInterface as Request,
    ResponseInterface as Response
};
use Respect\Validation\Validator as v;

class CategoryController extends Controller
{
    public function index(Request $request, Response $response)
    {

       $route = $request->getAttribute('route');
       $route_name = $route->getName();
       $settings = $this->c->db->select('settings', '*', ["id" => 1]);
       foreach ($settings as $web) {}
       $start = $this->c->db->has('start', []);

       $categories = $this->c->db->select('categories', '*', ["ORDER" => ["date_added" => "DESC"]]);


        return $this->c->view->render($response, 'backend/categories.twig', [
            'route_name' => $route_name,
            'web' => $web,
            'start' => $start,
            'categories' => $categories,
        ]);
    }

    public function add(Request $request, Response $response)
    {

       $route = $request->getAttribute('route');
       $route_name = $route->getName();
       $settings = $this->c->db->select('settings', '*', ["id" => 1]);
       foreach ($settings as $web) {}
       $start = $this->c->db->has('start', []);


        return $this->c->view->render($response, 'backend/category_add.twig', [
            'route_name' => $route_name,
            'web' => $web,
            'start' => $start,
        ]);
    }

    public function postadd(Request $request, Response $response)
    {

      $validation = $this->c->validator->validate($request, [
           'title' => v::notEmpty(),
      ]); 

      if ($validation->failed()) {
        return $response->withRedirect($this->c->router->pathFor('admin.category_add'));
      }

      $slug = $this->slugify($request->getParam('title'));

      //Insert
       $insert = $this->c->db->insert('categories', [
         'title' => $request->getParam('title'),
         'slug' => $slug,
         'date_added' => date('Y-m-d H:i:s'),
        ]); 

      if ($insert->rowCount() == 1) {
        
        $this->c->flash->addMessage('success', 'Category successfully added!');
        return $response->withRedirect($this->c->router->pathFor('admin.category_add'));
      }else{

        $this->c->flash->addMessage('warning', 'Something went wrong with posting of data!');
        return $response->withRedirect($this->c->router->pathFor('admin.category_add'));
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
       $has = $this->c->db->has('categories', ["id" => $id]);
       if ($has === false) {
        return $response->withRedirect($this->c->router->pathFor('admin.categories'));
       }

       $categories = $this->c->db->select('categories', '*', ["AND" => ["id" => $id]]);
       foreach ($categories as $category) {}


        return $this->c->view->render($response, 'backend/category_edit.twig', [
            'route_name' => $route_name,
            'web' => $web,
            'start' => $start,
            'category' => $category,
        ]);
    }   

    public function postedit(Request $request, Response $response)
    {

      $id = $request->getParam('id');


      $validation = $this->c->validator->validate($request, [
           'title' => v::notEmpty(),
      ]); 

      if ($validation->failed()) {
        return $response->withRedirect($this->c->router->pathFor('admin.category_edit', ['id' => $id]));
      }
      
      $slug = $this->slugify($request->getParam('title'));

      //Update
      $update = $this->c->db->update('categories',[
         'title' => $request->getParam('title'),
         'slug' => $slug,
      ],[
          'id' => $id
        ]);

      if ($update->rowCount() == 1) {
        
        $this->c->flash->addMessage('success', 'Category successfully updated!');
        return $response->withRedirect($this->c->router->pathFor('admin.category_edit', ['id' => $id]));
      }else{

        $this->c->flash->addMessage('warning', 'No changes made!');
        return $response->withRedirect($this->c->router->pathFor('admin.category_edit', ['id' => $id]));
      }


    }     

    public function getdelete(Request $request, Response $response, $args)
    {

      // get id value
      $id = $args['id'];
       
       // delete the entry
      $delete = $this->c->db->delete('categories', ['id' => $id]);

      if ($delete->rowCount() == 1) {
        
        $this->c->flash->addMessage('success', 'Category deleted successfully!');
        return $response->withRedirect($this->c->router->pathFor('admin.categories'));
      }else{

        $this->c->flash->addMessage('error', 'Unable to delete!');
        return $response->withRedirect($this->c->router->pathFor('admin.categories'));
      }


    }  
    


    public function nominees(Request $request, Response $response, $args)
    {

       $route = $request->getAttribute('route');
       $route_name = $route->getName();
       $settings = $this->c->db->select('settings', '*', ["id" => 1]);
       foreach ($settings as $web) {}
       $start = $this->c->db->has('start', []);

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
        


        return $this->c->view->render($response, 'backend/category_nominees.twig', [
            'route_name' => $route_name,
            'web' => $web,
            'start' => $start,
            'category' => $row,
            'count' => $da,
        ]);
    }       

    private function slugify($text){
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicated - symbols
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
          return 'n-a';
        }

        return $text;
    }    

}
