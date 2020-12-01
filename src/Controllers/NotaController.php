<?php

namespace App\Controllers;

 
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\Materia;
use App\Models\Nota;

class NotaController{
    public function put(Request $request, Response $response, $args) {
        //var_dump($request);
        $idMateria = $args['idMateria'];
        $parsedBody = $request->getParsedBody();
        $nota = new Nota();
        $nota->id_materia = $idMateria;
        $nota->id_alumno = $parsedBody['idAlumno'];
        $nota->nota = $parsedBody['nota'];

        $rta = $nota->save();
        if ($rta) {
            $response->getBody()->write("NOTA AGREGADA CON EXITO");
        } else {
            $response->getBody()->write("HUBO UN ERROR AL REGISTRAR");
        }
        //$response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function mostrarNotas(Request $request, Response $response, $args) {
        $idMateria = $args['idMateria'];
        $inscriptos = Nota::join('materias', 'notas.id_materia', 'materias.id')->join('usuarios', 'notas.id_alumno', 'usuarios.id')->where('id_materia', '=', $idMateria)->select('notas.nota', 'materias.materia', 'usuarios.nombre')->get();
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