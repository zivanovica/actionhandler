<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/21/17
 * Time: 1:14 PM
 */

namespace Api\Models;


use Core\CoreUtils\DataTransformer\Transformers\IntTransformer;
use Core\CoreUtils\Singleton;
use Core\Libs\Model\Model;

class User extends Model
{

    use Singleton;

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

    /**
     *
     * Creates user and its account
     *
     * @param string $email
     * @param string $password
     * @param string $firstName
     * @param string $lastName
     * @return User|null
     */
    public function createProfile(string $email, string $password, string $firstName, string $lastName): ?User
    {
        $user = User::getNewInstance([
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'code' => hash('sha256', uniqid('', true)),
            'status' => User::STATUS_INACTIVE
        ]);

        if (0 === $user->save()) {

            return null;
        }

        $account = Account::getNewInstance([
            'user_id' => $user,
            'first_name' => $firstName,
            'last_name' => $lastName
        ]);

        if (0 === $account->save()) {

            $user->delete();

            return null;
        }

        return $user;
    }

    /**
     *
     * Retrieves user with given email if credentials match
     *
     * @param string $email
     * @param string $password
     * @return User|null
     */
    public function getUserByEmailAndPassword(string $email, string $password): ?User
    {

        /** @var User $user */
        $user = $this->findOneWhere(['email' => $email]);

        if (false === $user instanceof User) {

            return null;
        }

        if (false === password_verify($password, $user->getAttribute('password'))) {

            return null;
        }

        return $user;
    }

    /**
     *
     * Get account associated to current user
     *
     * @return Account
     */
    public function getAccount(): Account
    {

        $id = $this->getAttribute('id', IntTransformer::getSharedInstance());

        return Account::getSharedInstance()->findOneWhere(['user_id' => $id]);
    }
}