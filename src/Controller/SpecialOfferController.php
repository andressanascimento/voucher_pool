<?php

namespace App\Controller;

use App\Repository\SpecialOfferRepository;
use App\Repository\VoucherRepository;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Validator\SpecialOfferValidator;


class SpecialOfferController {

    private $_repository;

    public function __construct(SpecialOfferRepository $repository, VoucherRepository $voucher_repository, SpecialOfferValidator $validator)
    {
        $this->_repository = $repository;
        $this->_voucher_repo = $voucher_repository;
        $this->_validator = $validator;
    }

    public function create($request, $response, $args)
    {
        $params = $request->getParams();

        if (!$this->_validator->assert($params)) {
            return $response->withJson($this->_validator->getErrors(), 400);
        }

        $special_offer = $this->_repository->create($params);
        $this->_voucher_repo->create_vouchers($special_offer);
        
        return $response->withJson($special_offer->toArray(), 201);
    }
}