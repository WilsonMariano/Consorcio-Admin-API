<?php

    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;

    require_once 'vendor/autoload.php';
    require_once 'clases/AccesoDatos.php';
	
	//Incluir todas las apis creadas
	foreach (glob("clases/apis/*.php") as $filename){
		require_once $filename;
	}
      
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
		
		// Métodos base de test
		$this->post('/post[/]', \GenericApi::class . ':Insert');
		$this->get('/all[/]', \GenericApi::class . ':GetAll');      
        $this->put('/put[/]', \GenericApi::class . ':UpdateOne');      
        $this->delete('/del/{id}', \GenericApi::class . ':DeleteOne');
		//
		
		$this->get('/filter[/]', \GenericApi::class . ':GetWithFilter');      
		$this->get('/paged[/]', \GenericApi::class . ':GetWithPaged');      
        $this->get('/one/{id}', \GenericApi::class . ':GetOne');       
    });



	// ********************  FUNCIONES AGRUPADAS POR ENTIDAD ***************************


    $app->group('/usuarios', function () {
		$this->get('/getAll[/]', \UsuarioApi::class . ':GetAll');      
		$this->post('/insert'   , \UsuarioApi::class . ':Insert');      
	});


	$app->group('/adherentes', function () {
		$this->post('/insert[/]', \AdherenteApi::class . ':Insert');      
	});

	$app->group('/uf', function () {
		$this->post('/insert[/]', \UFApi::class . ':Insert');      
	});

	$app->get('/ok[/]', function (Request $request, Response $response, $args) {
		$response->getBody()->write("OK", 200);
		return $response;
	});




	$app->run();