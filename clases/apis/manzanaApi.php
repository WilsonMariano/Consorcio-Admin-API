<?php   

include_once __DIR__ . '/../Manzanas.php';

class ManzanaApi{

    public static function GetCoeficientes($request , $response, $args){
        $apiParams = $request->getParsedBody();
        
        $data = Manzanas::GetCoeficientes($apiParams['manzanas']);

		if($data)
				return $response->withJson($data, 200); 		
			else   
				return $response->withJson(false, 400);
    }

}//class