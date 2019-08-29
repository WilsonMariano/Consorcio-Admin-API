<?php

    include_once __DIR__ . '/../Usuarios.php';

    class UsuarioApi
    {
		
		public static function GetAll($request, $response, $args){
            return $response->withJson(Usuarios::GetAll(), 200);
        }

        public static function Insert($request, $response, $args){

            $datosRecibidos = $request->getParsedBody();

            $usuario = new Usuarios();
            $usuario->email = $datosRecibidos['email'];
            $usuario->password = $datosRecibidos['password'];
      
            $resultado = Usuarios::Insert($usuario);
    
            if(is_numeric($resultado) == true)
                return $response->withJson(true, 200);
            else
                return $response->withJson("Ha ocurrido un error insertando el usuario. Inténtelo nuevamente.", 500);
        }


    }
