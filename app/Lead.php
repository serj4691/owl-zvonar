<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\LeadEvent;
use App\Complex;
use App\Call;

class Lead extends Model 
{
	public function callcenter()
	{
		return $this->belongsTo('\App\Callcenter', 'callcenter_id')->first();
	}

	public function events()
	{
		return $this->hasMany('\App\LeadEvent', 'lead_id')->get();
	}

	public function last_events()
	{
		$events = $this->hasMany('\App\LeadEvent', 'lead_id')
			->orderBy('id', 'desc')
			->take(10)
			->get()
		;

		return $events->reverse();
	}

	public function save(array $options = [])
	{
		if (!$this->id) {
			$result = parent::save($options);
			$this->process(CREATED);

		} else {
			$result = parent::save($options);
		}

		

		return $result;
	}

	public function setComplexes()
	{
		$min = "budjet".$this->rooms;
		$max = "budjet".$this->rooms."_max";

		$complexes = Complex::where($min, '<=', $this->price_from)->where($max, '>=', $this->price_to)->where('phone', '<>', '')->take(7)->get();

		$complex_ids = [];

		foreach ($complexes as $complex) $complex_ids[] = $complex->id;

		$this->ids = implode(",", $complex_ids);
		$this->save();
	}

	public function getScenario($operator)
	{
		$class = $this->callcenter()->scenario()->class;
		$class = "\\App\\Scenarios\\" . $class . "Scenario";


		return new $class($this, $operator);
	}

	public function eventsMessage()
	{
		$events = $this->last_events();
		$result = "";

		foreach ($events as $event) if (in_array($event->status, [NOT_INTERESTED, NOT_RESPONDING, CREATED, RECALLING, SUCCESS_PROCESSED, CALL_CREATED, COMMENT_BY_OPERATOR])) {
			$result .= $event->created_at;
			$result .= ": ";
			$result .= EVENT_MESSAGES[$event->status];
			if ($event->dt_recall) $result .= " ({$event->dt_recall})";
			if ($event->comment) $result .= ": " . $event->comment;

			if ($event->call_id) {
				$c = Call::find($event->call_id);

				$result .= "\n";
				$result .= $c->record_url;
			}

			$result .= "\n";
		}

		return $result;
	}

	public function getMessage()
	{
		$callcenter = $this->callcenter();

		$text = "Коллцентр " . $callcenter->name . "\n";
		$text .= $callcenter->description;
		$text .= "\n\n";
		$text .= "Лид: ". $this->name . "\n";
		$text .= $this->description;

		$text .= "\n\n\n";

		$text .= $this->eventsMessage();

		$text .= "\n\n\n";

		if ($this->ids) {
			$text .= $this->getMultiMessage();
		}

		return ['text' => $text, 'image' => $callcenter->script];
	}

	public function getMultiMessage()
	{
		$ids = explode(",", $this->ids);
		Log::debug($ids);
		$complexes = [];

		$text = "Подходящие ЖК:\n";

		foreach ($ids as $id) {
			$complexes[] = Complex::find($id)->name;
		}

		foreach ($complexes as $num => $name) {
			$text .= ($num+1) .  " - " . $name . "\n";
		}

		return $text;
	}

	public function getMultiScripts()
	{
		$ids = explode(",", $this->ids);
		$result = [];

		foreach ($ids as $id) {
			$script = Complex::find($id)->script;

			if ($script) $result[] = "https://zvonar.take-make.ru/" . $script;
		}

		return $result;
	}

	public function checkPhone()
    {
        $phone = $this->phone;

        $phone = preg_replace("/[^\d]/", "", $phone);
        $phone = preg_replace("/^8/", "7", $phone);

        $lead = Lead::where('phone', $phone)
        	->where('callcenter_id', '=', $this->callcenter_id)
            ->where('created_at', '>', date("Y-m-d"))->first();

        return !$lead && $phone[0] == "7" && strlen($phone) == 11;
    }

    public function generateEvent($status, $params = [])
    {
    	$event = new LeadEvent;

    	$event->lead_id = $this->id;
    	$event->status = $status;

    	if (isset($params['operator_id'])) $event->operator_id = $params['operator_id'];
    	if (isset($params['record_url'])) $event->record_url = $params['record_url'];
    	if (isset($params['comment'])) $event->comment = $params['comment'];
    	if (isset($params['dt_recall'])) $event->dt_recall = $params['dt_recall'];
    	if (isset($params['call_id'])) $event->call_id = $params['call_id'];

    	$event->save();
    }

    public function getNotResponsingTime()
    {
    	$n = $this->nr_count;
    	$strategy = $this->callcenter()->scenario()->strategy();
    	$slots = json_decode($strategy->slots);

    	if ($this->nr_count >= $strategy->nr_count) return false;
    	if ($this->nr_all_count >= $strategy->nr_all_count) return false;

    	$minutes = $slots[$n];

    	$this->nr_count++;
    	$this->nr_all_count++;
    	$this->save();

    	return date('Y-m-d H:i:s', strtotime("+$minutes minutes"));

    }

    public function process($type, $original_task = null, $params = [])
    {
    	if ($original_task) {
    		$operator = $original_task->operator();
    	}

    	$task = new LeadTask;
		$task->lead_id =  $this->id;

		if ($type !== NOT_RESPONDING) {
			$this->nr_count = 0;
			$this->save();
		}

		switch ($type) {
			case ONE_CONNECTION:
				$this->generateEvent($type, $params);
			break;
			case CREATED:
				Log::debug("Lead created " . $this);

				$this->generateEvent($type);
				$task->priority = CREATED_PRIORITY;
				$task->processing_at = date('Y-m-d H:i:s');
				$task->save();
			break;
			case NOT_RESPONDING:
				$time = $this->getNotResponsingTime();

				$params['dt_recall'] = $time ? $time : null;

				Log::debug("Not responding recall: " . $params['dt_recall'] . "ID: " . $this->id);

				$this->generateEvent($type, $params);

				if ($time) {
					$task->priority = NOT_RESPONDING_PRIORITY;
					$task->processing_at = $time;
					$task->save();
				}
			break;
			case RECALLING:
				$this->generateEvent($type, $params);
				$task->priority = RECALLING_PRIORITY;
				$task->processing_at = $params['dt_recall'];
				$task->save();

				Log::debug("Recalling recall: " . $params['dt_recall'] . "ID: " . $this->id);
			break;
			case SUCCESS_PROCESSED:
				$this->generateEvent($type, $params);
			break;
			case GOT_BY_OPERATOR:
				$this->generateEvent($type, $params);
			break;
			case CALLING_TO_OPERATOR:
				$this->generateEvent($type, $params);
			break;
			case CALL_CREATED:
				$this->generateEvent($type, $params);
			break;
			case COMMENT_BY_OPERATOR:
				$this->generateEvent($type, $params);
				$operator->resetOperatorState(OPERATOR_STATES_AWAKED);
				$original_task->delete();
			break;
			case NOT_INTERESTED:
				$this->generateEvent($type, $params);
			break;
		}
    }
}