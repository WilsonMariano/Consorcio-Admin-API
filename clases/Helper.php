<?php

class Helper{

	public static function NumFormat($number, $cantDecimales = 2){
		$number = str_replace(",",".",$number);
		return number_format(floatval($number), $cantDecimales, ".", "");
	}	

	public static function MyEcho($text){
		echo $text . "\xA";
	}

}//class