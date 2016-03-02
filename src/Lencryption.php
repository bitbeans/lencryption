<?php namespace Bitbeans\Lencryption;

use RuntimeException;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\EncryptException;
use \ParagonIE\ConstantTime\Encoding;

use Config;

class Lencryption {
	
	/**
	* The encryption key.
	*
	* @var string
	*/
	protected $_key;

	public function __construct( $config = array() )
	{
		if (!extension_loaded("libsodium")) {
			throw new RuntimeException('Missing libsodium extension.');
		}

		$this->_key = base64_decode((isset($config['key']) && !empty($config['key'])) ? $key : config('lencryption.KEY'));

		if (!$this->_key)
		{
			throw new RuntimeException('Missing KEY.');
		}
	}

	 /**
	 * Generate a new random key.
	 *
	 * @return string
	 *
	 * @throws \Illuminate\Contracts\Encryption\EncryptException
	 */
	public function generateKey() {
		return base64_encode(\Sodium\randombytes_buf(\Sodium\CRYPTO_SECRETBOX_KEYBYTES));
	}

	 /**
	 * Encrypt the given value.
	 *
	 * @param  string  $value
	 * @return string
	 *
	 * @throws \Illuminate\Contracts\Encryption\EncryptException
	 */
	public function encrypt($value)
	{
		$nonce = \Sodium\randombytes_buf(\Sodium\CRYPTO_SECRETBOX_NONCEBYTES);
		$ciphertext = \Sodium\crypto_secretbox(serialize($value), $nonce, $this->_key);

		$nonce = base64_encode($nonce);
		$ciphertext = base64_encode($ciphertext);

		$json = json_encode(compact('nonce', 'ciphertext'));

		if (! is_string($json)) {
			throw new EncryptException('Could not encrypt the data.');
		}
		return base64_encode($json);
	}

	/**
	 * Decrypt the given value.
	 *
	 * @param  string  $payload
	 * @return string
	 *
	 * @throws \Illuminate\Contracts\Encryption\DecryptException
	 */
	public function decrypt($payload)
	{
		$payload = $this->getJsonPayload($payload);

		$nonce = base64_decode($payload['nonce']);
		$ciphertext = base64_decode($payload['ciphertext']);

		$plaintext = \Sodium\crypto_secretbox_open($ciphertext, $nonce, $this->_key);
		if ($plaintext === false) {
			throw new DecryptException("Could not decrypt the data.");
		}

		return unserialize($plaintext);
	}

	/**
	 * Get the JSON array from the given payload.
	 *
	 * @param  string  $payload
	 * @return array
	 *
	 * @throws \Illuminate\Contracts\Encryption\DecryptException
	 */
	private function getJsonPayload($payload)
	{
		$payload = json_decode(base64_decode($payload), true);

		if (! $payload || $this->invalidPayload($payload)) {
			throw new DecryptException('The payload is invalid.');
		}

		return $payload;
	}

	/**
	 * Verify that the encryption payload is valid.
	 *
	 * @param  array|mixed  $data
	 * @return bool
	 */
	private function invalidPayload($data)
	{
		return ! is_array($data) || ! isset($data['nonce']) || ! isset($data['ciphertext']);
	}
}