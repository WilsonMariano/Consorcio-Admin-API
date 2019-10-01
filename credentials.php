<?php

$environment = "prod";
// $environment = "dev";

if($environment == "prod"){

    define("USERNAME",  "bc6fe4391dae63");
    define("PASSWORD",  "f8ee7a1e");
    define("DBNAME",    "heroku_d09773922a4b3a0");
    define("HOST",      "us-cdbr-iron-east-05.cleardb.net");

}else{

    define("USERNAME",  "root");
    define("PASSWORD",  "");
    define("DBNAME",    "gestion_expensas");
    define("HOST",      "localhost");
}