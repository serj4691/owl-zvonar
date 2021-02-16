<?php
use App\Scenario;
use App\Question;
use App\NotRespondingStrategy;
use SleepingOwl\Admin\Model\ModelConfiguration;

AdminSection::registerModel(Scenario::class, function (ModelConfiguration $model) {
    $model->setTitle('Сценарии');
    // Display
    $model->onDisplay(function () {
        $display = AdminDisplay::table()->setColumns([
            AdminColumn::text('name')->setLabel('Название')->setWidth('400px'),  
            AdminColumn::text('class')->setLabel('Класс')->setWidth('400px')
        ]);
        $display->paginate(15);
        return $display;
    });
    // Create And Edit
    $model->onCreateAndEdit(function() {

        $strategies = NotRespondingStrategy::get();
        $strategy_list = [];

        foreach ($strategies as $s) {
            $strategy_list[$s->id] = $s->name; 
        }

        $questions = Question::get();
        $question_list = [];

        foreach ($questions as $q) {
            $question_list[$q->id] = $q->text; 
        }

        $form = AdminForm::panel()->addBody(
            AdminFormElement::text('name', 'Имя'),
            AdminFormElement::text('class', 'Класс'),
            AdminFormElement::select('strategy_id', 'Стратегия прозвона недоступных', $strategy_list),
            AdminFormElement::select('success_first_id', 'Первый вопрос успеха', $question_list),
            AdminFormElement::select('not_interest_first_id', 'Первый вопрос нет интереса', $question_list)

            

        );
        return $form;
    });
});