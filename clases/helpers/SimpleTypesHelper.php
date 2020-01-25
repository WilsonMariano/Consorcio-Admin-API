<?php

require_once "BaseHelper.php";

class SimpleTypesHelper extends BaseHelper{

	public static function NumFormat($number, $cantDecimales = 2){
		$number = str_replace(",",".",$number);
		return number_format(floatval($number), $cantDecimales, ".", "");
	}	

	/**
	 * Aplica espacios a la derecha de un texto. 
	 */
	public static function TxtPadRight($text, $spaces = 1){
		$padLength = strlen($text) + $spaces;
		return str_pad($text, $padLength);
	}


}//class