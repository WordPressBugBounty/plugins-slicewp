<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Returns all defined encryption keys, keyed by version string (e.g. 'v1', 'v3').
 * Discovers keys by scanning get_defined_constants() for SLICEWP_ENCRYPTION_KEY_V{n} constants.
 * Sorted by version number ascending so the last entry is always the highest-numbered version.
 *
 * @return array
 *
 */
function slicewp_get_encryption_keys() {

	$keys       = array();
	$prefix     = 'SLICEWP_ENCRYPTION_KEY_V';
	$prefix_len = strlen( $prefix );

	foreach ( get_defined_constants() as $name => $value ) {

		if ( strpos( $name, $prefix ) !== 0 ) {
			continue;
		}

		$suffix = substr( $name, $prefix_len );

		if ( ! ctype_digit( $suffix ) || (int) $suffix < 1 ) {
			continue;
		}

		$raw = base64_decode( $value );

		if ( strlen( $raw ) === SODIUM_CRYPTO_SECRETBOX_KEYBYTES ) {
			$keys[ 'v' . (int) $suffix ] = $raw;
		}

	}

	ksort( $keys, SORT_NATURAL );

	return $keys;

}


/**
 * Returns true if at least one valid SLICEWP_ENCRYPTION_KEY_V{n} constant is defined.
 *
 * @return bool
 *
 */
function slicewp_is_encryption_key_configured() {

	return ! empty( slicewp_get_encryption_keys() );

}


/**
 * Returns the version string of the current (highest-numbered) encryption key, e.g. 'v1'.
 * Returns false if no keys are configured.
 *
 * @return string|false
 *
 */
function slicewp_get_current_encryption_version() {

	$keys = slicewp_get_encryption_keys();

	if ( empty( $keys ) ) {
		return false;
	}

	return array_key_last( $keys );

}


/**
 * Encrypts a plaintext string using the current (highest-numbered) key.
 * Returns a versioned string in the format: v{n}:base64(nonce . ciphertext)
 * Returns false if no encryption key is configured — does NOT throw.
 *
 * @param string $plaintext
 *
 * @return string|false
 *
 */
function slicewp_encrypt( $plaintext ) {

	$keys = slicewp_get_encryption_keys();

	if ( empty( $keys ) ) {
		_doing_it_wrong( __FUNCTION__, 'No valid SliceWP encryption key is configured. Define SLICEWP_ENCRYPTION_KEY_V1 in wp-config.php or a must-use plugin.', '1.2.10' );
		return false;
	}

	$version = array_key_last( $keys );
	$key     = $keys[ $version ];
	$nonce = random_bytes( SODIUM_CRYPTO_SECRETBOX_NONCEBYTES );

	$ciphertext = sodium_crypto_secretbox( $plaintext, $nonce, $key );

	sodium_memzero( $key );

	return $version . ':' . base64_encode( $nonce . $ciphertext );

}


/**
 * Decrypts a versioned encrypted string produced by slicewp_encrypt().
 * Returns the original plaintext, false on failure.
 * Strings without a version prefix are treated as legacy plain text and returned as-is.
 *
 * @param string $encrypted
 *
 * @return string|false
 *
 */
function slicewp_decrypt( $encrypted ) {

	if ( empty( $encrypted ) ) {
		return $encrypted;
	}

	// Detect version prefix: v{n}:...
	if ( ! preg_match( '/^(v\d+):(.+)$/', $encrypted, $matches ) ) {
		return $encrypted;
	}

	$version = $matches[1];
	$encoded = $matches[2];
	$keys    = slicewp_get_encryption_keys();

	if ( empty( $keys[ $version ] ) ) {
		return false;
	}

	$key  = $keys[ $version ];
	$blob = base64_decode( $encoded );

	if ( strlen( $blob ) <= SODIUM_CRYPTO_SECRETBOX_NONCEBYTES ) {
		sodium_memzero( $key );
		return false;
	}

	$nonce      = substr( $blob, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES );
	$ciphertext = substr( $blob, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES );

	$plaintext = sodium_crypto_secretbox_open( $ciphertext, $nonce, $key );

	sodium_memzero( $key );

	return $plaintext;

}