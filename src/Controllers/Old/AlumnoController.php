<?php

namespace App\Controllers;

 
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\Alumno; 
class AlumnoController{
    public function getAll(Request $request, Response $response, $args) {
        //CONSULTA PARA TRAER TODOS LOS RESULTADOS EN LA TABLA
         $rta = Alumno::get();
    
        /// CONSULTA PARA TRAER Alumnos con id = 4
        //$rta = Alumno::where('id', '=', 4)->get();
    
        
        /// CONSULTA PARA TRAER Alumnos con id = 4 Y ENCADENAR id_localidad = 3
        //$rta = Alumno::where('id', '=', 4)
        //->where('id_localidad', '=', 3)->get();
    
        //TRAER EL PRIMER RESULTADO
        //$rta = Alumno::first();
    
        ///SE COMBIna CON WHERE PARA QUE TE TRAIGA COMO OBJETO Y NO COMO ARRAY
        //$rta = Alumno::where('id', '=', 1)->first();
    
        //FIND BUSCA POR ID, DEVUELVE UN OBJETO ECONTRADO O NULL
        //$rta = Alumno::find(1);
    
        //SE PUEDE HACER CON EXCEPCIONES; SI NO HAY RESULTADO TIRA EXCEPTION
        //$rta = Alumno::where('id', '=', 1)->firstOrFail();
        $response->getBody()->write(json_encode($rta));
        return $response;
    }
    public function GetOne(Request $request, Response $response, $args) {
        //FIND BUSCA POR ID, DEVUELVE UN OBJETO ECONTRADO O NULL
        $rta = Alumno::find($args['id']);
        $response->getBody()->write(json_encode($rta));
        return $response;
    }
    
    public function add(Request $request, Response $response, $args) {
        //CREA NUEVO ALUMNO
        $alumno = new Alumno();
        //DEBERIA SACAR DEL REQUEST EL PARSED BODY Y GUARDARLO
        $alumno->apellido = "Apellido test";
        $alumno->nombre = "Nombre TEST";
        $alumno->id_localidad = 2;

        //INSERTAR EN BASE DE DATO, DEVUELVE TRUE OR FALSE
        $rta = $alumno->save();

        $response->getBody()->write(json_encode($rta));
        return $response; 
    }
    public function update(Request $request, Response $response, $args) {
        //BUSCA ALUMNO POR ID
        //$data = $args['id'];
        $alumno = Alumno::find($args['id']);

        /// SE HACE LOS CAMBIOS, DEBERIA SER POR BPARSED BODY
        $alumno->apellido = "APELLIDO PUT";
        //$alumno->nombre = "Nombre TEST";
        //$alumno->id_localidad = 2;

        //GUARDA EN BASE DE DATO, DEVUELVE TRUE OR FALSE
        $rta = $alumno->save();
        $response->getBody()->write(json_encode($rta));
        return $response;
    }
    public function delete(Request $request, Response $response, $args) {
       //BUSCA ALUMNO POR ID
        $alumno = Alumno::find($args['id']);

        //BORRA DE BASE DE DATO, DEVUELVE TRUE OR FALSE
        $rta = $alumno->delete();
        $response->getBody()->write(json_encode($rta));
        return $response;
    }


}