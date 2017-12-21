<?php
require('../vendor/autoload.php');
$app = new Silex\Application();
$app['debug'] = true;

function message_to($someones_id, $text)
{
	$request_params = array(
				'user_id' => $someones_id,
				'message' => $text,
				'access_token' => getenv('VK_TOKEN'),
				'v' => '5.69'
				);
	file_get_contents('https://api.vk.com/method/messages.send?' . http_build_query($request_params));
}

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

		$a = false;

			if (strpos($data->object->body,'admin@dz.bot')!==false) {
				if (strpos($data->object->body, '-dz') !==false) {
					$file = fopen("dz.txt", 'w');
					$x = preg_replace('/admin@dz.bot/', '', $data->object->body);
					$x = preg_replace('/-dz/', '', $x);
					fwrite($file, $x);
					fclose($file);
					message_to($data->object->user_id, 'ДЗ обновлено');
					$a = true;
				}

				/*if (strpos($data->object->body, '-m')) {
					
					$request_params = array(
					'group_id' => 'dzpredmet',
					'access_token' => getenv('VK_TOKEN'),
					'v' => '5.69'
					);
					$response = json_decode(file_get_contents('https://api.vk.com/method/groups.getMembers?' . http_build_query($request_params)), true);

					for ($i=0; $i < count($response); $i++) { 
						message_to($data->object->user_id, $response['items'][$i]);
					}

					
					$a = true;

				}
			*/	
			}

			if (preg_match('/[Дд][Зз]/', $data->object->body)&&(!$a)) {
				message_to($data->object->user_id, file_get_contents('dz.txt'));
				$a = true;
			}

			if (preg_match('/[Пп]ривет|[Шш]алом|[Хх]aй|[Хх]ей|[хХ]еллоу|[Зз]дарова|[Зз]дравствуй|[Дд]обрый день|[Дд]обрый вечер/', $data->object->body)&&(!$a)) {
				message_to($data->object->user_id, 'Привет, друг. Что нужно?');
				$a = true;
			}

			if (preg_match('/[вВ]ониш/', $data->object->body)&&(!$a)) {
				message_to($data->object->user_id, 'Пес');
				$a = true;
			}
			
			if (preg_match('/[Сс]пасибо/', $data->object->body)&&(!$a)) {
				message_to($data->object->user_id, 'Пожалуйста');
				$a = true;
			}
	
			if (!$a) {
				message_to($data->object->user_id, 'К такому меня жизнь не готовила');
			}
			//message_to($data->object->user_id, 'admin: Пользователь( https://vk.com/id'.$data->object->user_id. ' ) написал боту и получил ответ');
			sleep(0.02);
			return 'ok';
			break;
	}
  return 'ax';
});
$app->run();