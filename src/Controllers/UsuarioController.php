<?php

namespace App\Controllers;


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Firebase\JWT\JWT;


use App\Models\Usuario;

class UsuarioController
{
    public function getAll(Request $request, Response $response, $args)
    {
        //CONSULTA PARA TRAER TODOS LOS RESULTADOS EN LA TABLA
        $rta = Usuario::get();

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
    public function GetOne(Request $request, Response $response, $args)
    {
        //FIND BUSCA POR ID, DEVUELVE UN OBJETO ECONTRADO O NULL
        $rta = Usuario::find($args['id']);
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function add(Request $request, Response $response, $args)
    {
        //CREA NUEVO ALUMNO
        $alumno = new Usuario();
        //DEBERIA SACAR DEL REQUEST EL PARSED BODY Y GUARDARLO
        $alumno->apellido = "Apellido test";
        $alumno->nombre = "Nombre TEST";
        $alumno->id_localidad = 2;

        //INSERTAR EN BASE DE DATO, DEVUELVE TRUE OR FALSE
        $rta = $alumno->save();

        $response->getBody()->write(json_encode($rta));
        return $response;
    }
    public function update(Request $request, Response $response, $args)
    {
        //BUSCA ALUMNO POR ID
        //$data = $args['id'];
        $alumno = Usuario::find($args['id']);

        /// SE HACE LOS CAMBIOS, DEBERIA SER POR BPARSED BODY
        $alumno->apellido = "APELLIDO PUT";
        //$alumno->nombre = "Nombre TEST";
        //$alumno->id_localidad = 2;

        //GUARDA EN BASE DE DATO, DEVUELVE TRUE OR FALSE
        $rta = $alumno->save();
        $response->getBody()->write(json_encode($rta));
        return $response;
    }
    public function delete(Request $request, Response $response, $args)
    {
        //BUSCA ALUMNO POR ID
        $alumno = Usuario::find($args['id']);

        //BORRA DE BASE DE DATO, DEVUELVE TRUE OR FALSE
        $rta = $alumno->delete();
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function registro(Request $request, Response $response)
    {
        $parsedBody = $request->getParsedBody();
        $usuario = new Usuario();
        $usuario->email = $parsedBody['email'];
        $usuario->clave = $parsedBody['clave'];
        $usuario->tipo = $parsedBody['tipo'];
        $usuario->nombre = $parsedBody['nombre'];

        $rta = $usuario->save();
        if ($rta) {
            $response->getBody()->write("USUARIO REGISTRADO CON EXITO");
        } else {
            $response->getBody()->write("HUBO UN ERROR AL REGISTRAR");
            return $response->withStatus(400);
        }
        //$response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function login(Request $request, Response $response)
    {

        $parsedBody = $request->getParsedBody();
        //$email = $parsedBody['email'];
        //$nombre = $parsedBody['nombre'];
        $clave = $parsedBody['clave'];
        if (isset($parsedBody['email'])) {

            $email = $parsedBody['email'];
            $rtaEmail = Usuario::where('email', '=', $email)
                ->where('clave', '=', $clave)->first();
            if ($rtaEmail != null || $rtaEmail != false) {
                $tipo = $rtaEmail->tipo;
                $payload = array(
                    "email" => $email,
                    "tipo" => $tipo,
                    "id" => $rtaEmail->id
                );
                $jwt = JWT::encode($payload, "segundoParcial");
                $response->getBody()->write(json_encode($jwt));
                return $response->withStatus(200);
            } else {
                $response->getBody()->write("LOGIN INCORRECTO");
                return $response->withStatus(400);
                return $response;
            }
        } else if (isset($parsedBody['nombre'])) {
            //COMPARA SI EL MAIL Y CLVE ESTAN EN BD
            $nombre = $parsedBody['nombre'];
            $rtaNombre = Usuario::where('nombre', '=', $nombre)
                ->where('clave', '=', $clave)->first();
            if ($rtaNombre != null || $rtaNombre != false) {
                $tipo = $rtaNombre->tipo;
                $payload = array(
                    "nombre" => $nombre,
                    "tipo" => $tipo,
                    "id" => $rtaNombre->id
                );
                $jwt = JWT::encode($payload, "segundoParcial");
                $response->getBody()->write(json_encode($jwt));
                return $response->withStatus(200);
            } else {
                $response->getBody()->write("LOGIN INCORRECTO");
                return $response->withStatus(400);
                return $response;
            }
        }
        $response->getBody()->write("NOMRE O EMAIL INVALIDO");
        return $response->withStatus(400);
    }
}
