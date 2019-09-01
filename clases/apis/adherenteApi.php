<?php

    include_once __DIR__ . '/../Adherentes.php';

    class UsuarioApi
    {
		
		public static function GetWithPaged($request, $response, $args){
            return $response->withJson(Ad::GetWithPaged(), 200);
        }

		


    }
