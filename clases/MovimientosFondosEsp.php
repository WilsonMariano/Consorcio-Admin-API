<?php


class MovimientosFondosEsp
{
	// Atributos
	public $id;
	public $idManzana;
	public $monto;
	public $descripcion;
	public $saldo;
	public $tipoLiquidacion;
	
	// Constructor customizado
	public function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData['id'] ?? null;
			$this->idManzana = $arrData['idManzana'];
			$this->monto = $arrData['monto'];
			$this->descripcion = $arrData['descripcion'];
			$this->saldo = $arrData['saldo'];
			$this->tipoLiquidacion = $arrData['tipoLiquidacion'];
		} 
	}

	/**
	 * Bindeo los parametros para la consulta SQL.
	 */
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){

		if($includePK == true)
			$consulta->bindValue(':id',  $objEntidad->id,  \PDO::PARAM_INT);
		
		$consulta->bindValue(':idManzana',        $objEntidad->idManzana,        \PDO::PARAM_INT);
		$consulta->bindValue(':monto',            $objEntidad->monto,            \PDO::PARAM_INT);
		$consulta->bindValue(':descripcion',      $objEntidad->descripcion,      \PDO::PARAM_STR);
		$consulta->bindValue(':saldo',            $objEntidad->saldo,            \PDO::PARAM_INT);
		$consulta->bindValue(':tipoLiquidacion',  $objEntidad->tipoLiquidacion,  \PDO::PARAM_STR);
	}

		/**
	 * Devuelve el ultimo saldo calculado para un fondo especial.
	 * Recibe por parámetro el id de la manzana
	 */
	public static function GetLastSaldo($idManzana){
		try{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			
			$consulta = $objetoAccesoDato->RetornarConsulta("select saldo from " . static::class . 
				" where idManzana = :idManzana order by id desc limit 1");
			$consulta->bindValue(':idManzana' , $idManzana, \PDO::PARAM_INT);	
			$consulta->execute();

			return PDOHelper::FetchObject($consulta)->saldo ?? 0;

		} catch(Exception $e){
			ErrorHelper::LogError(__FUNCTION__, $idManzana, $e);		 
			throw new ErrorException("No se pudo recuperar el ultimo saldo de la manzana de id" . $idManzana);
		}
	}

	public static function SetMovimientoFondoEsp($idManzana, $montoGasto){
		try{
			$movFondos = new static();
			$movFondos->idManzana = $idManzana;
			$movFondos->monto = SimpleTypesHelper::NumFormat($montoGasto);
			$movFondos->descripcion = "SE IMPUTA GASTO CONTRA FONDO ESPECIAL";
			$lastSaldo = SimpleTypesHelper::NumFormat(MovimientosFondosEsp::GetLastSaldo($idManzana));
			$movFondos->saldo = $lastSaldo - SimpleTypesHelper::NumFormat($montoGasto);
			$movFondos->tipoLiquidacion = LiquidacionTypeEnum::FondoReserva;
			$newIdMovFondosEsp = Funciones::InsertOne($movFondos);

			return $newIdMovFondosEsp;

		} catch(Exception $e){
			ErrorHelper::LogError(__FUNCTION__, $relacionGasto . "- $" . $montoGasto, $e);		 
			throw new ErrorException("No se pudo generar el movimiento de fondo especial para la manzana de id" . $idManzana);
		}
    }

}//class