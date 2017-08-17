<?php

namespace Api\Models;

use RequestHandler\Modules\Model\Model;

class Account extends Model
{

    /**
     * @return string Table name
     */
    public function table(): string
    {

        return 'accounts';
    }

    /**
     * @return string Name of primary key field
     */
    public function primary(): string
    {

        return 'id';
    }

    /**
     * @return array Fields (columns) of table
     */
    public function fields(): array
    {

        return ['id', 'user_id', 'first_name', 'last_name'];
    }
}