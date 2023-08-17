<?php

declare(strict_types=1);

namespace App\Job;

use Hyperf\AsyncQueue\Job;
use App\Model\Person;
use Hyperf\Database\Exception\QueryException;
use Hyperf\Context\ApplicationContext;
use Hyperf\DbConnection\Db;

class PersonJob extends Job
{
    public $params;

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function handle()
    {
        if (is_array($this->params['stack'])) {
            $data['searchable'] = $data['apelido'] . ' ' . $data['nome'] . ' ' . implode(' ', $data['stack']);
        } else {
            $data['searchable'] = $data['apelido'] . ' ' . $data['nome'];
        }

        Db::statement("INSERT INTO person(id, apelido, nome, nascimento, stack, searchable) values (?, ?, ?, ?, ?, ?) ON CONFLICT DO NOTHING;", [
            $this->params['id'],
            $this->params['apelido'],
            $this->params['nome'],
            $this->params['nascimento'],
            json_encode($this->params['stack']),
            $data['searchable']
        ]);
    }
}
