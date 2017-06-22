<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/21/17
 * Time: 1:14 PM
 */

namespace Api\Models;


use Core\Libs\Model\Model;

class User extends Model
{

    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_INACTIVE = 'INACTIVE';
    const STATUS_BANNED = 'BANNED';

    public function table(): string
    {
        return 'users';
    }

    public function primary(): string
    {

        return 'id';
    }

    public function fields(): array
    {
        return ['id', 'email', 'password', 'code', 'status'];
    }
}