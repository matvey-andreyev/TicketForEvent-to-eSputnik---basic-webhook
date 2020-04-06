<?php

/**
 * eSputnik API v1, partial.
 * adding contacts
 *
 * @see https://esputnik.com/api/
 */
class ESputnikClass
{

	private $eSputnik_user = false;
	private $eSputnik_password = false;
    private $api_url = 'https://esputnik.com/api/v1';
    //private $api_url = 'http://apitests.local/esputnik';
    public $http_status;


    /**
     * Set auth credentials
     * @param null $eSputnikUser
     * @param null $eSputnikPassword
     */
    public function __construct($user = null, $password = null)
    {
		
		if (!empty($user)) {
			$this->eSputnik_user = $user;
		}

        if (!empty($password)) {
            $this->eSputnik_password = $password;
        }
    }	

    /**
     * Add a contact
     * @param      $params
     */	
    public function addContact($params)
    {
        return $this->call('contact', 'POST', $params);
    }
	
    /**
     * Subscribe a contact. If a contact does not exist,
	 * eSputnik will create one with unconfirmed email
     * @param      $params
     */	
    public function subscribeContact($params)
    {
        return $this->call('contact/subscribe', 'POST', $params);
    }	

    /**
     * retrieving balance
     *
     * @return mixed
     */
    public function getBalance()
    {
        return $this->call('balance');
    }
	
    /**
     * retrieving account info
     *
     * @return mixed
     */
    public function getAccountInfo()
    {
        return $this->call('account/info');
    }

    /**
     * Curl run request
     *
     * @param null $api_method
     * @param string $http_method
     * @param array $params
     * @return mixed
     * @throws Exception
     */
    private function call($api_method = null, $http_method = 'GET', $params = array())
    {
        if (empty($api_method)) {
            return (object)array(
                'httpStatus' => '400',
                'code' => '1010',
                'codeDescription' => 'Error in external resources',
                'message' => 'Empty api method'
            );
        }

		if( $this->eSputnik_user === false || $this->eSputnik_password === false ){
            return (object)array(
                'httpStatus' => '400',
                'code' => '0',
                'codeDescription' => 'Auth error',
                'message' => 'Credentials incomplete'
            );
		}
		
        $params = json_encode($params);
        $url = $this->api_url . '/' . $api_method;

        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_ENCODING => 'gzip,deflate',
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HEADER => false,
            CURLOPT_USERAGENT => 'ExpoPromoter ESputnik client 0.0.1',
            CURLOPT_HTTPHEADER => array('Accept: application/json', 'Content-Type: application/json'),
			CURLOPT_USERPWD => $this->eSputnik_user.':'.$this->eSputnik_password
        );

        if ($http_method == 'POST') {
            $options[CURLOPT_POST] = 1;
            $options[CURLOPT_POSTFIELDS] = $params;
        } else if ($http_method == 'DELETE') {
            $options[CURLOPT_CUSTOMREQUEST] = 'DELETE';
        }

        $curl = curl_init();
        curl_setopt_array($curl, $options);

        $response = json_decode(curl_exec($curl));

        $this->http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);
        return (object)$response;
    }

}