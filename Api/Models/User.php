<?php

namespace Api\Models;

use RequestHandler\Utils\DataFilter\Filters\IntFilter;
use RequestHandler\Utils\Singleton;
use RequestHandler\Modules\Model\Model;

class User extends Model
{

    use Singleton;

    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_INACTIVE = 'INACTIVE';
    const STATUS_BANNED = 'BANNED';

    protected $_hidden = ['password'];

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
     * @param int $permission
     * @return User|null
     */
    public function createProfile(
        string $email, string $password, string $firstName, string $lastName, int $permission = UserRole::ROLE_USER
    ): ?User
    {
        $user = User::getNewInstance([
            'email' => $email, 'password' => password_hash($password, PASSWORD_BCRYPT),
            'code' => hash('sha256', uniqid('', true)), 'status' => User::STATUS_INACTIVE
        ]);

        if (0 === $user->save()) {

            return null;
        }

        $account = $this->_createNewUserAccount($user, $firstName, $lastName);

        if (null === $account) {

            $user->delete();

            return null;
        }

        if (0 === UserRole::getNewInstance()->setRole($user, $permission)->save()) {

            $user->delete();

            $account->delete();

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

        $id = $this->getAttribute('id', IntFilter::getSharedInstance());

        return Account::getSharedInstance()->findOneWhere(['user_id' => $id]);
    }

    /**
     * @return UserRole
     */
    public function getRole(): UserRole
    {

        $id = $this->getAttribute('id', IntFilter::getSharedInstance());

        return UserRole::getSharedInstance()->findOneWhere(['user_id' => $id]);
    }

    /**
     *
     * Creates new account entity
     *
     * @param User $user
     * @param string $firstName
     * @param string $lastName
     * @return Account|null
     */
    private function _createNewUserAccount(User $user, string $firstName, string $lastName): ?Account
    {
        $account = Account::getNewInstance([
            'user_id' => $user,
            'first_name' => $firstName,
            'last_name' => $lastName
        ]);

        if (0 === $account->save()) {

            return null;
        }

        return $account;
    }
}