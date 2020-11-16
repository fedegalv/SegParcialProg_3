<?php

namespace App\Controllers;

 
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Firebase\JWT\JWT;


use App\Models\Auto;
use App\Models\Precio;

class AutoController{
    public function getAll(Request $request, Response $response, $args) {
        //CONSULTA PARA TRAER TODOS LOS RESULTADOS EN LA TABLA
         $rta = Auto::get();
    
        $response->getBody()->write(json_encode($rta));
        return $response;
    }
    public function GetOne(Request $request, Response $response, $args) {
        //FIND BUSCA POR ID, DEVUELVE UN OBJETO ECONTRADO O NULL
        $rta = Auto::find($args['id']);
        $response->getBody()->write(json_encode($rta));
        return $response;
    }
    
    public function add(Request $request, Response $response, $args) {
        $parsedBody = $request->getParsedBody();
        //CREA NUEVO ALUMNO
        $auto = new Auto();
        $token =  $request->getHeader('token');
        $decoded = JWT::decode($token[0], "segundoParcial", array('HS256'));

        $auto->patente = $parsedBody['patente'];
        $auto->tipo  = $parsedBody['tipo'];
        $auto->fecha_ingreso = date('Y-m-d H:i');
        $auto->email_usuario  = $decoded->email;

        $rta = $auto->save();
        if($rta){
            $response->getBody()->write("AUTO INGRESADO CON EXITO");
        }
        else{
            $response->getBody()->write("HUBO UN ERROR AL REGISTRAR");
        }
        //$response->getBody()->write(json_encode($rta));
        return $response;
    }
    public function update(Request $request, Response $response, $args) {
        $alumno = Auto::find($args['id']);

        $alumno->apellido = "APELLIDO PUT";
       
        $rta = $alumno->save();
        $response->getBody()->write(json_encode($rta));
        return $response;
    }
    public function delete(Request $request, Response $response, $args) {
       //BUSCA ALUMNO POR ID
        $alumno = Auto::find($args['id']);

        //BORRA DE BASE DE DATO, DEVUELVE TRUE OR FALSE
        $rta = $alumno->delete();
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function retiro(Request $request, Response $response, $args) {
        $auto = Auto::where('patente', '=',$args['patente'])->first();
        //var_dump($auto->tipo);
        if($auto != null){
            switch($auto->tipo)
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
            //var_dump($precio);
            $date = date('Y-m-d H:i');
            $retiroMensaje= "Monto: ".$precio."<br>Patente: ".$args['patente']
            ."<br>Fecha Ingreso: ".$auto->fecha_ingreso
            ."<br>Fecha Egreso: ".$date;
            $response->getBody()->write($retiroMensaje);
        }
        else{
            $response->getBody()->write("HUBO UN ERROR AL RETIRAR AUTO");
        }
        return $response;
    }

    public function listaOrdenada(Request $request, Response $response, $args) {
        $listaOrdenada = Auto::all()->sortByDesc("tipo");
        $listaOrdenada[0];
        if($listaOrdenada != null){
            $response->getBody()->write(json_encode($listaOrdenada));
        }else{
            $response->getBody()->write("HUBO UN ERROR AL DEVOLVER LISTA");
        }
        return $response;
    }

    public function datoPorPatente(Request $request, Response $response, $args) {
        echo "datoPAtente";
        $auto = Auto::where('patente', '=',$args['patente'])->first();
        //var_dump($auto);
        if($auto != null){

            $response->getBody()->write(json_encode($auto));
        }
        else{
            $response->getBody()->write("ERROR: PATENTE INVALIDA ");
        }
        return $response;
    }

}