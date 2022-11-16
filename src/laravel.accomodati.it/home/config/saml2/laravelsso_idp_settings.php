<?php

// If you choose to use ENV vars to define these values, give this IdP its own env var names
// so you can define different values for each IdP, all starting with 'SAML2_'.$this_idp_env_id
$this_idp_env_id = 'LARAVELSSO';

//This is variable is for simplesaml example only.
// For real IdP, you must set the url values in the 'idp' config to conform to the IdP's real urls.
$idp_host = env('SAML2_'.$this_idp_env_id.'_IDP_HOST', 'https://idp.accomodati.it');

return $settings = array(

    /*****
     * One Login Settings
     */

    // If 'strict' is True, then the PHP Toolkit will reject unsigned
    // or unencrypted messages if it expects them signed or encrypted
    // Also will reject the messages if not strictly follow the SAML
    // standard: Destination, NameId, Conditions ... are validated too.
    'strict' => true, //@todo: make this depend on laravel config

    // Enable debug mode (to print errors)
    'debug' => env('APP_DEBUG', false),

    // Service Provider Data that we are deploying
    'sp' => array(

        // Specifies constraints on the name identifier to be used to
        // represent the requested subject.
        // Take a look on lib/Saml2/Constants.php to see the NameIdFormat supported
        'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',

        // Usually x509cert and privateKey of the SP are provided by files placed at
        // the certs folder. But we can also provide them with the following parameters
        'x509cert' => env('SAML2_'.$this_idp_env_id.'_SP_x509',''),
        'privateKey' => env('SAML2_'.$this_idp_env_id.'_SP_PRIVATEKEY',''),

        // Identifier (URI) of the SP entity.
        // Leave blank to use the '{idpName}_metadata' route, e.g. 'test_metadata'.
        'entityId' => env('SAML2_'.$this_idp_env_id.'_SP_ENTITYID','laravel-accomodati-it-sp'),

        // Specifies info about where and how the <AuthnResponse> message MUST be
        // returned to the requester, in this case our SP.
        'assertionConsumerService' => array(
            // URL Location where the <Response> from the IdP will be returned,
            // using HTTP-POST binding.
            // Leave blank to use the '{idpName}_acs' route, e.g. 'test_acs'
            'url' => '',
        ),
        // Specifies info about where and how the <Logout Response> message MUST be
        // returned to the requester, in this case our SP.
        // Remove this part to not include any URL Location in the metadata.
        'singleLogoutService' => array(
            // URL Location where the <Response> from the IdP will be returned,
            // using HTTP-Redirect binding.
            // Leave blank to use the '{idpName}_sls' route, e.g. 'test_sls'
            'url' => '',
        ),
    ),

    // Identity Provider Data that we want connect with our SP
    'idp' => array(
        // Identifier of the IdP entity  (must be a URI)
        'entityId' => env('SAML2_'.$this_idp_env_id.'_IDP_ENTITYID', $idp_host . '/saml2/idp/metadata.php'),
        // SSO endpoint info of the IdP. (Authentication Request protocol)
        'singleSignOnService' => array(
            // URL Target of the IdP where the SP will send the Authentication Request Message,
            // using HTTP-Redirect binding.
            'url' => env('SAML2_'.$this_idp_env_id.'_IDP_SSO_URL', $idp_host . '/saml2/idp/SSOService.php'),
        ),
        // SLO endpoint info of the IdP.
        'singleLogoutService' => array(
            // URL Location of the IdP where the SP will send the SLO Request,
            // using HTTP-Redirect binding.
            'url' => env('SAML2_'.$this_idp_env_id.'_IDP_SL_URL', $idp_host . '/saml2/idp/SingleLogoutService.php'),
        ),
        // Public x509 certificate of the IdP
        'x509cert' => env('SAML2_'.$this_idp_env_id.'_IDP_x509', 'MIIEEzCCAvugAwIBAgIUfl8AHF2jfMhoCCeqc+hcZHhEEc0wDQYJKoZIhvcNAQELBQAwgZgxCzAJBgNVBAYTAklUMQ4wDAYDVQQIDAVJdGFseTETMBEGA1UEBwwKTGVjY2UgKExFKTEXMBUGA1UECgwOSURQIEFjY29tb2RhdGkxDDAKBgNVBAsMA1NTTzEaMBgGA1UEAwwRaWRwLmFjY29tb2RhdGkuaXQxITAfBgkqhkiG9w0BCQEWEnRlY2hAYWNjb21vZGF0aS5pdDAeFw0yMjExMDkxNTIyNTdaFw0zMjExMDYxNTIyNTdaMIGYMQswCQYDVQQGEwJJVDEOMAwGA1UECAwFSXRhbHkxEzARBgNVBAcMCkxlY2NlIChMRSkxFzAVBgNVBAoMDklEUCBBY2NvbW9kYXRpMQwwCgYDVQQLDANTU08xGjAYBgNVBAMMEWlkcC5hY2NvbW9kYXRpLml0MSEwHwYJKoZIhvcNAQkBFhJ0ZWNoQGFjY29tb2RhdGkuaXQwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDcSuXdQ/1o4MAPfmONwQDCdajHnNvhJLb7TI+DwmhgGg5JXaxqUgVX9TIUTVvv0gkM1ch91qHibkgOroFdnnoeWHJ3Qlznei6RU5aXt8VV8XTJmCvXdsX6RaHzCXvArBRBBnvwRlSO9ZlsML4doTHOFaE1ceDdh+YlN5IR007S319LYF7v73zkrYyBe9RYyjMoBSWBE9A4hhhNA0HriETSbqrAtr+icYNlRc9QP7R+qLv6HT+ro9i5S+gKB/BWrlujhoPcN5ZNqqLJ/kARDQ5oipW4gEQQlmxo2xuXKKuTuK2DmCPheE0jKS2xPr6mLTuKG+LPuK4YFk0TQdr0tQ1DAgMBAAGjUzBRMB0GA1UdDgQWBBSStCljqgkS4P7CpiMoylJ2O8uU7TAfBgNVHSMEGDAWgBSStCljqgkS4P7CpiMoylJ2O8uU7TAPBgNVHRMBAf8EBTADAQH/MA0GCSqGSIb3DQEBCwUAA4IBAQA0yNnCbN399bquThmGvefon3xzbskZTLhtai5VUNdsOBeyXYKFbyMRoq4O5Am04yYIaIPjkiQ8Pbtla2Nk4r7sT9rgG/wsy3dhcwMz865yRGYw5qNu+4Ayid6JdCI53vWSpahK2UrqZJzMeCcH605EXKVTSDojbJpDe9NCKumstnfU2m34sabEHMdH4+aGFm/kCqg4xcqf6LW7UD/WzwquX2Gxo4NJ7br7mkU4+l3EHdR13B9HObcNzzPg6gYqzBrlxY6TOT0m4ZerZaIDwKbedYh/B2FbMO1a9AI7iEWSxXNROAkXQ9V3vYb3yJpODTsqlDW9XesQZaMZntq9UlFJ'),
        /*
         *  Instead of use the whole x509cert you can use a fingerprint
         *  (openssl x509 -noout -fingerprint -in "idp.crt" to generate it)
         */
        // 'certFingerprint' => '',
    ),



    /***
     *
     *  OneLogin advanced settings
     *
     *
     */
    // Security settings
    'security' => array(

        /** signatures and encryptions offered */

        // Indicates that the nameID of the <samlp:logoutRequest> sent by this SP
        // will be encrypted.
        'nameIdEncrypted' => false,

        // Indicates whether the <samlp:AuthnRequest> messages sent by this SP
        // will be signed.              [The Metadata of the SP will offer this info]
        'authnRequestsSigned' => false,

        // Indicates whether the <samlp:logoutRequest> messages sent by this SP
        // will be signed.
        'logoutRequestSigned' => false,

        // Indicates whether the <samlp:logoutResponse> messages sent by this SP
        // will be signed.
        'logoutResponseSigned' => false,

        /* Sign the Metadata
         False || True (use sp certs) || array (
                                                    keyFileName => 'metadata.key',
                                                    certFileName => 'metadata.crt'
                                                )
        */
        'signMetadata' => false,


        /** signatures and encryptions required **/

        // Indicates a requirement for the <samlp:Response>, <samlp:LogoutRequest> and
        // <samlp:LogoutResponse> elements received by this SP to be signed.
        'wantMessagesSigned' => false,

        // Indicates a requirement for the <saml:Assertion> elements received by
        // this SP to be signed.        [The Metadata of the SP will offer this info]
        'wantAssertionsSigned' => false,

        // Indicates a requirement for the NameID received by
        // this SP to be encrypted.
        'wantNameIdEncrypted' => false,

        // Authentication context.
        // Set to false and no AuthContext will be sent in the AuthNRequest,
        // Set true or don't present thi parameter and you will get an AuthContext 'exact' 'urn:oasis:names:tc:SAML:2.0:ac:classes:PasswordProtectedTransport'
        // Set an array with the possible auth context values: array ('urn:oasis:names:tc:SAML:2.0:ac:classes:Password', 'urn:oasis:names:tc:SAML:2.0:ac:classes:X509'),
        'requestedAuthnContext' => true,
    ),

    // Contact information template, it is recommended to suply a technical and support contacts
    'contactPerson' => array(
        'technical' => array(
            'givenName' => 'Laravel Jetstream',
            'emailAddress' => 'laravel@accomodati.it'
        ),
        'support' => array(
            'givenName' => 'Laravel Jetstream',
            'emailAddress' => 'laravel@accomodati.it'
        ),
    ),

    // Organization information template, the info in en_US lang is recomended, add more if required
    'organization' => array(
        'en-US' => array(
            'name' => 'Laravel Jetstream',
            'displayname' => 'Laravel Jetstream',
            'url' => 'https://laravel.accomodati.it/'
        ),
        'it-IT' => array(
            'name' => 'Laravel Jetstream',
            'displayname' => 'Laravel Jetstream',
            'url' => 'https://laravel.accomodati.it/'
        ),        
    ),

/* Interoperable SAML 2.0 Web Browser SSO Profile [saml2int]   http://saml2int.org/profile/current

   'authnRequestsSigned' => false,    // SP SHOULD NOT sign the <samlp:AuthnRequest>,
                                      // MUST NOT assume that the IdP validates the sign
   'wantAssertionsSigned' => true,
   'wantAssertionsEncrypted' => true, // MUST be enabled if SSL/HTTPs is disabled
   'wantNameIdEncrypted' => false,
*/

);
