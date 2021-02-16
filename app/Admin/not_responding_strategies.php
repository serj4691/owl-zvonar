<?php
use App\NotRespondingStrategy;
use App\Scenario;
use SleepingOwl\Admin\Model\ModelConfiguration;

AdminSection::registerModel(NotRespondingStrategy::class, function (ModelConfiguration $model) {
    $model->setTitle('Стратегии');
    // Display
    $model->onDisplay(function () {
        $display = AdminDisplay::table()->setColumns([
            AdminColumn::text('name')->setLabel('Название')->setWidth('400px'),  
            AdminColumn::text('nr_count')->setLabel('Кол-во попыток')->setWidth('400px'),
            AdminColumn::text('nr_all_count')->setLabel('Кол-во попыток в целом')->setWidth('400px')
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

        $form = AdminForm::panel()->addBody(
            AdminFormElement::text('name', 'Имя'),
            AdminFormElement::text('nr_count', 'Кол-во попыток'),
            AdminFormElement::text('nr_all_count', 'Кол-во попыток в целом'),
            AdminFormElement::textarea('slots', 'Слоты')
        );
        return $form;
    });
});