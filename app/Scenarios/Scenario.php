<?php

namespace App\Scenarios;

use App\Question;
use App\Answer;
use App\Operator;
use App\LeadAnswer;
use Illuminate\Support\Facades\Log;

class Scenario
{
	public $need_lead_data = false;

	public function __construct($lead, $operator)
	{
		$this->lead = $lead;
		$this->operator = $operator;
		$this->scenario = $lead->callcenter()->scenario();
	}

	public function getFirstQuestion($column)
	{
		if (!in_array($column, ['success', 'not_interest'])) throw new Exception("Are u crazy?", 1);

		$question = $this->scenario->firstQuestion($column);

		if (!$question) return false;
		
		$answers = $question->answers();

		$buttons = [];

		foreach ($answers as $answer) {
			$buttons[] = $answer->text;
		}

		return [
			'question_id' => $question->id,
			'message' => $question->text,
			'buttons' => [$buttons],
			'state' => SCENARIO_ANSWERING
		];
	}

	public function successProcessing($lead)
	{
		return $this->getFirstQuestion('success');
	}

	public function notInterestedProcessing($lead)
	{
		return $this->getFirstQuestion('not_interest');
	}

	public function answering($question_id, $answer_text, $lead)
	{
		$lead_answer = new \App\LeadAnswer;
		$lead_answer->lead_id = $lead->id;
		$lead_answer->operator_id = $this->operator->id;
		$lead_answer->question_id = $question_id;

		$question = Question::find($question_id);
		$answers = $question->answers();
		$answer = null;

		foreach ($answers as $a) {
			if ($a->text == $answer_text) $answer = $a;
		}

		if (!$answer) {
			return false;
		}

		$lead_answer->answer_id = $answer->id;
		$lead_answer->save();

		if ($answer->need_comment) {
			$lead_answer->save();
			return  [
				'question_id' => $question_id,
				'message' => MESSAGE_SCENARIO_WRITE_COMMENT,
				'buttons' => NULL_BUTTONS,
				'state' => SCENARIO_ANSWERING_COMMENT
			];
		}

		
		

		if (!$answer->next_question_id) {
			return ['message' => $question->text];
		}

		$question = Question::find($answer->next_question_id);
		$answers = $question->answers();

		$buttons = [];

		$i = 0;
		$j = 0;

		foreach ($answers as $answer) {
			if (!isset($buttons[$i])) $buttons[$i]=[];

			$buttons[$i][] = $answer->text;

			$j++;

			if ($j > 2) {
				$j=0;
				$i++;
			}
		}

		Log::debug($buttons);

		return [
			'question_id' => $question->id,
			'message' => $question->text,
			'buttons' => $buttons,
			'state' => SCENARIO_ANSWERING
		];
	}


	public function answerCommenting($question_id, $comment, $lead)
	{

		$lead_answer = LeadAnswer::where('lead_id', $lead->id)->where('question_id', $question_id)->first();
		$lead_answer->comment = $comment;
		$lead_answer->save();


		$answer = Answer::find($lead_answer->answer_id);

		if (!$answer->next_question_id) {
			return ['message' => ''];
		}

		$question = Question::find($answer->next_question_id);
		$answers = $question->answers();

		$buttons = [];

		foreach ($answers as $answer) {
			$buttons[] = $answer->text;
		}

		return [
			'question_id' => $question->id,
			'message' => $question->text,
			'buttons' => [$buttons],
			'state' => SCENARIO_ANSWERING
		];
	}

}