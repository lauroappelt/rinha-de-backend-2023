<?php
namespace App\Service;

use App\Exception\NotFoundException;
use App\Model\Person;
use Hyperf\Database\Exception\QueryException;
use Ramsey\Uuid\Uuid;
use Hyperf\DbConnection\Db;
use App\Exception\UniqueException;
use Hyperf\Context\ApplicationContext;
class PersonService
{   
    private $redisClient;

    public function __construct()
    {   
        $container = ApplicationContext::getContainer();
        $this->redisClient = $container->get(\Redis::class);
    }

    public function createPerson(array $data): Person
    {
        try {
            $data['id'] = Uuid::uuid4();

            if (is_array($data['stack'])) {
                $data['searchable'] = $data['apelido'] . ' ' . $data['nome'] . ' ' . implode(' ', $data['stack']);
            } else {
                $data['searchable'] = $data['apelido'] . ' ' . $data['nome'];
            }

            $person = Person::create($data);
            
            //cache
            $this->redisClient->set('person.' . $person->id, json_encode($person->toArray()));
            $this->redisClient->expire('person.' . $person->id, 90);

            return $person;
        } catch (QueryException $exception) {
            if ($exception->getCode() === '23505') {
                throw new UniqueException($exception->getMessage());
            }
        }
    }

    public function getPerson(String $id): Person
    {   
        $personCached =$this->redisClient->get('person.' . $id);
        if ($personCached) {
            $personCached =  (array) json_decode($personCached);
            return new Person($personCached);
        }

        $person = Person::find($id);
        if ($person == null) {
            throw new NotFoundException("Not Found");
        }
        
        return $person;
    }

    public function searchPerson(String $term)
    {   
        $result = Db::select("select id, apelido, nascimento, stack from person where to_tsvector('english'::regconfig, searchable) @@ plainto_tsquery('english'::regconfig, ?) limit 50;", [$term]);
        return $result;
    }
}