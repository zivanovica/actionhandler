<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/21/17
 * Time: 6:05 PM
 */

namespace Api\Models;

use Core\CoreUtils\DataFilter\Filters\IntFilter;
use Core\CoreUtils\Singleton;
use Core\Libs\Model\Model;

class Idea extends Unique
{

    use Singleton;

    /** @var User */
    private $_user;

    const STATUS_OPEN = 'OPEN';
    const STATUS_CONFIRMED = 'CONFIRMED';
    const STATUS_DECLINED = 'DECLINED';

    const MIN_DESCRIPTION_LENGTH = 20;
    const MAX_DESCRIPTION_LENGTH = 1600;

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

        return ['id', 'creator_id', 'idea_category', 'description', 'status'];
    }

    /**
     *
     * Retrieves user model associated to "creator_id"
     *
     * @return User|null
     */
    public function user(): ?User
    {

        if (false === $this->_user instanceof User) {

            $id = $this->getAttribute('creator_id', IntFilter::getSharedInstance());

            $this->_user = User::getSharedInstance()->find($id);
        }

        return $this->_user;
    }
}