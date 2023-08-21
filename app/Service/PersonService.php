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

    public function createPerson(array $data)
    {
        $nickCached = $this->redisClient->get($data['apelido']);
        if ($nickCached) {
            throw new UniqueException("Unique nick violation");
        }

        $this->redisClient->set($data['apelido'], '1');

        $data['id'] = Uuid::uuid4();
        $personKey = 'person.' . $data['id'];

        //cache
        $this->redisClient->set($personKey, json_encode($data));
        $searchable =  $data['apelido'] . ' ' .  $data['nome'];
        if (is_array($data['stack'])) {
            $searchable .= implode(' ',  $data['stack']);
        }
        $this->redisClient->hset('persons', strtolower($searchable), json_encode($data));

        //queue
        $this->personQueue->push($data);

        return $data;
    }

    public function getPerson(String $id)
    {   
        $personCached =$this->redisClient->get('person.' . $id);
        if ($personCached) {
            return json_decode($personCached);
        }
        
        throw new NotFoundException("Not Found");
    }

    public function searchPerson(String $term)
    {   
        $cursor = null;
        $elements = $this->redisClient->hscan('persons', $cursor, '*' . strtolower($term) . '*', 50);
        $find = [];
        foreach ($elements as $element) {
            $find[] = json_decode($element);
        }
        return $find;
    }

    public function countPerson()
    {
        $count = Db::table('person')->count();
        return $count;
    }
}