<?php

    include_once __DIR__ . '/../UF.php';
    include_once __DIR__ . '/../_FuncionesEntidades.php';

    class UFApi
    {
		
        public static function Insert($request, $response, $args){

            $datosRecibidos = $request->getParsedBody();

            $objUF = new UF();
            $objUF->id            = $datosRecibidos['id'];
            $objUF->idManzana     = $datosRecibidos['idManzana'];
            $objUF->idAdherente   = $datosRecibidos['idAdherente'];
            $objUF->nroEdificio   = $datosRecibidos['nroEdificio'];
            $objUF->departamento  = $datosRecibidos['departamento'];
            $objUF->codSitLegal   = $datosRecibidos['codSitLegal'];
            $objUF->coeficiente   = $datosRecibidos['coeficiente'];
            $objUF->codAlquila    = $datosRecibidos['codAlquila'];
  
			//Valido que el id no esté duplicado antes de insertar
			if(!Funciones::IsDuplicated($objUF))
				$resultado = UF::Insert($objUF);
			else 
				return $response->withJson("El nro de unidad funcional ingresado no se encuentra disponible.", 409);
	
	
            if(is_numeric($resultado) == true)
                return $response->withJson(true, 200);
            else
                return $response->withJson(false, 400);
        }
	


    }
