<?php 

     require_once './system/function.php';
     require_once './controller/register.php';

     // $uri = parse_url($_SERVER['REQUEST_URI'])['path'];

     // $routes = [
     // '/login' => './login.php'
     // ];

     // function routeController($uri,$routes) {
     //      if(array_key_exists($uri, $routes)) {
     //           require $routes[$uri];
     //      }else {
     //           abort(404);
     //      }
     // }


     // Router sınıfı
class Router {
     private $routes = [];
 
     public function addRoute($method, $path, $handler) {
         $this->routes[$method][$path] = $handler;
     }
 
     public function handleRequest($method, $uri) {
          if (!isset($this->routes[$method])) {
               echo "404 Not Found";
               return;
          }
         foreach ($this->routes[$method] as $route => $handler) {
             $pattern = preg_replace('/\//', '\\/', $route);
             $pattern = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[^\/]+)', $pattern);
             $pattern = '/^' . $pattern . '$/i';
 
             if (preg_match($pattern, $uri, $matches)) {
                 array_shift($matches);
                 return call_user_func_array($handler, array_values($matches));
             }
         }
 
         echo "404 Not Found";
     }
 }
 
 // Router nesnesi oluşturma
 $router = new Router();
 
 // Routeların tanımlanması
 $router->addRoute('POST', '/B2B/login.php', [new SellersController(), 'register']);
//  $router->addRoute('GET', '/B2B/login.php', [new SellersController(), 'registera']);
 $router->handleRequest($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);


?>