<?php

namespace SimpleSAML\Module\laravelAuth\Auth\Source;

use Exception;
use SimpleSAML\Error;
use SimpleSAML\Logger;

//
//   Remote endpoint example response:
//
//   {
//       "result": false,
//       "error": "user auth error",
//       "user_data": {}
//   }
//
//   {
//       "result": true,
//       "error": "",
//       "user_data": {
//         "id": 11,
//         "name": "Sabryna Wolf",
//         "first_name": "Sabryna",
//         "last_name": "Wolf",
//         "city": "Milano",
//         "country": "Italia",
//         "address": "via pinco pallo",
//         "postcode": "20125",
//         "company": "Reinger",
//         "vat": "-",
//         "email": "wolf.sabryna@example.org",
//         "email_verified_at": null,
//         "two_factor_confirmed_at": null,
//         "current_team_id": 11,
//         "profile_photo_path": null,
//         "created_at": "2022-11-09T05:43:11.000000Z",
//         "updated_at": "2022-11-09T05:48:05.000000Z",
//         "profile_photo_url": "https://ui-avatars.com/api/?name=W+S&color=7F9CF5&background=EBF4FF"
//       }
//   }
//

class Remote extends \SimpleSAML\Module\core\Auth\UserPassBase
{

    private $endpoit_url;

    private $basic_auth_username;

    private $basic_auth_password;

    /**
     * Constructor for this authentication source.
     *
     * @param array $info  Information about this authentication source.
     * @param array $config  Configuration.
     */
    public function __construct($info, $config)
    {
        assert(is_array($info));
        assert(is_array($config));

        // Call the parent constructor first, as required by the interface
        parent::__construct($info, $config);

        // Make sure that all required parameters are present.
        foreach (['endpoit_url', 'basic_auth_username', 'basic_auth_password'] as $param) {

            if (!array_key_exists($param, $config)) {
                throw new Exception('laravelAuth: ' . $this->authId . ' - Missing required attribute "' . $param . '" for authentication source');
            }

            if (!is_string($config[$param])) {
                throw new Exception('laravelAuth: ' . $this->authId . ' - Expected parameter "' . $param . '" to be a string, instead it was: ' . gettype($config[$param]));
            }
        }

        $this->endpoit_url = $config['endpoit_url'];
        $this->basic_auth_username = $config['basic_auth_username'];
        $this->basic_auth_password = $config['basic_auth_password'];
    }

    /**
     * Attempt to log in using the given username and password.
     *
     * On a successful login, this function should return the users attributes. On failure,
     * it should throw an exception. If the error was caused by the user entering the wrong
     * username or password, a \SimpleSAML\Error\Error('WRONGUSERPASS') should be thrown.
     *
     * Note that both the username and the password are UTF-8 encoded.
     *
     * @param string $username  The username the user wrote.
     * @param string $password  The password the user wrote.
     * @return array  Associative array with the users attributes.
     */
    protected function login($username, $password)
    {
        assert(is_string($username));
        assert(is_string($password));

        $raw_data = json_encode(['username' => $username, 'password' => $password]);

        $http_headers = implode("\r\n", array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic ' . base64_encode($this->basic_auth_username . ':' . $this->basic_auth_password),
        ));

        $http_contex = stream_context_create(array(
            'http' => array(
                'method'  => 'POST',
                'content' => $raw_data,
                'header'  => $http_headers,
            ),
        ));

        try {

            $response = @file_get_contents($this->endpoit_url, false, $http_contex);

            if ( isset($http_response_header) and is_array($http_response_header)) {

                foreach ($http_response_header as $current_http_response_header) {

                    // mi aspetto l'intestazione di risposta: HTTP/1.1 200 OK
                    if (preg_match('|^HTTP\/|i', $current_http_response_header) and strpos($current_http_response_header, '200') === false) {
                        throw new Exception('laravelAuth: ' . $this->authId . ' - Errore nella risposta HTTP da endpoint remoto: ' . $current_http_response_header);
                    }
                }
            }

            if (!$response) {
                throw new Exception('laravelAuth: ' . $this->authId . ' - Errore nella risposta da endpoint remoto');
            }

            $response_obj = json_decode($response);

            if (is_null($response_obj)) {
                throw new Exception('laravelAuth: ' . $this->authId . ' - Errore nel formato della risposta da endpoint remoto');
            }

            if (!$response_obj->result) {
                // No data returned - invalid username/password
                Logger::error('laravelAuth: ' . $this->authId . ' - Errore da endpoint: ' . $response_obj->error . ' - Username: ' . $username);
                throw new Error\Error('WRONGUSERPASS');
            }

            //
            // Extract attributes. We allow the resultset to consist of multiple rows. Attributes
            // which are present in more than one row will become multivalued. null values and
            // duplicate values will be skipped. All values will be converted to strings.
            //
            $attributes = [];

            foreach ( (array)$response_obj->user_data as $name => $value ) {

                if ($value === null) {
                    continue;
                }

                $value = (string) $value;

                if (!array_key_exists($name, $attributes)) {
                    $attributes[$name] = [];
                }

                if (in_array($value, $attributes[$name], true)) {
                    // Value already exists in attribute
                    continue;
                }

                $attributes[$name][] = $value;
            }

            Logger::info('laravelAuth: ' . $this->authId . ' - Attributes: ' . implode(',', array_keys($attributes)));

            return $attributes;

        } catch (PDOException $e) {

            throw new Exception('laravelAuth: ' . $this->authId . ' - Errore nella connessione con endpoint remoto: ' . $e->getMessage());
        }
    }
}
