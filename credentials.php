<?php

// $environment = "prod";
$environment = "dev";

if($environment == "prod"){

    define("USERNAME",  "bbf8ba6b8fd24e");
    define("PASSWORD",  "db00fd24");
    define("DBNAME",    "heroku_97775c85b932729");
    define("HOST",      "us-cdbr-iron-east-02.cleardb.net");

}else{

    define("USERNAME",  "root");
    define("PASSWORD",  "");
    define("DBNAME",    "gestion_expensas");
    define("HOST",      "localhost");
}