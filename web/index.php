<?php

require('../vendor/autoload.php');

$app = new Silex\Application();
$app['debug'] = true;

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));



$app->get('/', function() use($app) {
  return 'Hello World!';
});

$app->post('/', function() use($app) {
	$data = json_decode(file_get_contents("php://input"));

	if (!$data) {
		return 'ax';}
	if ($data->secret !== getenv('VK_SECRET_TOKEN') && $data->type !== 'confirmation') {
		return 'ax'}

	switch ($data->type) {
		case 'confirmation':
			return getenv('VK_SECRET_TOKEN')
			break;
		
		case 'message_new':
			


			
			break;
	}


  return 'ax';
});

$app->run();
