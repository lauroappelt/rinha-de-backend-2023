<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Person;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Ramsey\Uuid\Uuid;
use App\Request\PersonRequest;

class PersonController extends AbstractController
{
    public function __construct()
    {
        
    }
    
    public function createPerson(PersonRequest $request, ResponseInterface $response)
    {   
        $params = $request->all();
        $params['id'] ??= Uuid::uuid4();
        $params['searchable'] = 'as';
        Person::create($params);
        return $response->json([])->withStatus(201);
    }
}
