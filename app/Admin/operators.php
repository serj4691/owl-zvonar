<?php
use App\Operator;
use App\Callcenter;
use SleepingOwl\Admin\Model\ModelConfiguration;

AdminSection::registerModel(Operator::class, function (ModelConfiguration $model) {
    $model->setTitle('Операторы');
    // Display
    $model->onDisplay(function () {
        $display = AdminDisplay::table()->setColumns([
            AdminColumn::text('name')->setLabel('Название')->setWidth('400px'),     
            AdminColumn::text('username')->setLabel('Username в TG')->setWidth('400px')     
        ]);
        $display->paginate(15);
        return $display;
    });
    // Create And Edit
    $model->onCreateAndEdit(function() {

        $callcenter = Callcenter::get();
        $callcenter_list = [];

        foreach ($callcenter as $s) {
            $callcenter_list[$s->id] = $s->name; 
        }

        $form = AdminForm::panel()->addBody(
            AdminFormElement::text('name', 'Имя'),
            AdminFormElement::text('username', 'Username в TG'),
            AdminFormElement::text('chat_id', 'ID чата'),
            AdminFormElement::text('phone', 'Телефон'),
            AdminFormElement::select('state', 'Состояние', [
                OPERATOR_STATES_SLEEP['STATUS'] => OPERATOR_STATES_SLEEP['STATUS'],
                OPERATOR_STATES_AWAKED['STATUS'] => OPERATOR_STATES_AWAKED['STATUS'],
                OPERATOR_STATES_HAS_LEAD['STATUS'] => OPERATOR_STATES_HAS_LEAD['STATUS'],
                OPERATOR_STATES_LEAD_CALLED['STATUS'] => OPERATOR_STATES_LEAD_CALLED['STATUS'],
                OPERATOR_ANSWERING['STATUS'] => OPERATOR_ANSWERING['STATUS'],
                OPERATOR_COMMENTING['STATUS'] => OPERATOR_COMMENTING['STATUS'],
                OPERATOR_CHOSING_TIME_STATE['STATUS'] => OPERATOR_CHOSING_TIME_STATE['STATUS'],
                OPERATOR_STATES_SLEEP['STATUS'] => OPERATOR_STATES_SLEEP['STATUS'],
            ]),
            AdminFormElement::multiselect('callcenters', 'Допущенные коллцентры', $callcenter_list)
        );
        return $form;
    });
});