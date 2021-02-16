<?php

use App\BasePhone;
use App\BaseComplex;
use SleepingOwl\Admin\Model\ModelConfiguration;

AdminSection::registerModel(BasePhone::class, function (ModelConfiguration $model) {
    $model->setTitle('Телефоны покупателей');
    // Display
    $model->onDisplay(function () {
        //$display = AdminDisplay::datatables();
        
        $display = AdminDisplay::datatables()->with('complexes')->setColumns([
            //AdminColumn::text('name')->setLabel('Имя')->setWidth('100px'),
            AdminColumn::text('phone')->setLabel('Номер телефона')->setWidth('150px'),
            // AdminColumn::text('complexes.name')->setLabel('Наименование комплекса')->setWidth('150px'),
            AdminColumn::custom('name', function(\Illuminate\Database\Eloquent\Model $model) {
                    return $model->id;
                })->setWidth('150px'),
            AdminColumn::text('sources')->setLabel('Наименование источника')->setWidth('150px'),
            AdminColumn::text('channels')->setLabel('Наименование канала')->setWidth('150px'),
            AdminColumn::datetime('first_contact_date')->setLabel('Дата первого обращения')->setFormat('d.m.Y')->setWidth('150px'),
            //AdminColumn::text('success_calls')->setLabel('Количество успешных звонков')->setWidth('30px'),
            //AdminColumn::text('got_through_calls')->setLabel('Количество полученных звонков')->setWidth('30px'),
            //AdminColumn::text('failed_calls')->setLabel('Количество неуспешных звонков')->setWidth('30px'),
            AdminColumn::text('comments')->setLabel('Комментарии')->setWidth('250px')
        ]);
        $display->setApply(function ($query) {
            $query->orderBy('phone', 'asc');
        });
        //$display->AdminDisplayFilter()::field('phone');
        $display->setColumnFilters([
             AdminColumnFilter::text()->setPlaceholder('Номер'),
             null,
             null,
             null,
             null,
             null
        ]);
        $display->paginate(10);
        return $display;
    });

});
