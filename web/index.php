<?php

require('../vendor/autoload.php');
//require('functions.php');


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
	$id = $data->object->user_id;
	$text = 'Some text'

	if (!$data)
		return 'ax';
	if ($data->secret !== getenv('VK_SECRET_TOKEN') && $data->type !== 'confirmation')
		return 'ax';

	switch ($data->type) {
		case 'confirmation':
			return getenv('VK_CONFIRM_CODE');
			break;
		
		case 'message_new':
			/*if ($data->object->body == '<admin>') {
				message_to($id,'Да я смотрю, ты админ... Чего прикажете, ваше благородие?');
				return 'ok';
				break;
			}else{
				message_to($id,'Предет!Я вшо еще малышь, но я вижю, што ты мне пишешь)))Хи-хи-хи');
				return 'ok';
				break;
			}
			message_to($id, 'Тест');*/

			$request_params = array(
				'user_id' => $id,
				'message' => $text,
				'access_token' => getenv('VK_TOKEN'),
				'v' => '5.69' 

			);
			file_get_contents('https://api.vk.com/method/messages.send?' . http_build_query($request_params));
			return 'ok';
			break;
	}


  return 'ax';
});

$app->run();
