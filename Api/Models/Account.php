<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/22/17
 * Time: 12:57 PM
 */

namespace Api\Models;


use Core\Libs\Model\Model;

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