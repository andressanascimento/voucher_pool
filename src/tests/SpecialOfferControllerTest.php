<?php

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\DBAL\Driver\AbstractDriverException;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SpecialOfferRepository;
use App\Repository\VoucherRepository;
use App\Validator\SpecialOfferValidator;
use App\Models\Entity\SpecialOffer;
use App\Controller\SpecialOfferController;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

class SpecialOfferControllerTest extends \PHPUnit_Framework_TestCase
{
    private $_repository;

    private $_validator;

    private $_recipient;

    protected function setUp()
    {
        $this->_repository = $this->createMock(SpecialOfferRepository::class);
        $this->_repository_voucher = $this->createMock(VoucherRepository::class);
        $this->_validator = $this->createMock(SpecialOfferValidator::class);
        $this->_model = $this->createMock(SpecialOffer::class);
    }

    public function testPostRequestFailValidator400()
    {
        //mock returns
        $this->_validator->method("assert")->willReturn(false);
        $this->_validator->method("getErrors")->willReturn(["message"=>"name should be only letters"]);

        $controller = new SpecialOfferController($this->_repository, $this->_repository_voucher, $this->_validator);
 
        $environment = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/special-offer',
            'QUERY_STRING'=>'']
        );
        $request = Request::createFromEnvironment($environment);
        $response = new Response();
 
        // run the controller action and test it
        $response = $controller->create($request, $response, []);
        $this->assertSame((string)$response->getBody(), '{"message":"name should be only letters"}');
        $this->assertSame($response->getStatusCode(), 400);
    }

    public function testPostRequestCreated201()
    {
        //mock returns
        $post = [
            "name" => "Oferta 54",
            "discount" => 0.2,
            "expire_date" => "2019-05-25"
        ];

        $this->_model->method("toArray")->willReturn($post);
        $this->_validator->method("assert")->willReturn(true);
        $this->_repository->method("create")->willReturn($this->_model);

        $controller = new SpecialOfferController($this->_repository, $this->_repository_voucher, $this->_validator);
 
        $environment = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/special-offer',
            'QUERY_STRING'=> '']
        );
        $request = Request::createFromEnvironment($environment);
        $response = new Response();
 
        // run the controller action and test it
        $response = $controller->create($request, $response, []);
        $this->assertSame((string)$response->getBody(), '{"name":"Oferta 54","discount":0.2,"expire_date":"2019-05-25"}');
        $this->assertSame($response->getStatusCode(), 201);
    }
}