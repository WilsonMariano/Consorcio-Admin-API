<?php


class LiquidacionesGlobales{
	
	//Atributos
	public $id;
	public $mes;
	public $anio;
	public $primerVencimiento;
	public $segundoVencimiento;
	public $codEstado;

	// Constructor customizado
	function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData["id"] ?? null;
			$this->mes = $arrData["mes"];
			$this->anio = $arrData["anio"];
			$this->primerVencimiento = $arrData["primerVencimiento"] ?? null;
			$this->segundoVencimiento = $arrData["segundoVencimiento"] ?? null;
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
		$consulta->bindValue(':codEstado'           ,$objEntidad->codEstado            ,\PDO::PARAM_STR);
	}

	/**
	 * (Bool)Valida si ya existe (o no) una LiquidacionGlobal, para un determinado período.
	 * Recibe por parámetros el mes y el año.
	 */
	public static function GetByPeriod($mes, $anio){
		try{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$objEntidad = new static();
			
			$consulta = $objetoAccesoDato->RetornarConsulta("select * from " . static::class . 
				" where mes = :mes and anio = :anio");
			$consulta->bindValue(':mes', $mes , PDO::PARAM_STR);
			$consulta->bindValue(':anio', $anio , PDO::PARAM_STR);
			$consulta->execute();
				
			return $consulta->rowCount() > 0 ? true : false;
		
		} catch(Exception $e){
			ErrorHelper::LogError(__FUNCTION__, $mes . "/" . $anio, $e);		 
			throw new ErrorException("No se pudo recuperar la liquidación del periodo  " . $mes . "/" . $anio);
		}

	}

}//class