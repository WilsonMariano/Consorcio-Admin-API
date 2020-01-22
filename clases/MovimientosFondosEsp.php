<?php

require_once "AccesoDatos.php";

class MovimientosFondosEsp
{
	// Atributos
	public $id;
	
	
	// Constructor customizado
	public function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData['id'] ?? null;
	
		} 
	}

	/**
	 * Bindeo los parametros para la consulta SQL.
	 */
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){

		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		// $consulta->bindValue(':idLiquidacion'          ,$objEntidad->idLiquidacion        ,\PDO::PARAM_INT);
	
	}

}//class