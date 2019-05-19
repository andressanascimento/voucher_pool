<?php

namespace App\Controller;

use App\Repository\VoucherRepository;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class VoucherController {

    private $_repository;

    public function __construct(VoucherRepository $repository)
    {
        $this->_repository = $repository;
    }

    public function search(Request $request, Response $response, $args)
    {
        $vouchers = $this->_repository->search($args['email']);
        if ($vouchers) {
            return $response->withJson($vouchers);
        }
        return $response->withJson(["message"=>"No voucher found"])
                        ->withStatus(404);
    }

    public function validate(Request $request, Response $response, $args)
    {
        $voucher = $this->_repository->validate($args['code'],$args['email']);
        $discount = $voucher->getDiscount() * 100;
        if ($voucher) {
            return $response->withJson(["message" =>"Your discount: ".$discount."%"]);
        }
        return $response->withJson("No voucher found")
                        ->withStatus(404);
    }
}