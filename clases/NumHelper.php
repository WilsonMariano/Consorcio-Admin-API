<?php

class NumHelper{

	public static function Format($number){
		$number = str_replace(",",".",$number);
		return number_format(floatval($number), 2, ".", "");
	}	

	public static function Multiply($val1, $val2){
		return Self::Format($val1) * Self::Format($val2);
	}

}//class