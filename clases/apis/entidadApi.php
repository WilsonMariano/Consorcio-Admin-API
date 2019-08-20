<?php

    include_once __DIR__ . '/../Entidad.php';

    class EntidadApi
    {

        public static function HolaMundo($request, $response, $args)
        {
            // $datosRecibidos = $request->getParsedBody();
            
            return $response->withJson('Hola Mundo', 200);
            
        } 

    }
