<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property string $id 
 * @property string $apelido 
 * @property string $nome 
 */
class Person extends Model
{   
    public bool $timestamps = false;
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'person';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'apelido', 'nome', 'stack', 'searchable'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['stack' => 'array'];

    protected String $keyType = 'string';
}
