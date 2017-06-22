<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/22/17
 * Time: 3:42 PM
 */

namespace Api\Models;


use Core\CoreUtils\DataTransformer\Transformers\IntTransformer;
use Core\CoreUtils\Singleton;
use Core\Libs\Model\Model;

class Token extends Model
{

    use Singleton;

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

        return ['id', 'user_id', 'value', 'updated_at'];
    }

    /**
     *
     * Creates new token for user
     *
     * @param User $user
     * @return Token|null
     */
    public function create(User $user): ?Token
    {

        $token = Token::getNewInstance([
            'user_id' => $user,
            'value' => hash('sha256', uniqid('', true)),
            'updated_at' => time()
        ]);

        if ($token->save()) {

            return $token;
        }

        return null;
    }

    /**
     *
     * Validates toke expire time
     *
     * @return bool
     */
    public function hasExpired(): bool
    {

        return time() - $this->getAttribute('updated_at', IntTransformer::getSharedInstance()) < Token::DEFAULT_EXPIRE_TIMEOUT;
    }

    /**
     * Refresh token
     *
     * @return bool TRUE on success otherwise FALSE
     */
    public function refresh(): bool
    {

        $this->setAttribute('updated_at', time());

        return (bool)$this->save();
    }
}