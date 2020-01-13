<?php

require_once "BaseEnum.php";

abstract class RentalContractEnum extends BaseEnum {
    const Propietario = "COD_ALQ_1";
    const InquilinoSinContrato = "COD_ALQ_2";
    const InquilinoConContrato = "COD_ALQ_3";
    const TaxInqSinContrato = "TAX_INQ_S_CONTRATO";
    const TaxInqConContrato = "TAX_INQ_C_CONTRATO";
}