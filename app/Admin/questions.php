<?php
use App\Question;
use App\Scenario;
use SleepingOwl\Admin\Model\ModelConfiguration;

AdminSection::registerModel(Question::class, function (ModelConfiguration $model) {
    $model->setTitle('Вопросы');
    // Display
    $model->onDisplay(function () {
        $display = AdminDisplay::table()->setColumns([
            AdminColumn::text('text')->setLabel('Название')->setWidth('400px'),  
            AdminColumn::text('scenario.name')->setLabel('Сценарий')->setWidth('400px')
        ]);
        $display->paginate(15);
        return $display;
    });
    // Create And Edit
    $model->onCreateAndEdit(function() {

        $scenarios = Scenario::get();
        $scenario_list = [];

        foreach ($scenarios as $s) {
            $scenario_list[$s->id] = $s->name; 
        }

        $form = AdminForm::panel()->addBody(
            AdminFormElement::text('text', 'Имя'),
            AdminFormElement::select('scenario_id', 'Сценарий', $scenario_list)
        );
        return $form;
    });
});