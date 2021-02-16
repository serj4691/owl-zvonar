<?php
use App\Complex;
use App\Operator;
use App\Scenario;
use SleepingOwl\Admin\Model\ModelConfiguration;

AdminSection::registerModel(Complex::class, function (ModelConfiguration $model) {
    $model->setTitle('Коллцентры');
    // Display
    $model->onDisplay(function () {
        $display = AdminDisplay::table()->setColumns([
            AdminColumn::text('name')->setLabel('Название')->setWidth('400px'),
            AdminColumnEditable::text('phone')->setLabel('Телефон')->setWidth('400px'),

            AdminColumnEditable::checkbox('is_active')->setLabel('Активен')->setWidth('400px'),
            AdminColumnEditable::text('budjet0')->setLabel('Студии от')->setWidth('400px'),
            AdminColumnEditable::text('budjet0_max')->setLabel('Студии до')->setWidth('400px'),
            AdminColumnEditable::text('budjet1')->setLabel('1-к от')->setWidth('400px'),
            AdminColumnEditable::text('budjet1_max')->setLabel('1-к до')->setWidth('400px'),
            AdminColumnEditable::text('budjet2')->setLabel('2-к от')->setWidth('400px'),
            AdminColumnEditable::text('budjet2_max')->setLabel('2-к до')->setWidth('400px'),
            AdminColumnEditable::text('budjet3')->setLabel('3-к от')->setWidth('400px'),
            AdminColumnEditable::text('budjet3_max')->setLabel('3-к до')->setWidth('400px'),
            AdminColumnEditable::text('budjet4')->setLabel('4-к от')->setWidth('400px'),
            AdminColumnEditable::text('budjet4_max')->setLabel('4-к до')->setWidth('400px'),
            AdminColumnEditable::select('region')->setOptions([
                'Север' => 'Север',
                'Юг' => 'Юг',
                'Запад' => 'Запад',
                'Восток' => 'Восток',
                'Центр' => 'Центр'
            ])->setLabel('Округ')->setWidth('400px')
        ]);
        $display->paginate(15);
        return $display;
    });
    // Create And Edit
    $model->onCreateAndEdit(function() {

        $form = AdminForm::panel()->addBody(
            AdminFormElement::text('name', 'Название'),
            AdminFormElement::text('short_description', 'Короткое описание'),
            AdminFormElement::image('script', 'Скрипт'),
            AdminFormElement::text('phone', 'Телефон')
        );
        return $form;
    });
});