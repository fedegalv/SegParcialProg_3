<?php

namespace App\Controllers;

use App\Models\Inscripcion;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Firebase\JWT\JWT;
use App\Models\Materia;

class InscripcionController{
   
    
    public function add(Request $request, Response $response, $args) {
        $idMateria = $args['idMateria'];
        $materia = Materia::where('id','=', $idMateria)->first();
        $token =  $request->getHeader('token');
        $decoded = JWT::decode($token[0], "segundoParcial", array('HS256'));
        //var_dump($materia);
        if( $materia != null || $materia != false)
        {
            if($materia->cupos > 0)
            {
                $inscripcion = new Inscripcion();
                $inscripcion->id_usuario  = $decoded->id;
                $inscripcion->id_materia  = $materia->id; 
                $rta = $inscripcion->save();
            }
            if ($rta) {
                $response->getBody()->write("INSCRIPCION AGREGADA CON EXITO");
            } else {
                $response->getBody()->write("HUBO UN ERROR AL REGISTRAR");
                return $response->withStatus(400);
            }
        }
        else{
            $response->getBody()->write("NO SE ENCONTRO LA MATERIA");
            return $response->withStatus(400);
        }
        return $response;
    }

    public function mostrarInscriptos(Request $request, Response $response, $args) {
        $idMateria = $args['idMateria'];
        $inscriptos = Inscripcion::join('materias', 'inscripciones.id_materia', 'materias.id')->where('id_materia', '=', $idMateria)->get();
        //echo "<pre>";
        //print_r($inscriptos);
        if( $inscriptos != null)
        {
            
            $response->getBody()->write(json_encode($inscriptos));
        }
        else{
            $response->getBody()->write("NO SE ENCONTRO LA MATERIA");
            return $response->withStatus(400);
        }
        return $response;
    }



}