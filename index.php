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
		->withHeader('Access-Control-Allow-Origin', 'http://localhost:4200')
		->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
		->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
	});


	//Test method
	$app->get('/ok', function (Request $request, Response $response, $args) {
		$response->getBody()->write("OK", 200);
		return $response;
	});


	//Generic
    $app->group('/generic', function () {
		$this->post('/post[/]', \GenericApi::class . ':Insert');
		$this->get('/all[/]', \GenericApi::class . ':GetAll');      
        $this->put('/put[/]', \GenericApi::class . ':UpdateOne');      
        $this->delete('/del/{id}', \GenericApi::class . ':DeleteOne');

		$this->get('/paged[/]', \GenericApi::class . ':GetPagedWithOptionalFilter');         
        $this->get('/one/{id}', \GenericApi::class . ':GetOne');       
    });



	// *********************************************************************************
	// ********************  FUNCIONES AGRUPADAS POR ENTIDAD ***************************
	// *********************************************************************************


    $app->group('/usuarios', function () {
		$this->get('/all[/]', \UsuarioApi::class . ':GetAll');      
		$this->post('/insert[/]'   , \UsuarioApi::class . ':Insert');      
	});

	$app->group('/adherentes', function () {
		$this->post('/insert[/]', \AdherenteApi::class . ':Insert');      
	});

	$app->group('/uf', function () {
		$this->post('/insert[/]',  \UFApi::class . ':Insert');      
	});
	
	$app->group('/concepto-gasto', function () {
		$this->get('/one[/]'     , \ConceptoGastoApi::class . ':GetOne'); 
		$this->post('/insert[/]' , \ConceptoGastoApi::class . ':Insert');      
	});

	$app->group('/diccionario', function () {
		$this->get('/all[/]',    \DiccionarioApi::class . ':GetAll');          
		$this->get('/one[/]',    \DiccionarioApi::class . ':GetOne'); 
	});

	$app->group('/manzana', function () {
		$this->get('/getCoef[/]', \ManzanaApi::class . ':GetCoeficientes');          
	});

	$app->group('/liquidaciongbl', function () {
		$this->get('/getOneFromView/{id}', \LiquidacionGlobalApi::class . ':GetOneFromView');          
		$this->post('/insert[/]', \LiquidacionGlobalApi::class . ':Insert');          
	});

	$app->run();