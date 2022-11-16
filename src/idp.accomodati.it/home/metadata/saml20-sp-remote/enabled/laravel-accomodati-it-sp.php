<?php

$metadata['laravel-accomodati-it-sp'] = [
    'entityid' => 'laravel-accomodati-it-sp',
    'description' => [
        'en-US' => 'Laravel Jetstream',
        'it-IT' => 'Laravel Jetstream',
    ],
    'OrganizationName' => [
        'en-US' => 'Laravel Jetstream',
        'it-IT' => 'Laravel Jetstream',
    ],
    'name' => [
        'en-US' => 'Laravel Jetstream',
        'it-IT' => 'Laravel Jetstream',
    ],
    'OrganizationDisplayName' => [
        'en-US' => 'Laravel Jetstream',
        'it-IT' => 'Laravel Jetstream',
    ],
    'url' => [
        'en-US' => 'https://laravel.accomodati.it/',
        'it-IT' => 'https://laravel.accomodati.it/',
    ],
    'OrganizationURL' => [
        'en-US' => 'https://laravel.accomodati.it/',
        'it-IT' => 'https://laravel.accomodati.it/',
    ],
    'contacts' => [
        [
            'contactType' => 'technical',
            'givenName' => 'Laravel Jetstream',
            'emailAddress' => [
                'laravel@accomodati.it',
            ],
        ],
        [
            'contactType' => 'support',
            'givenName' => 'Laravel Jetstream',
            'emailAddress' => [
                'laravel@accomodati.it',
            ],
        ],
    ],
    'metadata-set' => 'saml20-sp-remote',
    'expire' => 1984151222,
    'AssertionConsumerService' => [
        [
            'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
            'Location' => 'https://laravel.accomodati.it/saml2/laravelsso/acs',
            'index' => 1,
        ],
    ],
    'SingleLogoutService' => [
        [
            'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            'Location' => 'https://laravel.accomodati.it/saml2/laravelsso/sls',
        ],
    ],
    'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
    'validate.authnrequest' => false,
    'saml20.sign.assertion' => false,
];
