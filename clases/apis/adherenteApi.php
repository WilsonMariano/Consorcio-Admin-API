<?php

    include_once __DIR__ . '/../Adherentes.php';
    include_once __DIR__ . '/../_FuncionesEntidades.php';

    class AdherenteApi
    {
		
        public static function Insert($request, $response, $args){

            $datosRecibidos = $request->getParsedBody();

            $objAdherente = new Adherentes();
            $objAdherente->id           = $datosRecibidos['id'];
            $objAdherente->nombre       = $datosRecibidos['nombre'];
            $objAdherente->apellido     = $datosRecibidos['apellido'];
            $objAdherente->nroDocumento = $datosRecibidos['nroDocumento'];
            $objAdherente->telefono     = $datosRecibidos['telefono'];
            $objAdherente->email        = $datosRecibidos['email'];
 
			//Valido que el id no esté duplicado antes de insertar
			if(!Funciones::IsDuplicated($objAdherente))
				$resultado = Adherentes::Insert($objAdherente);
			else 
				return $response->withJson("El nro de adherente ingresado no se encuentra disponible.", 409);
	
	
            if(is_numeric($resultado) == true)
                return $response->withJson(true, 200);
            else
                return $response->withJson(false, 400);
        }

		


    }
