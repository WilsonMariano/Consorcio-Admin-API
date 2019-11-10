<?php

require_once "AccesoDatos.php";

class Usuarios{

	//	Atributos
	public $id;
	public $email;
	public $password;


	public function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData['id'] ?? null;
			$this->email = $arrData['email'];
			$this->password = $arrData['password'];
		}
	}

	//	Configurar parámetros para las consultas
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){

		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':email'	 ,$objEntidad->email    ,\PDO::PARAM_STR);
		$consulta->bindValue(':password' ,$objEntidad->password ,\PDO::PARAM_STR);
	}


	public static function Login($usuario) {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("select * from usuarios where email =:email AND password = :password");
		$consulta->bindValue(':email', $usuario->email, PDO::PARAM_STR);
		$consulta->bindValue(':password', $usuario->password, PDO::PARAM_STR);
		$consulta->execute();

		$usuarioBuscado = $consulta->fetchObject('usuarios');
		return $usuarioBuscado;
	}

}//class