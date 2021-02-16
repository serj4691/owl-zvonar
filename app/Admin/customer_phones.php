<?php

use App\CustomerPhone;
//use \App\Imports\CustomerPhoneImport;
use SleepingOwl\Admin\Model\ModelConfiguration;

AdminSection::registerModel(CustomerPhone::class, function (ModelConfiguration $model) {
    $model->setTitle('Телефоны покупателей');
    // Display
    $model->onDisplay(function () {
        //$display = AdminDisplay::datatables();
        
        $display = AdminDisplay::datatables()->setColumns([
            AdminColumn::text('phoneNumber')->setLabel('Номер телефона')->setWidth('400px'),
            AdminColumn::text('nameSource')->setLabel('Название источника поступлнения')->setWidth('400px'),
            AdminColumn::text('nameBase')->setLabel('Название базы')->setWidth('400px'),
            AdminColumn::text('UTM_campaign')->setLabel('UTM_campaign')->setWidth('400px'),
            AdminColumn::text('UTM_Source')->setLabel('UTM_Source')->setWidth('400px'),
            AdminColumn::text('nameResidentialComplexDonor')->setLabel('Название Жилого Комплекса донора')->setWidth('400px')
        ]);
        //$display->AdminDisplayFilter()::field('phoneNumber');
        $display->setColumnFilters([
             AdminColumnFilter::text()->setPlaceholder('Номер'),
             AdminColumnFilter::text()->setPlaceholder('Источник'),
             null,
             null,
             null,
             null
        ]);
        $display->paginate(10);
        return $display;
    });

    // $model->onCreateAndEdit(function () {

    //     $customerphones = CustomerPhone::get();
    //     $customerphones_list = [];

    //     foreach ($customerphones as $c) {
    //         $customerphones_list[$c->id] = $c->text;
    //     }
    //        $form = AdminForm::panel()->addBody(
    ////            FormItem::view('import');
    //            AdminFormElement::view(string 'import', array $data = [], Closure $callback = null): static
    //
    //        );
    //        return $form;
    //CustomerPhoneImport::model();
    // $form = AdminForm::panel()->addBody(
    //     AdminFormElement::text('phoneNumber', 'Номер телефона'),
    //     AdminFormElement::text('nameSource', 'Название источника поступлнения'),
    //     AdminFormElement::text('nameBase', 'Название базы'),
    //     AdminFormElement::text('nameResidentialComplexDonor', 'Название Жилого Комплекса донора')
    // );
    // return $form;
    //   });
    //    $model->onimport()
});
