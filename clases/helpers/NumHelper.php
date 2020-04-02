<?php

require_once "BaseHelper.php";

class NumHelper extends BaseHelper{

	public static function NumFormat($number, $cantDecimales = 2){
		$number = str_replace(",",".",$number);
		return number_format(floatval($number), $cantDecimales, ".", "");
	}	

	public static function IsNegative($number){
		$num = self::NumFormat($number);
		return $num < 0;
	}

}//class