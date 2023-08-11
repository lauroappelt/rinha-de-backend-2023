<?php

namespace App\Resource;

use Hyperf\Resource\Json\JsonResource;

class PersonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'apelido' => $this->apelido,
            'nome' => $this->nome,
            'stack' => $this->stack,
        ];
    }
}
