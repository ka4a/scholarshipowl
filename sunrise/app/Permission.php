<?php namespace App;

class Permission
{

    const SCHOLARSHIPS = 'scholarships';
    const SCHOLARSHIPS_CREATE   = self::SCHOLARSHIPS.'.create';
    const SCHOLARSHIPS_SEARCH   = self::SCHOLARSHIPS.'.search';
    const SCHOLARSHIPS_VIEW     = self::SCHOLARSHIPS.'.view';

    const ACL = 'acl';
    const ACL_USERS = 'acl.users';
    const ACL_ROLES = 'acl.roles';

    /**
     * @return array
     */
    public static function list()
    {
        return [
            static::ACL => [
                'name' => 'Access Limiter',
                'children' => [
                    static::ACL_USERS => [
                        'name' => 'Manage user roles.'
                    ],
                    static::ACL_ROLES => [
                        'name' => 'Manage roles.'
                    ],
                ]
            ],
            static::SCHOLARSHIPS => [
                'name' => 'Scholarships',
                'children' => [
                    static::SCHOLARSHIPS_VIEW => [
                        'name' => 'Scholarships view.',
                    ],
                    static::SCHOLARSHIPS_SEARCH => [
                        'name' => 'Scholarships search.',
                    ],
                    static::SCHOLARSHIPS_CREATE => [
                        'name' => 'Scholarships create.',
                    ],
                ]
            ]
        ];
    }
}
