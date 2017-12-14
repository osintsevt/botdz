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
	if (!$data)
		return 'ax';
	if ($data->secret !== getenv('VK_SECRET_TOKEN') && $data->type !== 'confirmation')
		return 'ax';
	switch ($data->type) {
		case 'confirmation':
			return getenv('VK_CONFIRM_CODE');
			break;
		
		case 'message_new':
			
			$request_params = array(
				'user_id' => $data->object->user_id,
				'message' => 'Извините! Но я еще не сертифицированный работник, да и вида на жительство не имею. А если миграционная служба пронюхает, что я без регистрации? Ну вы поняли... Должны дать документы в воскресенье, надеюсь так и будет, но не знаю<br><br>P.S Вот вам дз на завтра (только никому, контробанда в концеконцов):<br>Русский: 2 примера на каждый из четырех типов односоставных глагольных предложений, ОК, параграфы 22-27<br>Литература: басню Волк на псарне учить наизусть, песнь о Вещем Олеге<br>Биология:98 в р.т<br>История: знать всех правителей династии Романовых<br>Химия: готовимся к к/р<br>Тс... я вам ничего не говорил',
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