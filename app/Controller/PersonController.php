<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\NotFoundException;
use App\Exception\UniqueException;
use Hyperf\HttpServer\Contract\ResponseInterface;
use App\Request\PersonRequest;
use App\Resource\PersonResource;
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
            $person = $this->personService->createPerson($request->all());
            return (new PersonResource($person))->withoutWrapping(true)->toResponse()->withHeader('Location', '/pessoas/' . $person->id)->withStatus(201);
        } catch (UniqueException $exception) {
            return $response->withStatus(422);
        }
    }

    public function getPerson(RequestInterface $request, ResponseInterface $response)
    {
        try {
            $person = $this->personService->getPerson($request->route('id'));
            return (new PersonResource($person))->withoutWrapping(true)->toResponse()->withStatus(200);
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
}
