<?php

include_once __DIR__ . '/../Usuarios.php';
include_once __DIR__ . '/../AutentificadorJWT.php';


class usuarioApi 
    {
       public function Login($request, $response, $args)
       {
            $datosRecibidos = $request->getParsedBody();

            $usuarioRec = new Usuarios();

            $usuarioRec->email      = $datosRecibidos['email'];
            $usuarioRec->password   = $datosRecibidos['password'];
  
            $usuarioBuscado = Usuarios::Login($usuarioRec);

            if(!$usuarioBuscado)
            {
                return $response->withJson('Usuario invalido', 404);  
            }
            else
            {
                $token = AutentificadorJWT::CrearToken($usuarioBuscado);
                return $response->withJson($token, 200);
            }
            
       } 
    }