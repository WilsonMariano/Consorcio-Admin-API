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

	//Constructor customizado
	function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData["id"];
			$this->mes = $arrData["mes"];
			$this->anio = $arrData["anio"];
			$this->primerVencimiento = $arrData["primerVencimiento"];
			$this->segundoVencimiento = $arrData["segundoVencimiento"];
			$this->fechaEmision = $arrData["fechaEmision"];
			$this->tasaInteres = $arrData["tasaInteres"];
			$this->codEstado = $arrData["codEstado"] ?? null;
		}
    }

	//Configurar parámetros para las consultas
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

	public static function GetOneFromView($id){	
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$objEntidad = new LiquidacionesGlobales();
		
		$consulta = $objetoAccesoDato->RetornarConsulta("select * from vwLiquidacionesGlobales where id =:id");
		$consulta->bindValue(':id', $id , PDO::PARAM_STR);
		$consulta->execute();
		$objEntidad= $consulta->fetchObject();
		
		return $objEntidad;						
	}

	public static function CheckByPeriod($mes, $anio){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$objEntidad = new LiquidacionesGlobales();
		
		$consulta = $objetoAccesoDato->RetornarConsulta("select * from LiquidacionesGlobales where mes = :mes and anio = :anio");
		$consulta->bindValue(':mes', $mes , PDO::PARAM_STR);
		$consulta->bindValue(':anio', $anio , PDO::PARAM_STR);
		$consulta->execute();
			
		return $consulta->rowCount() > 0 ? true : false;						
	}
    
}//class