<?php

namespace Api\Models;

use Core\Libs\Model\Model;

class UserRole extends Model
{

    private const PERMISSION_GUEST = 1;
    private const PERMISSION_USER = 2;
    private const PERMISSION_MODERATOR = 4;
    private const PERMISSION_SUPER_USER = 8;

    const ROLE_GUEST = UserRole::PERMISSION_GUEST;
    const ROLE_USER = UserRole::ROLE_GUEST | UserRole::PERMISSION_USER;
    const ROLE_MODERATOR = UserRole::ROLE_USER | UserRole::PERMISSION_MODERATOR;
    const ROLE_SUPER_USER = UserRole::ROLE_MODERATOR | UserRole::PERMISSION_SUPER_USER;

    /**
     *
     * Retrieve current model table name
     *
     * @return string Table name
     */
    public function table(): string
    {

        return 'user_roles';
    }

    /**
     *
     * Retrieve current model primary key identifier
     *
     * @return string Name of primary key field
     */
    public function primary(): string
    {

        return 'id';
    }

    /**
     *
     * Retrieve current model available fields (columns)
     *
     * @return array Fields (columns) of table
     */
    public function fields(): array
    {

        return ['id', 'permission', 'user_id'];
    }

    /**
     * @param User $user
     * @param int $permissions
     * @return UserRole
     */
    public function setRole(User $user, int $permissions): UserRole
    {

        $this->setAttributes([
            'user_id' => $user,
            'permission' => $permissions
        ]);

        return $this;
    }
}