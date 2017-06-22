<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/22/17
 * Time: 3:42 PM
 */

namespace Api\Models;


use Core\Libs\Model\Model;

class Token extends Model
{

    const DEFAULT_EXPIRE_TIMEOUT = 3600;

    /**
     * @return string Table name
     */
    public function table(): string
    {

        return 'tokens';
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

        return ['id', 'user_id', 'token', 'updated_at'];
    }

    public function isValid(): bool
    {

    }
}