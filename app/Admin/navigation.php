<?php

use SleepingOwl\Admin\Navigation\Page;

return [
    [
        'title' => 'Коллцентры',
        'icon'  => 'fas fa-tachometer-alt',
        'url'   => '/admin/callcenters',
    ],
    [
        'title' => 'Операторы',
        'icon'  => 'fas fa-tachometer-alt',
        'url'   => '/admin/operators',
    ],
    [
        'title' => 'Жилые комплексы',
        'icon'  => 'fas fa-tachometer-alt',
        'url'   => '/admin/complexes',
    ],

    [
        'title' => 'Сценарии',
        'icon'  => 'fas fa-tachometer-alt',
        'url'   => '/admin/scenarios',
    ],
    [
        'title' => 'Стратегии',
        'icon'  => 'fas fa-tachometer-alt',
        'url'   => '/admin/not_responding_strategies',
    ],
    [
        'title' => 'Вопросы',
        'icon'  => 'fas fa-tachometer-alt',
        'url'   => '/admin/questions',
    ],
    [
        'title' => 'Ответы',
        'icon'  => 'fas fa-tachometer-alt',
        'url'   => '/admin/answers',
    ],
    [
        'title' => 'Загрузка лидов',
        'icon' => 'fas fa-upload',
        'url' => '/admin/lead_uploads'
    ],
    [
        'title' => 'Загрузка ЧС',
        'icon' => 'fas fa-upload',
        'url' => '/admin/blacklist_uploads'
    ],
    // [
    //     'title' => 'Телефоны покупателей old',
    //     'icon' => 'fas fa-phone',
    //     'url' => '/admin/customer_phones'
    // ],
    // [
    //     'title' => 'Import old',
    //     'icon' => 'fas fa-upload',
    //     'url' => '/admin/import_phones_old'
    // ],
    [
        'title' => 'Import new',
        'icon' => 'fas fa-upload',
        'url' => '/admin/import_phones'
    ],
    [
        'title' => 'Новый - Телефоны покупателей',
        'icon' => 'fas fa-phone',
        'url' => '/admin/base_phones'
    ],
];
