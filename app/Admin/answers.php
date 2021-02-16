<?php
use App\Answer;
use App\Question;
use SleepingOwl\Admin\Model\ModelConfiguration;

AdminSection::registerModel(Answer::class, function (ModelConfiguration $model) {
    $model->setTitle('Ответы');
    // Display
    $model->onDisplay(function () {
        $display = AdminDisplay::table()->setColumns([
            AdminColumn::text('question.text')->setLabel('Вопрос')->setWidth('400px'),
            AdminColumn::text('text')->setLabel('Текст')->setWidth('400px')
        ]);
        $display->paginate(15);
        return $display;
    });
    // Create And Edit
    $model->onCreateAndEdit(function() {

        $questions = Question::get();
        $question_list = [];

        foreach ($questions as $q) {
            $question_list[$q->id] = $q->text; 
        }

        $form = AdminForm::panel()->addBody(
            AdminFormElement::text('text', 'Текст'),
            AdminFormElement::checkbox('need_comment', 'Нужен коммент'),
            AdminFormElement::select('question_id', 'Вопрос', $question_list),
            AdminFormElement::select('next_question_id', 'Следующий', $question_list)
        );
        return $form;
    });
});