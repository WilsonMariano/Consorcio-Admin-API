<?php

require_once "AccesoDatos.php";

class CtasCtes
{
	//Atributos
	public $id;
	public $idUF;
	public $fecha;
	public $descripcion;
	public $monto;
	public $saldo;

	//Constructor customizado
	public function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData["id"] ?? null;
			$this->idUF = $arrData["idUF"];
			$this->fecha = $arrData["fecha"];
			$this->descripcion = $arrData["descripcion"] ?? null;
			$this->monto = $arrData["monto"];
			$this->saldo = $arrData["saldo"];
		}
    }

	/**
	 * Bindeo los parametros para la consulta SQL.
	 */
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){
		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':idUF'        ,$objEntidad->idUF         ,\PDO::PARAM_INT);
		$consulta->bindValue(':fecha'       ,$objEntidad->fecha        ,\PDO::PARAM_STR);
		$consulta->bindValue(':descripcion' ,$objEntidad->descripcion  ,\PDO::PARAM_STR);
		$consulta->bindValue(':monto'       ,$objEntidad->monto        ,\PDO::PARAM_STR);
		$consulta->bindValue(':saldo'       ,$objEntidad->saldo        ,\PDO::PARAM_STR);
	}

	/**
	 * Guarda un movimiento en CtasCtes y devuelve el id generado por la BD.
	 * Recibe por parámetro una instancia de la clase CtasCtes.
	 */
	public static function Insert($objEntidad){
 		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		 
		$consulta = $objetoAccesoDato->RetornarConsulta("insert into CtasCtes (idUF, fecha, descripcion, monto, saldo) 
			values (:idUF, :fecha, :descripcion, :monto, :saldo)");
		$objEntidad->BindQueryParams($consulta, $objEntidad, false);	
		$consulta->execute();

		return $objetoAccesoDato->RetornarUltimoIdInsertado();			
	}

	/**
	 * Devuelve el ultimo saldo calculado para una CtaCte.
	 * Recibe por parámetro el id de la unidad funcional
	 */
	public static function GetLastSaldo($idUF){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		 
		$consulta = $objetoAccesoDato->RetornarConsulta("select  saldo from CtasCtes where idUF = :idUF order by id desc limit 1");
		$consulta->bindValue(':idUF' , $idUF, \PDO::PARAM_INT);	
		$consulta->execute();

		return $consulta->fetch();
	}

}//class