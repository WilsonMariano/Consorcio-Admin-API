<?php

require_once "AccesoDatos.php";
require_once "_FuncionesEntidades.php";

class LiquidacionesGlobales
{
	//	Atributos
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
			$this->id = $arrData["id"];
			$this->mes = $arrData["mes"];
			$this->anio = $arrData["anio"];
			$this->primerVencimiento = $arrData["primerVencimiento"];
			$this->segundoVencimiento = $arrData["segundoVencimiento"];
			$this->fechaEmision = $arrData["fechaEmision"];
			$this->tasaInteres = $arrData["tasaInteres"];
		}
    }
 
	// Insert privado. (uso interno)
	private static function Insert($liquidacionGlobal){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta(
			"INSERT into liquidacionesGlobales (mes, anio, primerVencimiento, segundoVencimiento, fechaEmision, tasaInteres)
			 values(:mes, :anio, :primerVencimiento, :segundoVencimiento, :fechaEmision, :tasaInteres )");
		self::setQueryParams($consulta,$liquidacionGlobal,false);
		$consulta->execute();

		return $objetoAccesoDato->RetornarUltimoIdInsertado();
	}

	//	Configurar parámetros para las consultas
	public function setQueryParams($consulta, $objEntidad, $includePK = true){
		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':mes'         		,$objEntidad->mes        		   ,\PDO::PARAM_INT);
		$consulta->bindValue(':anio'        		,$objEntidad->anio     			   ,\PDO::PARAM_INT);
		$consulta->bindValue(':primerVencimiento'   ,$objEntidad->primerVencimiento    ,\PDO::PARAM_STR);
		$consulta->bindValue(':segundoVencimiento'  ,$objEntidad->segundoVencimiento   ,\PDO::PARAM_STR);
		$consulta->bindValue(':fechaEmision'        ,$objEntidad->fechaEmision         ,\PDO::PARAM_STR);
		$consulta->bindValue(':tasaInteres'         ,$objEntidad->tasaInteres          ,\PDO::PARAM_STR);
	}

	public function AddNewExpense($liquidacionGlobal, $arrGastos, $arrRelaciones){
		try {  
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$objetoAccesoDato->beginTransaction();
			// $objetoAccesoDato->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
			//Guardo la liquidación global, si anduvo ok, continuo procesando los gastos.
			if(is_numeric(Funciones::Insert($liquidacionGlobal)))
			{
				// Guardo los gastos y sus relaciones
				foreach($arrGastos as $gasto){
					if(is_numeric(GastosLiquidaciones::Insert($gasto)))
						foreach($arrRelaciones as $relacion)	
							if($relacion->idGastosLiquidaciones == $gasto->id)
								RelacionesGastos::Insert($relacion);
				}
				$objetoAccesoDato->commit();
				return true;
			} else {
				$objetoAccesoDato->rollBack();
				return false;	
			}				
		} catch (Exception $e){
			$objetoAccesoDato->rollBack();
			echo "Error: " . $e->getMessage();
		}
	}

	public static function GetOneFromView($id){	
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$objEntidad = new ConceptosGastos();
		
		$consulta = $objetoAccesoDato->RetornarConsulta("select * from vwLiquidacionesGlobales where id =:id");
		$consulta->bindValue(':id', $id , PDO::PARAM_STR);
		$consulta->execute();
		$objEntidad= $consulta->fetchObject();
		
		return $objEntidad;						
	}//GetOne	
    
}