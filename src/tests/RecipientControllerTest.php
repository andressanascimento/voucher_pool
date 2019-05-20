<?php

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\DBAL\Driver\AbstractDriverException;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RecipientRepository;
use App\Validator\RecipientValidator;
use App\Models\Entity\Recipient;
use App\Controller\RecipientController;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

class RecipientControllerTest extends \PHPUnit_Framework_TestCase
{
    private $_repository;

    private $_validator;

    private $_recipient;

    protected function setUp()
    {
        $this->_repository = $this->createMock(RecipientRepository::class);
        $this->_validator = $this->createMock(RecipientValidator::class);
        $this->_recipient = $this->createMock(Recipient::class);
    }

    public function testGetRequestReturns200()
    {
        //mock returns
        $this->_recipient->method("toArray")->willReturn(["name"=>"Hudson","email"=>"teste@teste.com"]);
        $this->_repository->method("find")->willReturn($this->_recipient);

        $controller = new RecipientController($this->_repository, $this->_validator);
 
        $environment = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/recipient',
            'QUERY_STRING'=>'']
        );
        $request = Request::createFromEnvironment($environment);
        $response = new Response();
 
        // run the controller action and test it
        $response = $controller->find($request, $response, ["email"=>"teste@teste.com"]);

        $this->assertSame((string)$response->getBody(), '{"name":"Hudson","email":"teste@teste.com"}');
        $this->assertSame($response->getStatusCode(), 200);
    }

    public function testGetRequestReturns404()
    {
        //mock returns
        $this->_repository->method("find")->willReturn(null);

        $controller = new RecipientController($this->_repository, $this->_validator);
 
        $environment = Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/recipient',
            'QUERY_STRING'=>'']
        );
        $request = Request::createFromEnvironment($environment);
        $response = new Response();
 
        // run the controller action and test it
        $response = $controller->find($request, $response, ["email"=>"teste@teste.com"]);
        $this->assertSame((string)$response->getBody(), '{"message":"No recipient found with that email"}');
        $this->assertSame($response->getStatusCode(), 404);
    }

    public function testPostRequestFailValidator400()
    {
        //mock returns
        $this->_validator->method("assert")->willReturn(false);
        $this->_validator->method("getErrors")->willReturn(["message"=>"name should be only letters"]);

        $controller = new RecipientController($this->_repository, $this->_validator);
 
        $environment = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/recipient',
            'QUERY_STRING'=>'']
        );
        $request = Request::createFromEnvironment($environment);
        $response = new Response();
 
        // run the controller action and test it
        $response = $controller->create($request, $response, []);
        $this->assertSame((string)$response->getBody(), '{"message":"name should be only letters"}');
        $this->assertSame($response->getStatusCode(), 400);
    }

    public function testPostRequestUniqueKey400()
    {
        $driverExceptionMock = $this->createMock(AbstractDriverException::class);
        //mock returns
        $this->_validator->method("assert")->willReturn(true);
        $this->_repository->method("create")->will(
            $this->throwException(new UniqueConstraintViolationException("Unique",$driverExceptionMock))
        );

        $controller = new RecipientController($this->_repository, $this->_validator);
 
        $environment = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/recipient',
            'QUERY_STRING'=>'']
        );
        $request = Request::createFromEnvironment($environment);
        $response = new Response();
 
        // run the controller action and test it
        $response = $controller->create($request, $response, []);
        $this->assertSame((string)$response->getBody(), '{"message":"Recipient already exists"}');
        $this->assertSame($response->getStatusCode(), 400);
    }

    public function testPostRequestCreated201()
    {
        //mock returns
        $this->_recipient->method("toArray")->willReturn(["name"=>"Hudson","email"=>"teste@teste.com"]);
        $this->_validator->method("assert")->willReturn(true);
        $this->_repository->method("create")->willReturn($this->_recipient);

        $controller = new RecipientController($this->_repository, $this->_validator);
 
        $environment = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/recipient',
            'QUERY_STRING'=> '']
        );
        $request = Request::createFromEnvironment($environment);
        $response = new Response();
 
        // run the controller action and test it
        $response = $controller->create($request, $response, []);
        $this->assertSame((string)$response->getBody(), '{"name":"Hudson","email":"teste@teste.com"}');
        $this->assertSame($response->getStatusCode(), 201);
    }
}