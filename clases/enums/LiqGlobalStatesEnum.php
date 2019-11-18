<?php

require_once "BaseEnum.php";

/**
 * Enumerado con los estados posibles de una liquidación global.
 */
abstract class LiqGlobalStatesEnum extends BaseEnum {
    const Abierta = "COD_ESTADO_1";
    const Cerrada = "COD_ESTADO_2";
}