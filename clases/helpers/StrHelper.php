<?php

require_once "BaseHelper.php";

class StrHelper extends BaseHelper{

    /**
	 * Aplica espacios a la derecha de un texto. 
	 */
	public static function TxtPadRight($text, $spaces = 1){
		$padLength = strlen($text) + $spaces;
		return str_pad($text, $padLength);
	}

}//class