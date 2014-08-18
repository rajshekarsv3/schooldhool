<?php
final class Voodoo {
    protected $voodoo_username;
    protected $voodoo_password;
    protected $voodoo_sendsms_api = "http://www.voodoosms.com/vsapi/server.php";
    protected $voodoo_balance_api = "http://www.voodoosms.com/vsapi/server.php";

    private function setUsername($voodoo_username){
        $this->voodoo_username = $voodoo_username;
    }

    private function setPassword($voodoo_password){
        $this->voodoo_password = $voodoo_password;
    }
    public function setApi($username,$password){
        $this->voodoo_username = $username;
        $this->voodoo_password = $password;
    }


    public function setVoodoo($voodoo_username, $voodoo_password){
        $this->setUsername($voodoo_username);
        $this->setPassword($voodoo_password);
    }

    public function getMessage($accurate,$message){
        $codes = array('{{order_id}}','{{order_status}}','{{customer}}');
        $message = str_replace($codes,$accurate,$message);
        return $message;
    }

    public function getMessageForCheckout($accurate,$message){
        $codes = array('{{order_id}}','{{firstname}}','{{email}}','{{total}}','{{currency_code}}','{{invoice_no}}');
        $message = str_replace($codes,$accurate,$message);
        return $message;
    }


    public function exportOrder($filename,$message){
        $dirPath = DIR_SYSTEM.'var/export/';
        //if the export directory does not exist, create it
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        }
        file_put_contents(
            $dirPath.$filename.'.txt',
            $message
        );
    }

    private function VoodoocURL($link){
        $http = curl_init($link);
        // do your curl thing here
        curl_setopt($http, CURLOPT_RETURNTRANSFER, TRUE);
        $http_result = curl_exec($http);
        $http_status = curl_getinfo($http, CURLINFO_HTTP_CODE);
        curl_close($http);

        return $http_result;
    }


    public function getBalance(){

        if (!$this->voodoo_username || !$this->voodoo_password) {
            exit('Please set your Voodoo username and password');
        }

        $voodoo_balance_api = $this->voodoo_balance_api;
        $voodoo_username = $this->voodoo_username;
        $voodoo_password = $this->voodoo_password;
        $link = $voodoo_balance_api.'?method=get_credit';
        $link .= "&username=".urlencode($voodoo_username);
        $link .= "&password=".urlencode($voodoo_password);

        $result = $this->VoodoocURL($link);

        $balance = (float)$result;

        if($balance < 0) return substr($result, 8);
        else return $result;
    }

    public function send($originator, $destination, $messagetype, $message, $db) {
        if (!$this->voodoo_username || !$this->voodoo_password) {
            exit('Please set your Voodoo username and password');
        }
        $voodoo_send_api = $this->voodoo_sendsms_api;
        $voodoo_username = $this->voodoo_username;
        $voodoo_password = $this->voodoo_password;
        $data = $voodoo_send_api.'?method=sendSMS';
        $data  .= '&username=' . urlencode($voodoo_username);
        $data .= '&password=' . urlencode($voodoo_password);
        $data .= '&destination=' . urlencode($destination);
        $data .= '&originator=' . urlencode($originator);
        $data .= '&message=' . urlencode($message);
        $data .= '&validity=300';
        $result = $this->VoodoocURL($data);
        return $result;
        //$voodoo_query = $db->query("INSERT INTO " . DB_PREFIX . "voodoosms(from, to, sms_message, voodoo_message_type, status, status_message, created_time) VALUES('" . $db->escape($originator) . "', '" . $db->escape($destination) . "', '" . $db->escape($message) . "', '" . $db->escape($messagetype) . "', '" . $result . "','" . $result . "',)");
    }
}
?>