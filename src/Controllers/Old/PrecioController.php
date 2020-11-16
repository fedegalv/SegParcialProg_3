<?php

namespace App\Controllers;

use App\Models\Precio;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PrecioController{
    public function agregaPrecio(Request $request, Response $response, $args) {
        //echo "precio controller";
        $parsedBody = $request->getParsedBody();
        $precio = Precio::find(1);
        $precio->precio_hora= $parsedBody['precio_hora'];
        $precio->precio_estadia = $parsedBody['precio_estadia'];
        $precio->precio_mensual = $parsedBody['precio_mensual'];

        $rta = $precio->save();
        if($rta){
            $response->getBody()->write("PRECIO GUARDADO CON EXITO");
        }
        else{
            $response->getBody()->write("HUBO UN ERROR AL GUARDAR PRECIO");
        }
        return $response;
    }
    public function totalPorTipo(Request $request, Response $response, $args) {
        //echo "precio controller";
        $tipo = $args['tipo'];
        $rta = Precio::find(1);
        switch($tipo)
            {
                case 'hora':
                    {
                        $precio = Precio::select('precio_hora')->get();
                        //var_dump($precio);
                    }
                break;
                case 'estadia':
                    {
                        $precio = Precio::select('precio_estadia')->get();
                    }
                break;
                case 'mensual':
                {
                    $precio = Precio::select('precio_mensual')->get();
                }
            }
        if($rta){
            $response->getBody()->write("PRECIO GUARDADO CON EXITO");
        }
        else{
            $response->getBody()->write("HUBO UN ERROR AL GUARDAR PRECIO");
        }
        return $response;
    }
}