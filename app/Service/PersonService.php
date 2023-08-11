<?php
namespace App\Service;

use App\Model\Person;
use Ramsey\Uuid\Uuid;
class PersonService 
{
    public function createPerson(array $data): Person
    {   
        $data['id'] = Uuid::uuid4();
        $data['searchable'] = $data['apelido'] . ' ' . $data['nome'] . ' ' . json_encode($data['stack']);
        $person = Person::create($data);
        return $person;
    }
}