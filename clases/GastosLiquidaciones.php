<?php

require_once "AccesoDatos.php";

class GastosLiquidaciones
{

	//	Atributos
	public $id;
	public $idLiquidacionGlobal;
	public $monto;
	public $codConceptoGasto;
	

	//	Configurar parámetros para las consultas
	public function setQueryParams($consulta,$objEntidad, $includePK = true){

		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':idLiquidacionGlobal'	 ,$objEntidad->idLiquidacionGlobal  ,\PDO::PARAM_INT);
 		$consulta->bindValue(':monto'		         ,$objEntidad->monto                ,\PDO::PARAM_STR);
		$consulta->bindValue(':codConceptoGasto'	 ,$objEntidad->codConceptoGasto     ,\PDO::PARAM_STR);
		
		return $consulta;
	}

	public static function GetInstanceFromArray($arrData){
		$gasto = new GastosLiquidaciones();
		$gasto->id = $arrData['id'];
		$gasto->idLiquidacionGlobal = $arrData['idLiquidacionGlobal'];
		$gasto->monto = $arrData['monto'];
		$gasto->codConceptoGasto = $arrData['codConceptoGasto'];
		return $gasto;
	}


}//class