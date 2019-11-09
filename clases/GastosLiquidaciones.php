<?php

require_once "AccesoDatos.php";

class GastosLiquidaciones
{

	//	Atributos
	public $id;
	public $idLiquidacionGlobal;
	public $monto;
	public $codConceptoGasto;
	public $detalle;

	// Constructor customizado
	public function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData['id'] ?? null;
			$this->idLiquidacionGlobal = $arrData['idLiquidacionGlobal'];
			$this->monto = $arrData['monto'] ;
			$this->codConceptoGasto = $arrData['codConceptoGasto'] ?? null;
			$this->detalle = $arrData['detalle'] ?? null;
		}
	}

	//	Configurar parámetros para las consultas
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){
		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':idLiquidacionGlobal'	 ,$objEntidad->idLiquidacionGlobal  ,\PDO::PARAM_INT);
 		$consulta->bindValue(':monto'		         ,$objEntidad->monto                ,\PDO::PARAM_STR);
		$consulta->bindValue(':codConceptoGasto'	 ,$objEntidad->codConceptoGasto     ,\PDO::PARAM_STR);
		$consulta->bindValue(':detalle'	 			 ,$objEntidad->detalle     			,\PDO::PARAM_STR);
	}

	public static function GetByLiquidacionGlobal($idLiquidacionGlobal){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		
		$consulta = $objetoAccesoDato->RetornarConsulta("select * from GastosLiquidaciones where idLiquidacionGlobal = :idLiquidacionGlobal");
		$consulta->bindValue(':idLiquidacionGlobal', $idLiquidacionGlobal , PDO::PARAM_INT);
		$consulta->execute();
		$arrObjEntidad= $consulta->fetchAll(PDO::FETCH_ASSOC);	

		return $arrObjEntidad;					
	}

}//class