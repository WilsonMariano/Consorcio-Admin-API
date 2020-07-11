<?php


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

	/**
	 * Bindeo los parametros para la consulta SQL.
	 */
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){
		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':idLiquidacionGlobal'	 ,$objEntidad->idLiquidacionGlobal  ,\PDO::PARAM_INT);
 		$consulta->bindValue(':monto'		         ,$objEntidad->monto                ,\PDO::PARAM_STR);
		$consulta->bindValue(':codConceptoGasto'	 ,$objEntidad->codConceptoGasto     ,\PDO::PARAM_STR);
		$consulta->bindValue(':detalle'	 			 ,$objEntidad->detalle     			,\PDO::PARAM_STR);
	}

	/**
	 * Devuelve un array de GastosLiquidaciones relacionadas a una LiquidacionGlobal. 
	 * Recibe por param un idLiquidacionGlobal.
	 */
	public static function GetByLiquidacionGlobal($idLiquidacionGlobal){
		try{

			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			
			$consulta = $objetoAccesoDato->RetornarConsulta("select * from " . static::class . 
				" where idLiquidacionGlobal = :idLiquidacionGlobal");
			$consulta->bindValue(':idLiquidacionGlobal', $idLiquidacionGlobal , PDO::PARAM_INT);
			$consulta->execute();
			$arrObjEntidad= PDOHelper::FetchAll($consulta);

			return $arrObjEntidad;					

		} catch(Exception $e){
			ErrorHelper::LogError(__FUNCTION__, $idLiquidacionGlobal, $e);		 
			throw new ErrorException("No se pudo recuperar la liquidación global " . $idLiquidacionGlobal);
		}
	}

}//class