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
use App\Controllers\NotaController;
use App\Middlewares\AdminAuthMiddleware;
use App\Middlewares\AdminOrProfessorAuthMiddleware;
use App\Middlewares\AlumnoAuthMiddleware;

use Slim\Routing\RouteCollectorProxy;

use App\Middlewares\JsonMiddleware;
use App\Middlewares\DatosValidosUsuario;
use App\Middlewares\ProfesorAuthMiddleware;


require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();


$app->setBasePath('/SegParcialProg_3/public');

new Database;
//1
$app->group('/users', function (RouteCollectorProxy $group) {
    $group->post('[/]', UsuarioController:: class .":registro")->add(new DatosValidosUsuario);
})->add(new JsonMiddleware);
//2
$app->group('/login', function (RouteCollectorProxy $group) {
    $group->post('[/]', UsuarioController:: class .":login");
})->add(new JsonMiddleware);
//3
$app->group('/materia', function (RouteCollectorProxy $group) {
    $group->post('[/]', MateriaController:: class .":add")->add(new AdminAuthMiddleware);
})->add(new JsonMiddleware);
//4
$app->group('/inscripcion', function (RouteCollectorProxy $group){
    $group->post('[/{idMateria}]', InscripcionController:: class .":add")->add(new AlumnoAuthMiddleware);

})->add(new JsonMiddleware);
//5
$app->group('/notas', function (RouteCollectorProxy $group) {
    $group->post('[/{idMateria}]', NotaController:: class .":put")->add(new ProfesorAuthMiddleware);
})->add(new JsonMiddleware);
//6
$app->group('/inscripcion', function (RouteCollectorProxy $group){
    $group->get('[/{idMateria}]', InscripcionController:: class .":mostrarInscriptos")->add(new AdminOrProfessorAuthMiddleware);

})->add(new JsonMiddleware);

//7
$app->group('/materia', function (RouteCollectorProxy $group){
    $group->get('[/]', MateriaController:: class .":getAll")->add(new AdminOrProfessorAuthMiddleware);

})->add(new JsonMiddleware);
//8
$app->group('/notas', function (RouteCollectorProxy $group){
    $group->get('[/{idMateria}]', NotaController:: class .":mostrarNotas")->add(new AdminOrProfessorAuthMiddleware);

})->add(new JsonMiddleware);


$app->run();