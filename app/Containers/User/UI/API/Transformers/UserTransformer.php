<?php

namespace App\Containers\User\UI\API\Transformers;

use App\Containers\Authorization\UI\API\Transformers\RoleTransformer;
use App\Containers\User\Models\User;
use App\Ship\Parents\Transformers\Transformer;
use Config;

/**
 * Class UserTransformer.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class UserTransformer extends Transformer
{

    /**
     * @var  array
     */
    protected $defaultIncludes = [
        'roles',
    ];

    /**
     * @param \App\Containers\User\Models\User $user
     *
     * @return array
     */
    public function transform(User $user)
    {
        $response = [
            'object'               => 'User',
            'id'                   => $user->getHashedKey(),
            'name'                 => $user->name,
            'email'                => $user->email,
            'confirmed'            => $user->confirmed,
            'nickname'             => $user->nickname,
            'gender'               => $user->gender,
            'birth'                => $user->birth,
            'social_auth_provider' => $user->social_provider,
            'social_id'            => $user->social_id,
            'social_avatar'        => [
                'avatar'   => $user->social_avatar,
                'original' => $user->social_avatar_original,
            ],
            'created_at'           => $user->created_at,
            'updated_at'           => $user->updated_at,
            'token'                => $this->transformToken($user->access_token),
        ];

        // TODO: uncomment this and  fix ifAdmin
//        $response = $this->ifAdmin([
//            'real_id'    => $user->id,
//            'deleted_at' => $user->deleted_at,
//        ], $response);

        return $response;
    }

    /**
     * TODO: remove from here
     *
     * @param null $token
     *
     * @return  array
     */
    private function transformToken($token = null)
    {
        return !$token ? null : [
            'object'       => 'Token',
            'access_token' => [
                'token_type'   => 'Bearer',
                'value'        => $token,
//                'expires_in'   => '...',
            ],
        ];
    }

    public function includeRoles(User $user)
    {
        return $this->collection($user->roles, new RoleTransformer());
    }
}
