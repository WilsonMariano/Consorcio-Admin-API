<?php

require_once "AccesoDatos.php";

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


	//	Configurar parámetros para las consultas
	public function setQueryParams($consulta,$objEntidad, $includePK = true){

		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':mes'         		,$objEntidad->mes        		   ,\PDO::PARAM_INT);
		$consulta->bindValue(':anio'        		,$objEntidad->anio     			   ,\PDO::PARAM_INT);
		$consulta->bindValue(':primerVencimiento'   ,$objEntidad->primerVencimiento    ,\PDO::PARAM_STR);
		$consulta->bindValue(':segundoVencimiento'  ,$objEntidad->segundoVencimiento   ,\PDO::PARAM_STR);
		$consulta->bindValue(':fechaEmision'        ,$objEntidad->fechaEmision         ,\PDO::PARAM_STR);
		$consulta->bindValue(':tasaInteres'         ,$objEntidad->tasaInteres          ,\PDO::PARAM_STR);
		
		return $consulta;
	}


	public function AddNewExpense($liquidacionGlobal , $arrGastos){
		try {  
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$objetoAccesoDato->beginTransaction();
			$objetoAccesoDato->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
			//Guardo la liquidación global, si anduvo ok, continuo procesando los gastos.
			if(is_numeric(Insert($objetoAccesoDato,$liquidacionGlobal)))
			{
				//INSERTAR GASTOS

				//INSERTAR RELACIONESGASTOS

				$objetoAccesoDato->commit();
				return true;
			} else {
				$objetoAccesoDato->rollBack();
				return false;	
			}				
		} catch (Exception $e) {
			$objetoAccesoDato->rollBack();
			echo "Error: " . $e->getMessage();
		}
	}

	public static function Insert($objetoAccesoDato,$liquidacionGlobal){
		$consulta =$objetoAccesoDato->RetornarConsulta(
			"INSERT into liquidacionesGlobales values(:id, :mes, :anio, :primerVencimiento, :segundoVencimiento, :fechaEmision, :tasaInteres )");
		self::setQueryParams($consulta,$liquidacionGlobal);
		$consulta->execute();

		return $objetoAccesoDato->RetornarUltimoIdInsertado();
	}


}