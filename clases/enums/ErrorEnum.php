<?php

require_once "BaseEnum.php";

abstract class ErrorEnum extends BaseEnum {
    const GenericInsert = "Funciones::InsertOne";
    const GenericUpdate = "Funciones::UpdateOne";
    const GenericDelete = "Funciones::DeleteOne";
    const GenericGet    = "Funciones::GetAll";
    const GenericGetOne = "Funiones::GetOne";
}
