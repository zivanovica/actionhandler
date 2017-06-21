<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/21/17
 * Time: 1:14 PM
 */

namespace Api\Models;


use Core\Libs\Model\AModel;

class UserModel extends AModel
{

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
        return ['id', 'email', 'password', 'status', 'code', 'remember_token', 'created_at', 'updated_at'];
    }
}