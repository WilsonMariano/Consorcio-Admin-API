<?php

    include_once __DIR__ . '/../ConceptosGastos.php';
    

    class ConceptoGastoApi
    {
		
        public static function Insert($request, $response, $args){

            $datosRecibidos = $request->getParsedBody();

            $objConceptoGasto = new ConceptosGastos();
            $objConceptoGasto->codigo         = $datosRecibidos['codigo'];
            $objConceptoGasto->conceptoGasto  = $datosRecibidos['conceptoGasto'];
 
 
			//Valido que el código no esté duplicado antes de insertar
			if(!ConceptosGastos::IsDuplicated($objConceptoGasto))
				$resultado = ConceptosGastos::Insert($objConceptoGasto);
			else 
				return $response->withJson("El codigo de gasto ingresado no se encuentra disponible.", 409);
	
	
            if(is_numeric($resultado) == true)
                return $response->withJson(true, 200);
            else
                return $response->withJson(false, 400);
        }



		public static function GetOne ($request, $response, $args)
		{
         	$datosRecibidos = $request->getQueryParams();
 	
			$objEntidad = ConceptosGastos::GetOne($datosRecibidos["codigo"]);
			
			if($objEntidad)
				return $response->withJson($objEntidad, 200); 
			else
			   return $response->withJson(false, 400);  
			
		}


    }
