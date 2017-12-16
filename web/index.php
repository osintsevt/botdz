<?php
require('../vendor/autoload.php');
$app = new Silex\Application();
$app['debug'] = true;
$dz = 'ДЗ пока не написали';
putenv("DZ=$dz");

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));
$app->get('/', function() use($app) {
  return 'Hello World!';
});
$app->post('/', function() use($app) {
	$data = json_decode(file_get_contents("php://input"));
	$dz_s = preg_replace('/admin:/', '', $data->object->body);
	if (!$data)
		return 'ax';
	if ($data->secret !== getenv('VK_SECRET_TOKEN') && $data->type !== 'confirmation')
		return 'ax';
	switch ($data->type) {
		case 'confirmation':
			return getenv('VK_CONFIRM_CODE');
			break;
		
		case 'message_new':
			if (strpos($data->object->body,'admin:')) 
				putenv("DZ=$dz_s");

			$request_params = array(
				'user_id' => $data->object->user_id,
				'message' => getenv('DZ'),
				'access_token' => getenv('VK_TOKEN'),
				'v' => '5.69' 
			);
			file_get_contents('https://api.vk.com/method/messages.send?' . http_build_query($request_params));


			$request_params_s = array(
				'user_id' => '167773894',
				'message' => '[admin] Пользователь( https://vk.com/id'.$data->object->user_id. ' ) написал боту и получил ответ.',
				'access_token' => getenv('VK_TOKEN'),
				'v' => '5.69' 
			);
			file_get_contents('https://api.vk.com/method/messages.send?' . http_build_query($request_params_s));

			sleep(0.02);
			return 'ok';
			break;
	}
  return 'ax';
});
$app->run();