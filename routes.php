<?php

$app->get('/recipient/{email}', 'App\Controller\RecipientController:find');
$app->post('/recipient', 'App\Controller\RecipientController:create');

$app->post('/special-offer', 'App\Controller\SpecialOfferController:create');

$app->get('/voucher/validate/code/{code}/email/{email}', 'App\Controller\VoucherController:validate');
$app->get('/voucher/email/{email}', 'App\Controller\VoucherController:search');
$app->get('/voucher/{code}', 'App\Controller\VoucherController:find');

