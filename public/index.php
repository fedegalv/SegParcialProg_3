<?php
///1- EN CONSOLE composer init
///2- LUEGO composer require slim/slim:"4.*"
//3- LUEGO composer require slim/psr7
//4- USAR EL CODIGO DEBAJO COMO TEMPLATE
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
//AGREGAR RequestHandler;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;

//10-LUEGO DE CREAR EL NAMESPACE CONFIG AGREGARLO
use Config\Database;
//LUEGO DE CREAR EL NAMESPACE APP CON LA CLASE MODELO AGREGAR
//use App\Models\Alumno;
//EL MODELO NO HACE FALTA AGREGAR YA QUE SE MANEJA DESDE CONTROLLER
use App\Controllers;
use App\Controllers\UsuarioController;
use App\Controllers\MateriaController;
use App\Controllers\InscripcionController;

use App\Middlewares\AdminAuthMiddleware;
use App\Middlewares\AlumnoAuthMiddleware;

use Slim\Routing\RouteCollectorProxy;

use App\Middlewares\JsonMiddleware;
use App\Middlewares\DatosValidosUsuario;
use App\Middlewares\UserAuthMiddleware;
use App\Middlewares\UserOrAdminAuthMiddleware;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
//5-PONER BASEPATH DONDE ESTA EL INDEX.php 
///6-COPIAR EL .htacess  MISMA ALTURA QUE INDEX EN PUBLIC
//7-BUSCAR ACA https://packagist.org/packages/illuminate/database
// INSTALAR composer require illuminate/database
//8- SETEAR EN database.php NOMBRE DE LA BD, PASSWORD A "" Y USER SI NECESARIO
//9- USAR composer require "illuminate/events"

$app->setBasePath('/SegParcialProg_3/public');
//11- INSTANCIAR Database
new Database;
//12- CREAR EN src LA CARPETA Models, donde van los MODELS DE LA DB

//SE REEMPLAZO LO QUE QUEDO EN OLDIndex POR ESTO
//PARA PODER USAR EL USERCONTROLLER
$app->group('/users', function (RouteCollectorProxy $group) {
    $group->post('[/]', UsuarioController:: class .":registro")->add(new DatosValidosUsuario);
})->add(new JsonMiddleware);

$app->group('/login', function (RouteCollectorProxy $group) {
    $group->post('[/]', UsuarioController:: class .":login");
})->add(new JsonMiddleware);

$app->group('/materia', function (RouteCollectorProxy $group) {
    $group->post('[/]', MateriaController:: class .":add")->add(new AdminAuthMiddleware);
})->add(new JsonMiddleware);

$app->group('/inscripcion', function (RouteCollectorProxy $group){
    $group->post('[/{idMateria}]', InscripcionController:: class .":add")->add(new AlumnoAuthMiddleware);

})->add(new JsonMiddleware);

$app->run();
