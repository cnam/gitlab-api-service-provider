<?php

namespace Silex\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Gitlab\Api;

class GitlabApiSilexProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['gitlab_api.default_options'] = array(
            "verify" => false,
            'auth' => array('user', 'pass'),
            'query' => array(
                'private_token' => 'You_Private_token'
            )
        );

        $app['gitlab_api'] = function ($app) {

            $token = $app['security']->getToken();
            $private_token = $app['gitlab_api.options']['request_options']['private_token'];

            if (null !== $token) {
                $user = $token->getUser();
                if (method_exists($user, 'getCredential')) {
                    $tokens = $user->getCredential('gitlab');
                }
            }

            return new Api(array(
                    "base_url" => $app['gitlab_api.options']['base_url'],
                    'request.options' => array(
                        "verify" => false,
                        "auth" => $app['gitlab_api.options']['request_options']['auth'],
                        "query" => array(
                            'private_token' =>  isset($tokens['private_access_token']) ? $tokens['private_access_token'] : $private_token
                        )
                    ),
                    'oauth2' => !empty($app['gitlab_api.options']['oauth2']) ? $app['gitlab_api.options']['oauth2'] : [],
                )
            );
        };
    }
}