<?php

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\VoucherRepository;
use App\Models\Entity\Voucher;
use App\Controller\VoucherController;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

class VoucherControllerTest extends \PHPUnit_Framework_TestCase
{
    private $_repository;

    private $_validator;

    private $_recipient;

    protected function setUp()
    {
        $this->_repository = $this->createMock(VoucherRepository::class);
        $this->_model = $this->createMock(Voucher::class);
    }

    public function testGetFindRequestReturns200()
    {
        //mock returns
        $return = [
            "code" => "HJJU55544",
            "offer" => "Promo 454",
            "date_used" => "2019-05-05"
        ];

        $this->_model->method("toArray")->willReturn($return);
        $this->_repository->method("find")->willReturn($this->_model);

        $controller = new VoucherController($this->_repository);
 
        $environment = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/voucher',
            'QUERY_STRING'=>'']
        );
        $request = Request::createFromEnvironment($environment);
        $response = new Response();
 
        // run the controller action and test it
        $response = $controller->find($request, $response, ["code"=>"HJJU55544"]);

        $this->assertSame((string)$response->getBody(), json_encode($return));
        $this->assertSame($response->getStatusCode(), 200);
    }

    public function testGetFindRequestReturns404()
    {
        //mock returns
        $this->_repository->method("find")->willReturn(null);

        $controller = new VoucherController($this->_repository);
 
        $environment = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/voucher',
            'QUERY_STRING'=>'']
        );
        $request = Request::createFromEnvironment($environment);
        $response = new Response();
 
        // run the controller action and test it
        $response = $controller->find($request, $response, ["code"=>"HJJU55544"]);
        $this->assertSame((string)$response->getBody(), '{"message":"No voucher found"}');
        $this->assertSame($response->getStatusCode(), 404);
    }

    public function testGetSearchRequestReturns200()
    {
        //mock returns
        $return = [
            [
            "code" => "HJJU55544",
            "offer" => "Promo 454",
            "date_used" => "2019-05-05"
            ],
            [
                "code" => "VGH45888",
                "offer" => "Promo 788",
                "date_used" => "2019-06-12"
            ]
        ];

        $this->_repository->method("search")->willReturn($this->_model);
        $this->_model->method("toArray")->willReturn($return);

        $controller = new VoucherController($this->_repository);
 
        $environment = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/voucher',
            'QUERY_STRING'=>'']
        );
        $request = Request::createFromEnvironment($environment);
        $response = new Response();
 
        // run the controller action and test it
        $response = $controller->search($request, $response, ["email"=>"teste@teste.com"]);

        $this->assertSame((string)$response->getBody(), json_encode($return));
        $this->assertSame($response->getStatusCode(), 200);
    }

    public function testGetSearchRequestReturns404()
    {
        //mock returns
        $this->_repository->method("search")->willReturn(null);

        $controller = new VoucherController($this->_repository);
 
        $environment = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/voucher',
            'QUERY_STRING'=>'']
        );
        $request = Request::createFromEnvironment($environment);
        $response = new Response();
 
        // run the controller action and test it
        $response = $controller->search($request, $response, ["email"=>"teste@teste.com"]);
        $this->assertSame((string)$response->getBody(), '{"message":"No voucher found"}');
        $this->assertSame($response->getStatusCode(), 404);
    }

    public function testGetValidateRequestReturns200()
    {
        //mock returns
        $this->_model->method("getDiscount")->willReturn(0.2);
        $this->_repository->method("validate")->willReturn($this->_model);

        $controller = new VoucherController($this->_repository);
 
        $environment = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/voucher',
            'QUERY_STRING'=>'']
        );
        $request = Request::createFromEnvironment($environment);
        $response = new Response();
 
        // run the controller action and test it
        $response = $controller->validate($request, $response, ["code"=>"HJJU55544", "email" => "teste@teste.com"]);

        $this->assertSame((string)$response->getBody(), json_encode(["message"=>"Your discount: 20%"]));
        $this->assertSame($response->getStatusCode(), 200);
    }

    public function testGetValidateRequestReturns404()
    {
        //mock returns
        $this->_repository->method("validate")->willReturn(null);

        $controller = new VoucherController($this->_repository);
 
        $environment = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/voucher',
            'QUERY_STRING'=>'']
        );
        $request = Request::createFromEnvironment($environment);
        $response = new Response();
 
        // run the controller action and test it
        $response = $controller->validate($request, $response, ["code"=>"HJJU55544", "email" => "teste@teste.com"]);
        $this->assertSame((string)$response->getBody(), '{"message":"No voucher found"}');
        $this->assertSame($response->getStatusCode(), 404);
    }


}