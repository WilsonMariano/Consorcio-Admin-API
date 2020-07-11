<?php

class LiquidacionApi{

    private const TASA_INTERES = "TASA_INTERES";

    /**
	 * Genera una liquidación nueva y la guarda en la BD
	 */
	public static function NewLiquidacion($uf){
		$liquidacion = new Liquidaciones();
		$liquidacion->idUF = $uf->id;
		$liquidacion->fechaEmision = date("Y-m-d");
		$liquidacion->tasaInteres = Diccionario::GetValue(self::TASA_INTERES);

		return Funciones::InsertAndSaveID($liquidacion);
	}

}





