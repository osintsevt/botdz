<?php
function message_to($someones_id, $text='Привет!Я ДзБОТ-1.<br>Что тебе нужно?')

	$request_params = array(
				'user_id' => $someones_id,
				'message' => $text,
				'access_token' => getenv('VK_TOKEN'),
				'v' => '5.69' 

			);
	file_get_contents('https://api.vk.com/method/messages.send?' . http_build_query($request_params));
	return 'ok';
}