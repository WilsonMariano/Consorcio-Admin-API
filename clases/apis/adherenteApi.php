<?php

    include_once __DIR__ . '/../Adherentes.php';

    class AdherenteApi
    {
		
		public static function GetWithPaged($request, $response, $args){
			
			$datosRecibidos = $request->getQueryParams();
			$r = $datosRecibidos['rows'];
			$p = $datosRecibidos['page'];
			 	
			return $response->withJson(Adherentes::GetWithPaged($r,$p), 200);
        }

		


    }
