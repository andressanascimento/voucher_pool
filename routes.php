<?php

$app->get('/recipient/{slug}', 'App\Controller\RecipientController:find');
$app->post('/recipient', 'App\Controller\RecipientController:create');

$app->post('/special-offer', 'App\Controller\SpecialOfferController:create');

$app->get('/voucher/validate/{code}/{email}', 'App\Controller\VoucherController:validate');
$app->get('/voucher/{email}', 'App\Controller\VoucherController:search');

