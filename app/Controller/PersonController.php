<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\HttpServer\Contract\ResponseInterface;
use App\Request\PersonRequest;
use App\Resource\PersonResource;
use App\Service\PersonService;

class PersonController extends AbstractController
{   
    private $personService;

    public function __construct(PersonService $personService)
    {
        $this->personService = $personService;
    }
    
    public function createPerson(PersonRequest $request, ResponseInterface $response)
    {       
        $person = $this->personService->createPerson($request->all());
        return (new PersonResource($person))->toResponse();
    }
}
