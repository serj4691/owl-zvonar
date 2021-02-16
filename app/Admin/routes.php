<?php

Route::get('', ['as' => 'admin.dashboard', function () {
    $content = 'Define your dashboard here.';
    return AdminSection::view($content, 'Dashboard');
}]);

Route::get('information', ['as' => 'admin.information', function () {
    $content = 'Define your information here.';
    return AdminSection::view($content, 'Information');
}]);

//Route::post('show', '\App\Http\Controllers\ImportPhonesController@index');

// Route::get('import_phones', function () {
//     $content = view('show');
//     return AdminSection::view($content, 'My Import Page');
// });
// Route::post('customer_phone/import', '\App\Http\Controllers\CustomerPhoneController@import');
Route::get('import_phones', function () {
    $content = view('show');
    return AdminSection::view($content, 'Import Page');
});
// Route::get('import_phones_old', function () {
//     $content = view('import');
//     return AdminSection::view($content, 'Old Import Page');
// });
Route::post('base_phone/import', '\App\Http\Controllers\BasePhoneController@import');

//Route::post('customer_phone/import', '\App\Http\Controllers\CustomerPhoneController@import');

