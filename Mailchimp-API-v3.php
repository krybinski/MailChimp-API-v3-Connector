<?php
/**
 * @author Kamil Rybinski <kamilryba19@gmail.com>
 * 
 * This is a simple Mailchimp API v3 connector using PHP and cURL.
 */

class MailchimpConnector {

    const STATUS_SUCCESS = "SUCCESS";
    const STATUS_FAILED = "FAILED";

    /**
     * @param string $server
     * @param string $apiKey
     */
    public function __construct(string $server, string $apiKey) {
        $this->server = $server;
        $this->apiKey = $apiKey;

        $this->headerAuth = array(
			'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode('user:'. $apiKey),
        );
    }

    /**
     * Get members from list
     * @param string $listId
     */
    public function getMembers(string $listId) {
        $url = 'https://' . $this->server . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/';

        $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headerAuth);
		curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);

        echo $this->getResult($ch, 'Members successfully received.');

        curl_close($ch);
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
        $url = 'https://' . $this->server . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/';

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
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headerAuth);
		curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $result = curl_exec($ch);

        echo $this->getResult($ch, 'Member saved successfully.');

        curl_close($ch);
    }

    /**
     * Get success JSON
     * @param any $result
     * @param string $msg
     */
    private function getSuccessJson($result, string $msg) {
        return json_encode(array(
            'status' => self::STATUS_SUCCESS,
            'msg' => $msg,
            'data' => $result,
        ));
    }

    /**
     *  Get error JSON
     * @param string $msg
     */
    private function getErrorJson($msg) {
        return json_encode(array(
            'status' => self::STATUS_FAILED,
            'msg' => $msg,
            'data' => array(),
        ));
    }

    /**
     * Get result
     * @param any $ch
     * @param string $msg
     */
    private function getResult($ch, string $msg) {
        $result = curl_exec($ch);
        $error = curl_error($ch);

        return ($result === false)
            ? $this->getErrorJson($error)
            : $this->getSuccessJson($result, $msg);
    }
}

?>
