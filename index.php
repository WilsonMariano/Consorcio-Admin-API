<?php

    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;

    require_once 'composer/vendor/autoload.php';
    require_once 'clases/AccesoDatos.php';
    require_once 'clases/apis/genericApi.php';
	require_once 'clases/apis/usuarioApi.php';
    
   
    $config['displayErrorDetails'] = true;
    $config['addContentLengthHeader'] = false;

    $app = new \Slim\App(["settings" => $config]);


    $app->add(function ($req, $res, $next){
		$response = $next($req, $res);
		return $response
			->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
	});



    $app->group('/generic', function () {
        $this->get('/all[/]', \GenericApi::class . ':GetAll');      
        $this->put('/put[/]', \GenericApi::class . ':UpdateOne');
        $this->post('/post[/]', \GenericApi::class . ':Insert');
        $this->delete('/del[/]', \GenericApi::class . ':DeleteOne');
    
        //https://github.com/pablo86v/miApiRestGenerica/blob/master/README.md
        //http://localhost/consorcioAdminAPI/index.php/generic/all?t=usuarios
    });


    $app->group('/usuario', function () {
		$this->get('/getAll[/]', \UsuarioApi::class . ':GetAll');      
		$this->post('/insert'   , \UsuarioApi::class . ':Insert');      
	});

	$app->run();