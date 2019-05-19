<?php

namespace App\Controller;

use App\Repository\RecipientRepository;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Respect\Validation\Exceptions\NestedValidationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use App\Validator\RecipientValidator;


class RecipientController {

    private $_repository;

    public function __construct(RecipientRepository $repository, RecipientValidator $validator)
    {
        $this->_repository = $repository;
        $this->_validator = $validator;
    }

    public function find(Request $request, Response $response, $args)
    {
        $recipient = $this->_repository->find($args['email']);
        if ($recipient) {
            return $response->withJson($recipient->toArray());
        }
        return $response->withJson("No recipient found with that slug.")
                        ->withStatus(404);
    }

    public function create($request, $response, $args)
    {
        $params = $request->getParams();

        if (!$this->_validator->assert($params)) {
            $response->withJson($this->_validator->getErrors(), 400);
        }

        try {
            $recipient = $this->_repository->create($params);
        } catch(UniqueConstraintViolationException $exception) {
            return $response->withJson(["message" => "Recipient already exists"], 400);
        }

        $return = $response->withJson($recipient->toArray(), 201)
                        ->withHeader('Content-type', 'application/json');
        return $return;
    }
}