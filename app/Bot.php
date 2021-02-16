<?php

namespace App;

use TelegramBot\Api\Types\ReplyKeyboardMarkup as Keyboard;
use Illuminate\Support\Facades\Log;
use App\LeadTask;
use App\BlacklistPhone;

class Bot
{
	
	private $operator;

	private $message;

	private $api;


	public function __construct($params, $api)
	{
		Log::debug("Creating Bot Instance");

		$chat_id = $params['chat_id'];
		$username = $params['username'];

		$this->setOperator($chat_id, $username);
		$this->message = $params['message'];
		$this->api = $api;
	}

	public function process()
	{
		Log::debug("Process Telegram Message: " 
			. $this->operator->state 
			. "(".$this->message.")"
			. " operator_id:" . $this->operator->id
			. " lead_id:" . $this->operator->lead()

		);
		$this->{$this->operator->state . "State"}();
	}

	public function sleepState()
	{
		$this->sendMessage(OPERATOR_STATES_SLEEP["MESSAGE"], OPERATOR_STATES_SLEEP["BUTTONS"]);
		$this->setState(OPERATOR_STATES_AWAKED);
	}

	public function awakedState()
	{
		if ($this->message == OPERATOR_SET_LEAD) {
			if (!$this->setLead()) {
				$this->sendMessage(OPERATOR_STATES_AWAKED["MESSAGE_NO_LEADS"], NULL_BUTTONS);
				$this->setState(OPERATOR_STATES_SLEEP);
				$this->operator->resetOperatorState(OPERATOR_STATES_SLEEP);
			}
		} elseif ($this->message == OPERATOR_REFUSE_LEAD) {
			$this->sendMessage(OPERATOR_STATES_AWAKED["MESSAGE_REFUSE"], NULL_BUTTONS);
			$this->setState(OPERATOR_STATES_SLEEP);
				$this->operator->resetOperatorState(OPERATOR_STATES_SLEEP);
		} else {
			$this->sendMessage(MESSAGE_USE_BUTTONS);
		}
	}

	public function has_leadState()
	{
		if ($this->message == OPERATOR_CALL_TO_LEAD) {
			$this->createCall();
			$this->operator->lead()->process(CALLING_TO_OPERATOR, null, [
				'operator_id' => $this->operator->id
			]);

			$scenario = $this->operator->lead()->getScenario($this->operator);


			if ($scenario->need_lead_data) {

				$this->sendMessage(OPERATOR_STATES_LEAD_DATA["MESSAGE"], OPERATOR_STATES_LEAD_DATA["BUTTONS"]);
				$this->setState(OPERATOR_STATES_LEAD_DATA);

				return;
			}

			$this->sendMessage(OPERATOR_STATES_HAS_LEAD["MESSAGE"], OPERATOR_STATES_HAS_LEAD["BUTTONS"]);
			$this->setState(OPERATOR_STATES_LEAD_CALLED);

		} elseif ($this->message == OPERATOR_REFUSE_LEAD) {
			$this->operator->lead()->process(REFUSED_BY_OPERATOR, null, [
				'operator_id' => $this->operator->id
			]);

			$this->sendMessage(OPERATOR_STATES_HAS_LEAD["MESSAGE_REFUSE"], NULL_BUTTONS);
			$this->setState(OPERATOR_STATES_SLEEP);
				$this->operator->resetOperatorState(OPERATOR_STATES_SLEEP);
		} else {
			$this->sendMessage(MESSAGE_USE_BUTTONS);
		}
	}

	public function lead_dataState($value='')
	{
		$rooms = $this->message;
		
		
		if (strpos($this->message, "/start") !== false) {

			Log::debug($this->message);
			$ids = str_replace("/start ", "", $this->message);
			Log::debug($ids);
			$ids = preg_replace("/aa$/", "", $ids);
			Log::debug($ids);
			$ids = explode("aa", $ids);
			Log::debug($ids);

			$lead = $this->operator->lead();
			$lead->ids = implode(",", $ids);
			$lead->save();

			
			$this->sendMessage($lead->getMultiMessage(), NULL_BUTTONS);
			$this->sendMessage(OPERATOR_STATES_HAS_LEAD["MESSAGE"], OPERATOR_STATES_HAS_LEAD["BUTTONS"]);
			$this->setState(OPERATOR_STATES_LEAD_CALLED);

		} elseif ($this->message == OPERATOR_FINISHED_CALL) {
			$this->sendMessage(OPERATOR_STATES_LEAD_CALLED["MESSAGE"], OPERATOR_STATES_LEAD_CALLED["BUTTONS"]);
			$this->setState(OPERATOR_ANSWERING);
		} elseif ($this->message == OPERATOR_BAD_COMPLEX) {
			$this->sendMessage(OPERATOR_STATES_ROOMS["MESSAGE"], NULL_BUTTONS);
			$this->setState(OPERATOR_STATES_ROOMS);
		} else {
			$this->sendMessage(OPERATOR_STATES_LEAD_DATA["MESSAGE"], OPERATOR_STATES_LEAD_DATA["BUTTONS"]);
			$this->setState(OPERATOR_STATES_LEAD_DATA);

			return;
		}
	}

	public function state_roomsState()
	{
		$rooms = $this->message;
		$rooms = (int)$rooms;

		if ($this->message == OPERATOR_FINISHED_CALL) {
			$this->sendMessage(OPERATOR_STATES_LEAD_CALLED["MESSAGE"], OPERATOR_STATES_LEAD_CALLED["BUTTONS"]);
			$this->setState(OPERATOR_ANSWERING);

			return;
		}

		if (!$rooms) {
			$this->sendMessage(OPERATOR_STATES_ROOMS["MESSAGE"], NULL_BUTTONS);
			$this->setState(OPERATOR_STATES_ROOMS);

			return;
		}

		$lead = $this->operator->lead();
		$lead->rooms = $rooms;
		$lead->save();

		$this->sendMessage(OPERATOR_STATES_PRICE1["MESSAGE"], NULL_BUTTONS);
		$this->setState(OPERATOR_STATES_PRICE1);
	}

	public function state_price_minState()
	{
		$price = $this->message;
		$price = (int)$price;

		if ($this->message == OPERATOR_FINISHED_CALL) {
			$this->sendMessage(OPERATOR_STATES_LEAD_CALLED["MESSAGE"], OPERATOR_STATES_LEAD_CALLED["BUTTONS"]);
			$this->setState(OPERATOR_ANSWERING);

			return;
		}

		if (!$price) {
			$this->sendMessage(OPERATOR_STATES_PRICE1["MESSAGE"], NULL_BUTTONS);
			$this->setState(OPERATOR_STATES_PRICE1);

			return;
		}

		$lead = $this->operator->lead();
		$lead->price_from = $price;
		$lead->save();

		$this->sendMessage(OPERATOR_STATES_PRICE2["MESSAGE"], NULL_BUTTONS);
		$this->setState(OPERATOR_STATES_PRICE2);
	}

	public function state_price_maxState()
	{
		$price = $this->message;
		$price = (int)$price;

		if ($this->message == OPERATOR_FINISHED_CALL) {
			$this->sendMessage(OPERATOR_STATES_LEAD_CALLED["MESSAGE"], OPERATOR_STATES_LEAD_CALLED["BUTTONS"]);
			$this->setState(OPERATOR_ANSWERING);

			return;
		}

		if (!$price) {
			$this->sendMessage(OPERATOR_STATES_PRICE2["MESSAGE"], NULL_BUTTONS);
			$this->setState(OPERATOR_STATES_PRICE2);

			return;
		}

		$lead = $this->operator->lead();
		$lead->price_to = $price;
		$lead->save();

		$lead->setComplexes();

		$this->sendMessage(OPERATOR_PUT_0["MESSAGE"], OPERATOR_PUT_0["BUTTONS"]);
		$this->setState(OPERATOR_PUT_0);
	}

	public function put_0_butState()
	{
		$lead = $this->operator->lead();

		if ($this->message == OPERATOR_FINISHED_CALL) {
			$this->sendMessage(OPERATOR_STATES_LEAD_CALLED["MESSAGE"], OPERATOR_STATES_LEAD_CALLED["BUTTONS"]);
			$this->setState(OPERATOR_ANSWERING);
		} elseif ($this->message == OPERATOR_PUT_0_BUTTON) {
			$this->sendMessage($lead->getMultiMessage(), NULL_BUTTONS);

			foreach ($lead->getMultiScripts() as $image) {
				$this->sendPhoto($image);
			}

			$this->sendMessage(OPERATOR_STATES_HAS_LEAD["MESSAGE"], OPERATOR_STATES_HAS_LEAD["BUTTONS"]);
			$this->setState(OPERATOR_STATES_LEAD_CALLED);
		} else {
			$this->sendMessage(OPERATOR_PUT_0["MESSAGE"], OPERATOR_PUT_0["BUTTONS"]);
			$this->setState(OPERATOR_PUT_0);
		}
	}

	

	public function lead_calledState()
	{
		if ($this->message == OPERATOR_FINISHED_CALL) {
			$this->sendMessage(OPERATOR_STATES_LEAD_CALLED["MESSAGE"], OPERATOR_STATES_LEAD_CALLED["BUTTONS"]);
			$this->setState(OPERATOR_ANSWERING);
		} else {
			$this->sendMessage(MESSAGE_USE_BUTTONS);
		}
	}

	public function answeringState()
	{
		switch ($this->message) {
			case OPERATOR_NOT_RESPONDING:
				$this->operator->lead()->process(NOT_RESPONDING, $this->operator->task(), [
					'operator_id' => $this->operator->id
				]);

				$next_step = [
					'message' => OPERATOR_ANSWERING["MESSAGE"],
					'buttons' => OPERATOR_COMMENTING["BUTTONS"],
					'state' => OPERATOR_COMMENTING
				];
			break;
			case OPERATOR_SUCCESS:
				$this->operator->lead()->process(SUCCESS_PROCESSED, $this->operator->task(), [
					'operator_id' => $this->operator->id
				]);

				$scenario = $this->operator->lead()->getScenario($this->operator);

				if (!($next_step = $scenario->successProcessing($this->operator->lead()))) {
					$next_step = [
						'message' => OPERATOR_ANSWERING["MESSAGE"],
						'buttons' => OPERATOR_COMMENTING["BUTTONS"],
						'state' => OPERATOR_COMMENTING
					];
				}
			break;
			case OPERATOR_CALL_LATER:
				$next_step = [
					'message' => OPERATOR_ANSWERING["MESSAGE_CHOOSE_TIME"],
					'buttons' => NULL_BUTTONS,
					'state' => OPERATOR_CHOSING_TIME_STATE
				];
			break;

			case OPERATOR_SUCCESS_ONE_COMPLEX:
				$next_step = [
					'message' => OPERATOR_ANSWERING["MESSAGE_CHOOSE_TIME"],
					'buttons' => NULL_BUTTONS,
					'state' => OPERATOR_CHOSING_TIME_STATE
				];

				$this->operator->lead()->process(ONE_CONNECTION, $this->operator->task(), [
					'operator_id' => $this->operator->id
				]);

			break;

			case OPERATOR_NOT_INTERESTED:
				$this->operator->lead()->process(NOT_INTERESTED, $this->operator->task(), [
					'operator_id' => $this->operator->id
				]);

				$scenario = $this->operator->lead()->getScenario($this->operator);

				if (!($next_step = $scenario->notInterestedProcessing($this->operator->lead()))) {
					$next_step = [
						'message' => OPERATOR_ANSWERING["MESSAGE"],
						'buttons' => OPERATOR_COMMENTING["BUTTONS"],
						'state' => OPERATOR_COMMENTING
					];
				}
			break;

			case OPERATOR_BLACKLISTED:
				
				$blp = new BlacklistPhone;
				$blp->phone = $this->operator->lead()->phone;
				$blp->save();

				$next_step = [
					'message' => OPERATOR_ANSWERING["MESSAGE"],
					'buttons' => OPERATOR_COMMENTING["BUTTONS"],
					'state' => OPERATOR_COMMENTING
				];

			break;

			default:
				$this->sendMessage(MESSAGE_USE_BUTTONS);
			break;
		}

		if ($next_step) {
			$this->sendMessage($next_step['message'], $next_step['buttons']);
			$this->setState($next_step['state']);

			if (isset($next_step['question_id'])) $this->operator->setQuestion($next_step['question_id']);
		}
	}


	public function scenario_answeringState()
	{
		$scenario = $this->operator->lead()->getScenario($this->operator);
		$next_step = $scenario->answering($this->operator->question_id, $this->message, $this->operator->lead());

		if (!$next_step) {
			return $this->sendMessage(MESSAGE_USE_BUTTONS);
		} elseif (!isset($next_step['question_id'])) {
			$next_step = [
				'message' => OPERATOR_ANSWERING["MESSAGE"],
				'buttons' => OPERATOR_COMMENTING["BUTTONS"],
				'state' => OPERATOR_COMMENTING
			];
		}

		$this->sendMessage($next_step['message'], $next_step['buttons']);
		$this->setState($next_step['state']);

		if (isset($next_step['question_id'])) $this->operator->setQuestion($next_step['question_id']);
	}

	public function scenario_answering_commentState()
	{
		$scenario = $this->operator->lead()->getScenario($this->operator);
		$next_step = $scenario->answerCommenting($this->operator->question_id, $this->message, $this->operator->lead());

		if (!isset($next_step['question_id'])) {
			$next_step = [
				'message' => OPERATOR_ANSWERING["MESSAGE"],
				'buttons' => OPERATOR_COMMENTING["BUTTONS"],
				'state' => OPERATOR_COMMENTING
			];
		}

		$this->sendMessage($next_step['message'], $next_step['buttons']);
		$this->setState($next_step['state']);

		if (isset($next_step['question_id'])) $this->operator->setQuestion($next_step['question_id']);

	}

	public function commentingState()
	{
		//пишем коммент и событие

		$this->operator->lead()->process(COMMENT_BY_OPERATOR, $this->operator->task(), [
			'operator_id' => $this->operator->id,
			'comment' => $this->message
		]);

		$this->sendMessage(OPERATOR_COMMENTING["MESSAGE"], NULL_BUTTONS);
		$this->sendMessage(OPERATOR_STATES_SLEEP["MESSAGE"], OPERATOR_STATES_SLEEP["BUTTONS"]);
		$this->setState(OPERATOR_STATES_AWAKED);
	}

	public function chooseTimeState()
	{
		$format = preg_match("/^\d\d\d\d-\d\d-\d\d \d\d:\d\d/", $this->message);

		if (!$format || $this->message > "2099-01-01 00:00") {
			$this->sendMessage(OPERATOR_CHOSING_TIME_STATE["MESSAGE_WRONG"], []);
		} else {
			//пишем событие и время
			$this->operator->lead()->process(RECALLING, $this->operator->task(), [
				'operator_id' => $this->operator->id, 
				'dt_recall' => $this->message
			]);

			$this->sendMessage(OPERATOR_ANSWERING["MESSAGE"], OPERATOR_COMMENTING["BUTTONS"]);
			$this->setState(OPERATOR_COMMENTING);
		}
	}

	/**

	PRIVATE

	**/

	public function setLead()
	{
		$task = LeadTask::getPriorityTask($this->operator);

		if (!$task) {
			return false;
		}

		$lead = $task->lead();
		$this->operator->lead_task_id = $task->id;
		$this->operator->save();

		$this->operator->lead()->process(GOT_BY_OPERATOR, null, [
			'operator_id' => $this->operator->id
		]);

		$message = $lead->getMessage();

		if ($message['image']) {
			$this->sendPhoto('https://zvonar.take-make.ru/' . $message['image']);
		}

		$this->sendMessage($message['text'], OPERATOR_STATES_AWAKED["BUTTONS"]);
		$this->setState(OPERATOR_STATES_HAS_LEAD);

		return true;
	}


	private function setOperator($chat_id, $username) {
		$operator = Operator::where('chat_id', $chat_id)->first();
		if (!$operator) {
			$operator = Operator::where('username', $username)->first();
			if (!$operator) throw new \Exception("Not any operator", 303);

			$operator->chat_id = $chat_id;
			$operator->state = OPERATOR_STATES_SLEEP['STATUS'];
			$operator->save();
		}

		$this->operator = $operator;
	}

	private function createCall()
	{
		app('App\Http\Controllers\VoxController')->createCall($this->operator->id);
	}



	private function sendMessage($message, $keyboard = null)
	{
		Log::debug("Sending message (" . $message . ") " . json_encode($keyboard) . "operator_id: " . $this->operator->id);

		$keyboard = $keyboard ? new Keyboard($keyboard) : null;

		$this
			->api
			->sendMessage(
				$this->operator->chat_id, 
				$message, null, false, null, $keyboard
			);
	}

	private function sendPhoto($photo)
	{
	
		$this
			->api
			->sendPhoto($this->operator->chat_id, $photo);
	}

	private function setState($state)
	{
		Log::debug("Setting state for operator " . $state["STATUS"] . " id:" . $this->operator->id);


		$this->operator->state = $state["STATUS"];
		$this->operator->save();
	}


}