<?php

require_once "AccesoDatos.php";
require_once "enums/EntityTypeEnum.php";

class RelacionesGastos{

	//Atributos
	public $id;
	public $idGastosLiquidaciones;
	public $entidad;
	public $idManzana;
	public $nroEntidad;

	//Constructor customizado
	public function __construct($arrData){
		$this->id = $arrData["id"] ?? null;
		$this->idGastosLiquidaciones = $arrData["idGastosLiquidaciones"] ?? null;
		$this->entidad = $arrData["entidad"] ?? null;
		$this->idManzana = $arrData["idManzana"] ?? null;
		$this->nroEntidad = $arrData["nroEntidad"] ?? null;
	}

	/**
	 * Bindeo los parametros para la consulta SQL.
	 */
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){
		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':idGastosLiquidaciones' ,$objEntidad->idGastosLiquidaciones ,\PDO::PARAM_INT);
		$consulta->bindValue(':entidad'   	          ,$objEntidad->entidad	              ,\PDO::PARAM_STR);
		$consulta->bindValue(':idManzana'	          ,$objEntidad->idManzana                ,\PDO::PARAM_INT);
		$consulta->bindValue(':nroEntidad'	          ,$objEntidad->nroEntidad                ,\PDO::PARAM_INT);
	}

	/**
	 * Elimino todas las relaciones pertenicientes a un mismo GastoLiquidacion.
	 * Recibe por parámetro un idGastoLiquidacion.
	 */
	public static function DeleteAll($idGastosLiquidaciones){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("delete from RelacionesGastos where idGastosLiquidaciones = :idGastosLiquidaciones");	
		$consulta->bindValue(':idGastosLiquidaciones',$idGastosLiquidaciones, PDO::PARAM_INT);		
		$consulta->execute();
		
		return $consulta->rowCount() > 0 ? true : false;
	}

	/**
	 * Devuelve un array de objetos RelacionesGastos relacionados a un GastoLiquidacion. 
	 * Recibe por parámetro un idGastosLiquidacion.
	 */
	public static function GetByIdGastoLiquidacion($idGastosLiquidaciones){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		
		$consulta = $objetoAccesoDato->RetornarConsulta("select * from RelacionesGastos where idGastosLiquidaciones = :idGastosLiquidaciones");
		$consulta->bindValue(':idGastosLiquidaciones', $idGastosLiquidaciones , PDO::PARAM_INT);
		$consulta->execute();
			
		$arrObjEntidad= $consulta->fetchAll(PDO::FETCH_ASSOC);	
		
		return $arrObjEntidad;	
	}

	/**
	 * Valida que la entidad relacionada (edificio, manzana o uf segun corresponda) existan en la bd.
	 */
	public static function IsValid($relacion){
		switch($relacion->entidad){
			case EntityTypeEnum::Manzana :
				return Manzanas::GetByNumero($relacion->nroEntidad);
				break;
			case EntityTypeEnum::Edificio :
				return Edificios::GetByManzanaAndNumero($relacion->idManzana, $relacion->nroEntidad);
				break;
			case EntityTypeEnum::UnidadFuncional :
				return UF::GetByManzanaAndNumero($relacion->idManzana, $relacion->nroEntidad);	
				break;
		}
	}

}//class
