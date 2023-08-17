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
    private $personQueue;

    public function __construct(PersonQueueService $personQueue)
    {   
        $container = ApplicationContext::getContainer();
        $this->redisClient = $container->get(\Redis::class);

        $this->personQueue = $personQueue;
    }

    public function createPerson(array $data): Person
    {
        try {
            $data['id'] = Uuid::uuid4();
            $person = new Person($data);
            $personKey = 'person.' . $person->id;

            //cache
            $this->redisClient->set($personKey, json_encode($data));
            $this->redisClient->expire($personKey, 180);

            //queue
            $this->personQueue->push($data);

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

    public function countPerson()
    {
        $count = Db::table('person')->count();
        return $count;
    }
}