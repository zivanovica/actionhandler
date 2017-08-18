<?php

namespace Bulletproof\Models;

use RequestHandler\Modules\Model\Model;

class PostModel extends Model
{

    /**
     * @return string Table/collection name
     */
    public function table(): string
    {

        return 'posts';
    }

    /**
     *
     * List of all available fields (columns)
     *
     * @return array
     */
    public function fields(): array
    {

        return ['id', 'user_id', 'title', 'content', 'created_at', 'updated_at'];
    }
}