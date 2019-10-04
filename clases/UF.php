<?php

require_once "AccesoDatos.php";

class UF
{
	//	Atributos
	public $id;
	public $idManzana;
	public $idAdherente;
	public $nroEdificio;
	public $departamento;
	public $codSitLegal;
	public $coeficiente;
	public $codAlquila;
 
 	
	//	Configurar parámetros para las consultas
	public function setQueryParams($consulta,$objEntidad, $includePK = true){

		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':idManzana'    ,$objEntidad->idManzana     ,\PDO::PARAM_INT);
		$consulta->bindValue(':idAdherente'  ,$objEntidad->idAdherente   ,\PDO::PARAM_INT);
		$consulta->bindValue(':nroEdificio'  ,$objEntidad->nroEdificio   ,\PDO::PARAM_INT);
		$consulta->bindValue(':departamento' ,$objEntidad->departamento  ,\PDO::PARAM_STR);
		$consulta->bindValue(':codSitLegal'  ,$objEntidad->codSitLegal   ,\PDO::PARAM_STR);
		$consulta->bindValue(':coeficiente'  ,$objEntidad->coeficiente   ,\PDO::PARAM_INT);
		$consulta->bindValue(':codAlquila'   ,$objEntidad->codAlquila    ,\PDO::PARAM_STR);
	}


	public static function Insert($uf){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta(
			"INSERT into uf values(:id,:idManzana,:idAdherente,:nroEdificio,:departamento,:codSitLegal,:coeficiente,:codAlquila)");
		self::setQueryParams($consulta,$uf);
		$consulta->execute();

		return $objetoAccesoDato->RetornarUltimoIdInsertado();
	
	}

}//class