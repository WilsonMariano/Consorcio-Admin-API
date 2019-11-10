<?php   

include_once __DIR__ . '/../LiquidacionesGlobales.php';
include_once __DIR__ . '/../Diccionario.php';

class LiquidacionGlobalApi{
    
    private static function IsValid($liquidacionGbl){
        // Valido que el periodo ingresado no haya sido ingresado previamente. (Para evitar periodos duplicados)
        return !LiquidacionesGlobales::GetByPeriod($liquidacionGbl->mes, $liquidacionGbl->anio);
    }

    private static function IsHoliday($dia){
        //TODO :  validar feriados
        return false;
    }

    private static function GetExpirationDates($liquidacionGbl){
        // Si el mes es diciembre, calcular como enero. Sino, usar mes siguiente
        $mes = $liquidacionGbl->mes == 12 ? 1 : ($liquidacionGbl->mes + 1);
        $anio = $liquidacionGbl->anio;
        $CantDiasMes = days_in_month($mes, $anio); 

        for($i=$CantDiasMes; $i>0; $i--){
            $nroDia =  jddayofweek (gregoriantojd($mes, $i, $anio), 0);
            // Validar que el dia de la semana no sea sabádo(6) ni domingo (0)
            if($nroDia != 6 && $nroDia != 0){
                $segundoVenc = $anio . "-" . $mes . "-" . $i;
                if(!self::IsHoliday($segundoVenc)){  
                    $primerVenc = date('Y-m-d', strtotime('-7 days', strtotime($segundoVenc))); 
                    if(!self::IsHoliday($primerVenc))
                        break;   
                }
            }
        }
        $liquidacionGbl->primerVencimiento = $primerVenc;
        $liquidacionGbl->segundoVencimiento = $segundoVenc; 
    }

    public static function Insert($request, $response, $args){
        //Proceso los datos recibidos por body
        $apiParams = $request->getParsedBody();

        //Obtengo instancia de LiquidacionGlobal
        $liquidacionGbl = new LiquidacionesGlobales($apiParams);
        
        if(self::IsValid($liquidacionGbl)){
            $liquidacionGbl->tasaInteres = Diccionario::GetValue("TASA_INTERES");
            $liquidacionGbl->fechaEmision = date("Y-m-d");
            self::GetExpirationDates($liquidacionGbl);
            if(Funciones::InsertOne($liquidacionGbl))
                return $response->withJson(true, 200); 		
            else
                return $response->withJson(false, 500);
        }else{
            return $response->withJson("El período ingresado ya se encuentra registrado.", 400);				
        }
    }
  	 
}//class