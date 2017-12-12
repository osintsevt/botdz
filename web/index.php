<?php

require('../vendor/autoload.php');
require('functions.php');


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

	if (!$data)
		return 'ax';
	if ($data->secret !== getenv('VK_SECRET_TOKEN') && $data->type !== 'confirmation')
		return 'ax';

	switch ($data->type) {
		case 'confirmation':
			return getenv('VK_CONFIRM_CODE');
			break;
		
		case 'message_new':
			if ($data->object->body == '<admin>') {
				$body = 'Да я смотрю, ты админ... Чего прикажете, ваше благородие?';
			}else{
				$body = 'Предет!Я вшо еще малышь, но я вижю, што ты мне пишешь)))Хи-хи-хи';
			}
			message_to($data->object->user_id, $body);

			break;
	}


  return 'ax';
});

$app->run();
