<?php
namespace App\Service;

use App\Exception\NotFoundException;
use App\Model\Person;
use Hyperf\Database\Exception\QueryException;
use Ramsey\Uuid\Uuid;
use Hyperf\DbConnection\Db;
use App\Exception\UniqueException;
class PersonService
{
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
            
            return $person;
        } catch (QueryException $exception) {
            if ($exception->getCode() === '23505') {
                throw new UniqueException($exception->getMessage());
            }
        }
    }

    public function getPerson(String $id): Person
    {
        $person = Person::find($id);
        if ($person == null) {
            throw new NotFoundException("Not Found");
        }
        
        return $person;
    }

    public function searchPerson(String $term)
    {   

        // $persons = Db::table('person')
        //     ->whereRaw("searchable like ?", [strtolower('%' . $term . '%')])
        //     ->limit(50)
        //     ->get(['id', 'apelido', 'nome', 'nascimento']);

        $result = Db::select("select id, apelido, nascimento, stack from person where to_tsvector('english'::regconfig, searchable) @@ plainto_tsquery('english'::regconfig, ?) limit 50;", [$term]);
        return $result;
    }
}