<?php

namespace Api\Models;

use RequestHandler\Modules\Model\Model;

class Wall extends Model
{

    /**
     *
     * Retrieve current model table name
     *
     * @return string Table name
     */
    public function table(): string
    {

        return 'user_wall';
    }

    /**
     *
     * Retrieve current model primary key identifier
     *
     * @return string Name of primary key field
     */
    public function primary(): string
    {

        return 'id';
    }

    /**
     *
     * Retrieve current model available fields (columns)
     *
     * @return array Fields (columns) of table
     */
    public function fields(): array
    {

        return [
            'id', 'user_id', 'value', ''
        ];
    }
}