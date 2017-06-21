<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/21/17
 * Time: 6:05 PM
 */

namespace Api\Models;

use Core\CoreUtils\Singleton;
use Core\Libs\Model\Model;

class Idea extends Model
{

    use Singleton;

    const STATUS_OPEN = 'OPEN';
    const STATUS_CONFIRMED = 'CONFIRMED';
    const STATUS_DECLINED = 'DECLINED';

    /**
     * @return string Table name
     */
    public function table(): string
    {

        return 'ideas';
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

        return ['id', 'idea_category', 'description', 'status'];
    }
}