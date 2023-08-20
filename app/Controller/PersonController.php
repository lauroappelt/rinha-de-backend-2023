<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\NotFoundException;
use App\Exception\UniqueException;
use Hyperf\HttpServer\Contract\ResponseInterface;
use App\Request\PersonRequest;
use App\Service\PersonService;
use Hyperf\HttpServer\Contract\RequestInterface;

class PersonController extends AbstractController
{   
    private $personService;

    public function __construct(PersonService $personService)
    {
        $this->personService = $personService;
    }
    
    public function createPerson(PersonRequest $request, ResponseInterface $response)
    {      
        try {

            $data = $request->all();
            if (!is_string($data['apelido']) || !is_string($data['nome'])) {
                return $response->withStatus(400);
            }

            if (is_array($data['stack'])) {
                foreach ($data['stack'] as $stack) {
                    if (!is_string($stack)){
                        return $response->withStatus(400);
                    }
                }
            }

            $person = $this->personService->createPerson($data);
            return $response->withHeader('Location', '/pessoas/' . $person['id'])->withStatus(201)->json($person);
        } catch (UniqueException $exception) {
            return $response->withStatus(422);
        }
    }

    public function getPerson(RequestInterface $request, ResponseInterface $response)
    {
        try {
            $person = $this->personService->getPerson($request->route('id'));
            return $response->json($person);
        } catch (NotFoundException $exception) {
            return $response->withStatus(404);
        }
    }

    public function searchPerson(RequestInterface $request, ResponseInterface $response)
    {
        $term = $request->input('t');
        if ($term == null) {
            return $response->withStatus(400);
        }
        
        $persons = $this->personService->searchPerson($term);
        return $response->json($persons)->withStatus(200);
    }

    public function countPerson(RequestInterface $request, ResponseInterface $response)
    {
        $count = $this->personService->countPerson();
        return $count;
    }
}
