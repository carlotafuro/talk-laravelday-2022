<?php

/**
 * SAML 2.0 remote SP metadata for SimpleSAMLphp.
 *
 * See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-sp-remote
 */


$enabled_sp_remote_dir = __DIR__ . '/saml20-sp-remote/enabled/';

$enabled_sp_remote_array = scandir($enabled_sp_remote_dir, SCANDIR_SORT_ASCENDING);

foreach($enabled_sp_remote_array as $enabled_sp_remote_file) {

	if ( substr($enabled_sp_remote_file, -4) == '.php') { // escludo le voci '.' e '..'

		require_once($enabled_sp_remote_dir . $enabled_sp_remote_file);
	}
}
