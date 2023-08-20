<?php

declare(strict_types=1);

namespace App\Job;

use Hyperf\AsyncQueue\Job;
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
        $searchable =  $this->params['apelido'] . ' ' .  $this->params['nome'];
        if (is_array($this->params['stack'])) {
            $searchable .= implode(' ',  $this->params['stack']);
        }

        Db::statement("INSERT INTO person(id, apelido, nome, nascimento, stack, searchable) values (?, ?, ?, ?, ?, ?) ON CONFLICT DO NOTHING;", [
            $this->params['id'],
            $this->params['apelido'],
            $this->params['nome'],
            $this->params['nascimento'],
            json_encode($this->params['stack']),
            $searchable
        ]);
    }
}
