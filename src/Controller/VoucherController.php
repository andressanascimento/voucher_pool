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

    public function find(Request $request, Response $response, $args)
    {
        $voucher = $this->_repository->find($args['code']);
        if ($voucher) {
            return $response->withJson($voucher->toArray())->withStatus(200);
        }
        return $response->withJson(["message"=>"No voucher found"])
                        ->withStatus(404);
    }

    public function search(Request $request, Response $response, $args)
    {
        $vouchers = $this->_repository->search($args['email']);
        if ($vouchers) {
            return $response->withJson($vouchers->toArray());
        }
        return $response->withJson(["message"=>"No voucher found"])
                        ->withStatus(404);
    }

    public function validate(Request $request, Response $response, $args)
    {
        $voucher = $this->_repository->validate($args['code'],$args['email']);
        if ($voucher) {
            $discount = $voucher->getDiscount() * 100;
            return $response->withJson(["message" =>"Your discount: ".$discount."%"]);
        }
        return $response->withJson(["message"=>"No voucher found"])
                        ->withStatus(404);
    }
}