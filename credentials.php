<?php

// $environment = "prod";
$environment = "dev";

if($environment == "prod"){

    define("USERNAME",  "id10303673_pepusa");
    define("PASSWORD",  "quesito123");
    define("DBNAME",    "gestion_expensas");
    define("HOST",      "localhost");
}else{

    define("USERNAME",  "root");
    define("PASSWORD",  "");
    define("DBNAME",    "gestion_expensas");
    define("HOST",      "localhost");
}