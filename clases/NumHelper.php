<?php

class NumHelper{

	public static function Format($number, $cantDecimales = 2){
		$number = str_replace(",",".",$number);
		return number_format(floatval($number), $cantDecimales, ".", "");
	}	

	// public static function Multiply($val1, $val2){
	// 	return Self::Format($val1) * Self::Format($val2);
	// }

}//class