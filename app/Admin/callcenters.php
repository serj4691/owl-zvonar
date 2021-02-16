<?php
use App\Callcenter;
use App\Operator;
use App\Scenario;
use SleepingOwl\Admin\Model\ModelConfiguration;

AdminSection::registerModel(Callcenter::class, function (ModelConfiguration $model) {
    $model->setTitle('Коллцентры');
    // Display
    $model->onDisplay(function () {
        $display = AdminDisplay::table()->setColumns([
            AdminColumn::text('name')->setLabel('Название')->setWidth('400px')       
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

        $operators = Operator::get();
        $operator_list = [];

        foreach ($operators as $s) {
            $operator_list[$s->id] = $s->name; 
        }

        $form = AdminForm::panel()->addBody(
            AdminFormElement::text('name', 'Имя'),
            AdminFormElement::textarea('description', 'Описание'),
            AdminFormElement::text('phone', 'Телефон'),
            
            AdminFormElement::image('script', 'Скрипт'),
            AdminFormElement::select('priority', 'Приоритет', [0 => 0, 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9 ])->required(),
            AdminFormElement::multiselect('operators', 'Допущенные операторы', $operator_list),
            AdminFormElement::select('scenario_id', 'Стратегия прозвона недоступных', $scenario_list),
            AdminFormElement::checkbox('use_redirect', 'Использовать переключение'),
            AdminFormElement::text('user_id_page', 'UserID домен')
        );
        return $form;
    });
});