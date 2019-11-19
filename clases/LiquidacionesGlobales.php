<?php

require_once "AccesoDatos.php";
require_once "_FuncionesEntidades.php";

class LiquidacionesGlobales{
	
	//Atributos
	public $id;
	public $mes;
	public $anio;
	public $primerVencimiento;
	public $segundoVencimiento;
	public $fechaEmision;
	public $tasaInteres;
	public $codEstado;

	// Constructor customizado
	function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData["id"] ?? null;
			$this->mes = $arrData["mes"];
			$this->anio = $arrData["anio"];
			$this->primerVencimiento = $arrData["primerVencimiento"] ?? null;
			$this->segundoVencimiento = $arrData["segundoVencimiento"] ?? null;
			$this->fechaEmision = $arrData["fechaEmision"] ?? null;
			$this->tasaInteres = $arrData["tasaInteres"] ?? null;
			$this->codEstado = $arrData["codEstado"] ?? "COD_ESTADO_1";
		}
    }

	/**
	 * Bindeo los parametros para la consulta SQL.
	 */
	public function BindQueryParams($consulta, $objEntidad, $includePK = true){
		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':mes'         		,$objEntidad->mes        		   ,\PDO::PARAM_INT);
		$consulta->bindValue(':anio'        		,$objEntidad->anio     			   ,\PDO::PARAM_INT);
		$consulta->bindValue(':primerVencimiento'   ,$objEntidad->primerVencimiento    ,\PDO::PARAM_STR);
		$consulta->bindValue(':segundoVencimiento'  ,$objEntidad->segundoVencimiento   ,\PDO::PARAM_STR);
		$consulta->bindValue(':fechaEmision'        ,$objEntidad->fechaEmision         ,\PDO::PARAM_STR);
		$consulta->bindValue(':tasaInteres'         ,$objEntidad->tasaInteres          ,\PDO::PARAM_STR);
		$consulta->bindValue(':codEstado'           ,$objEntidad->codEstado            ,\PDO::PARAM_STR);
	}

	/**
	 * (Bool)Valida si ya existe (o no) una LiquidacionGlobal, para un determinado período.
	 * Recibe por parámetros el mes y el año.
	 */
	public static function GetByPeriod($mes, $anio){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$objEntidad = new LiquidacionesGlobales();
		
		$consulta = $objetoAccesoDato->RetornarConsulta("select * from LiquidacionesGlobales where mes = :mes and anio = :anio");
		$consulta->bindValue(':mes', $mes , PDO::PARAM_STR);
		$consulta->bindValue(':anio', $anio , PDO::PARAM_STR);
		$consulta->execute();
			
		return $consulta->rowCount() > 0 ? true : false;						
	}
    
}//class