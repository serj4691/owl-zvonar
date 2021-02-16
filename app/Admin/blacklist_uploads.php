<?php
use App\BlacklistUpload;
use App\Callcenter;
use SleepingOwl\Admin\Model\ModelConfiguration;

AdminSection::registerModel(BlacklistUpload::class, function (ModelConfiguration $model) {
    $model->setTitle('Загрузка лидов');
    // Display
    $model->onDisplay(function () {
        $display = AdminDisplay::table()->setColumns([
            
            AdminColumn::text('created_at')->setLabel('Дата загрузки')->setWidth('400px') ,
            AdminColumn::text('uploaded')->setLabel('Загружено')->setWidth('400px')
        ]);
        $display->paginate(15);
        return $display;
    });
    // Create And Edit
    $model->onCreateAndEdit(function() {

        $callcenters = Callcenter::get();
        $callcenter_list = [];

        foreach ($callcenters as $s) {
            $callcenter_list[$s->id] = $s->name; 
        }

        $form = AdminForm::panel()->addBody(
            AdminFormElement::file('file', 'Файл')
        );
        return $form;
    });
});