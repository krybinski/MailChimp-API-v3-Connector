<?php
/**
 * @author Kamil Rybinski <kamilryba19@gmail.com>
 * 
 * This is a simple Mailchimp API v3 connector using PHP and cURL.
 */

class MailchimpConnector {
    const STATUS_SUCCESS = "SUCCESS";
    const STATUS_FAILED = "FAILDED";

    /**
     * @param string $server
     * @param string $apiKey
     */
    public function __construct(string $server, string $apiKey) {
        $this->server = $server;
        $this->apiKey = $apiKey;
    }

    /**
     * Add new member to list
     * @param string $listId
     * @param string $email
     * @param string $firstName
     * @param string $lastName
     * @param string $phone
     */
    public function addMember(string $listId, string $email, string $firstName = "", string $lastName = "", string $phone = "") {
        $auth = base64_encode('user:'. $this->apiKey);
        
		$data = array(
			'apikey' => $this->apiKey,
			'email_address' => $email,
            'status' => 'subscribed',
            'merge_fields'  => array(
                'FNAME' => $firstName,
                'LNAME' => $lastName,
                'PHONE' => $phone,
            )
        );
        
        $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://' . $this->server . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Authorization: Basic ' . $auth)
		);
		curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $result = curl_exec($ch);

        if ($result === false) {
            echo json_encode(array(
                'status' => self::STATUS_FAILED,
                'msg' => curl_error($ch),
            ));
        } else {
            echo json_encode(array(
                'status' => self::STATUS_SUCCESS,
                'msg' => 'Member saved successfully.',
            ));
        }

        curl_close($ch);
    }
}

?>
