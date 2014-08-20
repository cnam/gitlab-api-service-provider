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
                'private_token' => 'YOU_PRIVATE_TOKEN'
            )
        );

        $app['gitlab_api'] = function ($app) {
            return new Api(array(
                "base_url" => $app['gitlab_api.base_url'],
                'request.options' => array(
                    "verify" => false,
                    "auth" => $app['gitlab_api.request_options']['auth'],
                    "query" => array(
                        'private_token' => $app['gitlab_api.request_options']['private_token']
                    )
                ))
            );
        };
    }
}