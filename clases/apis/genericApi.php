<?php

    include_once __DIR__ . '/../_FuncionesEntidades.php';
   

    class GenericApi
    {

        public static function GetAll($request, $response, $args)
        {
           	//Traigo  todos los items
			$datosRecibidos = $request->getQueryParams();
			$listado=\Funciones::GetAll($datosRecibidos["t"]);
    		return $response->write(json_encode($listado));           
        } 

        public static function UpdateOne($request, $response, $args)
        {
            //Recibo los datos y los asigno a un nuevo usuario
            $datosRecibidos = $request->getQueryParams();
            return $response->write(json_encode(Funciones::UpdateOne($datosRecibidos)));
        }

        public static function DeleteOne($request, $response, $args)
        {
             $datosRecibidos = $request->getQueryParams();
             $id = $datosRecibidos['id'];
       

            //Busco el Persona mediante el id
            $objEntidad = Funciones::GetOne($id,$datosRecibidos['t']); 
            // //Si no se encontró grabo un mensaje
            if($objEntidad == null)
            {
                $response->write("Registro no encontrado");
            }else{
            //Sino borro el registro y muestro los datos 
                Funciones::DeleteOne($id,$datosRecibidos['t']);
                $response->write(json_encode($objEntidad));
            }
            return $response;
        }

        public static function Insert($request, $response, $args)
        {
            //Datos recibidos por QueryString
            $datosRecibidosQS = $request->getQueryParams();
                    
            //Datos recibidos por body
            $datosRecibidosBody = $request->getParsedBody();	

            return $response->write(json_encode(Funciones::InsertOne($datosRecibidosQS,$datosRecibidosBody)));
        }
    


    }
