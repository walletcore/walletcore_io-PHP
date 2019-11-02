<?php 

class WalletCoreAPI {
	private $private_key = '';
	private $public_key = '';
	private $ch = null;
	
	public function Setup($private_key, $public_key) {
		$this->private_key = $private_key;
		$this->public_key = $public_key;
		$this->ch = null;
	}
	   

	/**
	 * Creates an address for receiving payments into your WalletCore Address.<br />
	 * @param currency The cryptocurrency to create a receiving address for.
	 * @param ipn_url Optionally set an IPN handler to receive notices about this transaction. If ipn_url is empty then it will use the default IPN URL in your account.
	 */
	public function GetCallbackAddress($currency, $ipn_url = '') {		
		$req = array(
			'currency' => $currency,
			'ipn_url' => $ipn_url,
		);
		return $this->api_call('get_callback_address', $req);
	}

	/**
	 * Creates a withdrawal from your account to a specified address.<br />
	 * @param amount The amount of the transaction (floating point to 8 decimals).
	 * @param currency The cryptocurrency to withdraw.
	 * @param address The address to send the coins to. 
	 * @param ipn_url Optionally set an IPN handler to receive notices about this transaction. If ipn_url is empty then it will use the default IPN URL in your account.
	 */
	public function CreateWithdrawal($amount, $currency, $address,$ipn_url = '') {		
		$req = array(
			'amount'        => $amount,
			'currency'      => $currency,
			'address'       => $address, 
			'ipn_url'       => $ipn_url, 
		);
		return $this->api_call('create_withdrawal', $req);
	}
	 
   
	
	private function is_setup() {
		return (!empty($this->private_key) && !empty($this->public_key));
	}
	
	private function api_call($cmd, $req = array()) {
		if (!$this->is_setup()) {
			return array('error' => 'You have not called the Setup function with your private and public keys!');
		}
		
		// Set the API command and required fields
    $req['version'] = 1;
		$req['cmd'] = $cmd;
		$req['key'] = $this->public_key; 
	    
		// Generate the query string
		$post_data = http_build_query($req, '', '&');
	    
		// Calculate the HMAC signature on the POST data
		$hmac = hash_hmac('sha512', $post_data, $this->private_key);
	    
		// Create cURL handle and initialize (if needed)
		if ($this->ch === null) {
			$this->ch = curl_init('https://member.walletcore.io/api.php');
			curl_setopt($this->ch, CURLOPT_FAILONERROR, TRUE);
			curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
		}
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('HMAC: '.$hmac));
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post_data);
	    
		$data = curl_exec($this->ch);                
		if ($data !== FALSE) {
			if (PHP_INT_SIZE < 8 && version_compare(PHP_VERSION, '5.4.0') >= 0) {
				// We are on 32-bit PHP, so use the bigint as string option. If you are using any API calls with Satoshis it is highly NOT recommended to use 32-bit PHP
				$dec = json_decode($data, TRUE, 512, JSON_BIGINT_AS_STRING);
			} else {
				$dec = json_decode($data, TRUE);
			}
			if ($dec !== NULL && count($dec)) {
				return $dec;
			} else {
				// If you are using PHP 5.5.0 or higher you can use json_last_error_msg() for a better error message
				return array('error' => 'Unable to parse JSON result ('.json_last_error().')');
			}
		} else {
			return array('error' => 'cURL error: '.curl_error($this->ch));
		}
	}
};
