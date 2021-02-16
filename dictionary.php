<?php
define('NOT_INTERESTED', 'not_interested');
define('NOT_RESPONDING', 'not_responding');
define('CREATED', 'created');
define('RECALLING', 'recalling');
define('ONE_CONNECTION', 'one_connection');
define('SUCCESS_PROCESSED', 'success_processed');
define('GOT_BY_OPERATOR', 'got_by_operator');
define('CALLING_TO_OPERATOR', 'calling_to_operator');
define('REFUSED_BY_OPERATOR', 'refused_by_operator');
define('CALL_CREATED', 'call_created');
define('COMMENT_BY_OPERATOR', 'comment_by_operator');

define('OPERATOR_BLACKLISTED', 'В черный список');


define('EVENT_MESSAGES', [
	NOT_INTERESTED => 'Нет интереса',
	NOT_RESPONDING => 'Не отвечает',
	CREATED => 'Лид создан',
	RECALLING => 'Перезвонить позже',
	SUCCESS_PROCESSED => 'Успешная обработка',
	GOT_BY_OPERATOR => 'Оператор взял лида',
	CALLING_TO_OPERATOR => 'Звонок лиду',
	REFUSED_BY_OPERATOR => 'Оператор передумал',
	CALL_CREATED => 'Звонок',
	COMMENT_BY_OPERATOR => 'Комментарий от оператора'
]);


define('CREATED_PRIORITY', 200);
define('RECALLING_PRIORITY', 100);
define('NOT_RESPONDING_PRIORITY', 500);

define('MESSAGE_USE_BUTTONS', "Брат, используй кнопки для сообщений");
define('MESSAGE_SCENARIO_WRITE_COMMENT', 'Введите комментарий');


define('NULL_BUTTONS', [['.']]);


define('OPERATOR_SET_LEAD', 'Готов');
define('OPERATOR_DROP_PROCESSING', 'Нет, позже');
define('OPERATOR_CALL_TO_LEAD', 'Звонить');
define('OPERATOR_REFUSE_LEAD', 'Нет, позже');
define('OPERATOR_FINISHED_CALL', 'Звонок завершен');

define('OPERATOR_NOT_RESPONDING', 'Не взял трубку');
define('OPERATOR_CALL_LATER', 'Звонить позже');
define('OPERATOR_SUCCESS_ONE_COMPLEX', 'Соеденил с 1 ЖК');
define('OPERATOR_NOT_INTERESTED', 'Нет интереса');
define('OPERATOR_SUCCESS', 'Успешно');
define('OPERATOR_BAD_COMPLEX', 'ЖК не подошёл');
define('OPERATOR_PUT_0_BUTTON', 'Готово');

define('BUTTON_NO', 'Нет');

define('OPERATOR_PUT_0', [
	"STATUS" => "put_0_but",
	"MESSAGE" => "Теперь нажмите 0 на звуковой клавиатуре телефона",
	"BUTTONS" => [[OPERATOR_PUT_0_BUTTON]]
]);

define('OPERATOR_STATES_ROOMS', [
	'STATUS' => "state_rooms",
	'MESSAGE' => "Введите желаемую комнатность"
]);

define('OPERATOR_STATES_PRICE1', [
	'STATUS' => "state_price_min",
	'MESSAGE' => "Введите желаемую минимальную цену в млн"
]);

define('OPERATOR_STATES_PRICE2', [
	'STATUS' => "state_price_max",
	'MESSAGE' => "Введите желаемую максимальную цену в млн"
]);

define('OPERATOR_STATES_SLEEP', [
	'STATUS' => "sleep",
	'MESSAGE' => "Готовы обработать новый лид?",
	'BUTTONS' => [[OPERATOR_SET_LEAD, OPERATOR_DROP_PROCESSING]]
]);

define('OPERATOR_STATES_LEAD_DATA', [
	'STATUS' => "lead_data",
	'MESSAGE' => "Звоним лиду...",
	'BUTTONS' => [[OPERATOR_BAD_COMPLEX, OPERATOR_FINISHED_CALL]]
]);

define('OPERATOR_STATES_AWAKED', [
	'STATUS' => "awaked",
	'MESSAGE' => "Твой лид:",
	'MESSAGE_NO_LEADS' => "Извини, сейчас лидов нет, напишем тебе позже",
	'MESSAGE_REFUSE' => "Ок, пиши",
	'BUTTONS' => [[OPERATOR_CALL_TO_LEAD, OPERATOR_REFUSE_LEAD]]
]);

define('OPERATOR_STATES_HAS_LEAD', [
	'STATUS' => "has_lead",
	'MESSAGE' => "Скажи, когда закончишь разговор",
	'MESSAGE_REFUSE' => "Ок, пиши",
	'BUTTONS' => [[OPERATOR_FINISHED_CALL]]
]);

define('OPERATOR_STATES_LEAD_CALLED', [
	'STATUS' => "lead_called",
	'MESSAGE' => "Как прошел разговор?",
	'BUTTONS' => [[OPERATOR_NOT_RESPONDING, OPERATOR_CALL_LATER],[OPERATOR_NOT_INTERESTED, OPERATOR_SUCCESS],[OPERATOR_SUCCESS_ONE_COMPLEX],[OPERATOR_BLACKLISTED]]
]);

define('OPERATOR_ANSWERING', [
	'STATUS' => "answering",
	'MESSAGE' => 'Окей, есть дополнительные комментарии к лиду? Введи или нажми нет.',
	'MESSAGE_CHOOSE_TIME' => 'Введи время повторного звонка в формате 2018-01-12 15:17'
]);

define('OPERATOR_COMMENTING', [
	'STATUS' => "commenting",
	'MESSAGE' => 'Спасибо! Погнали дальше',
	'BUTTONS' => [[BUTTON_NO]]
]);

define('SCENARIO_ANSWERING', [
	'STATUS' => "scenario_answering"
]);

define('SCENARIO_ANSWERING_COMMENT', [
	'STATUS' => "scenario_answering_comment"
]);

define('OPERATOR_CHOSING_TIME_STATE', [
	'STATUS' => "chooseTime",
	'MESSAGE_WRONG' => 'Вводи именно в формате 2018-01-12 15:17 (Желательно не больше 2099 года)'
]);