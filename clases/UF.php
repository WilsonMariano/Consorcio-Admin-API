<?php

require_once "AccesoDatos.php";

class UF{
	
	//	Atributos
	public $id;
	public $idManzana;
	public $idAdherente;
	public $nroEdificio;
	public $departamento;
	public $codSitLegal;
	public $coeficiente;
	public $codAlquila;

	public function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData['id'] ?? null;
			$this->idManzana = $arrData['idManzana'];
			$this->idAdherente = $arrData['idAdherente'];
			$this->nroEdificio = $arrData['nroEdificio'] ?? null;
			$this->departamento = $arrData['departamento'] ?? null;
			$this->codSitLegal = $arrData['codSitLegal'];
			$this->coeficiente = $arrData['coeficiente'];
			$this->codAlquila = $arrData['codAlquila'];
		}
	}

	//	Configurar parámetros para las consultas
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){

		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':idManzana'    ,$objEntidad->idManzana     ,\PDO::PARAM_INT);
		$consulta->bindValue(':idAdherente'  ,$objEntidad->idAdherente   ,\PDO::PARAM_INT);
		$consulta->bindValue(':nroEdificio'  ,$objEntidad->nroEdificio   ,\PDO::PARAM_INT);
		$consulta->bindValue(':departamento' ,$objEntidad->departamento  ,\PDO::PARAM_STR);
		$consulta->bindValue(':codSitLegal'  ,$objEntidad->codSitLegal   ,\PDO::PARAM_STR);
		$consulta->bindValue(':coeficiente'  ,$objEntidad->coeficiente   ,\PDO::PARAM_STR);
		$consulta->bindValue(':codAlquila'   ,$objEntidad->codAlquila    ,\PDO::PARAM_STR);
	}

}//class