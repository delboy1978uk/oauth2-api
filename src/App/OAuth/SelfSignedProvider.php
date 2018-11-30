<?php

namespace App\OAuth;

use League\OAuth2\Client\Provider\GenericProvider;

class SelfSignedProvider extends GenericProvider
{
    /**
     * @param array $options
     * @return array
     */
    protected function getAllowedClientOptions(array $options)
    {
        return [ 'timeout', 'proxy', 'verify' ];
    }
}