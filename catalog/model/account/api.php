<?php
/**
 * Used for Internal admin api db access
 * User: aditya
 * Date: 20/7/14
 * Time: 6:17 PM
 */
class ModelAccountApi extends Model {
    public function getVoucherDetails() {
        $voucher_query = "SELECT sum(amount) as total_amount,". DB_PREFIX ."customer_transaction.customer_id,". DB_PREFIX ."customer.firstname,". DB_PREFIX ."customer.lastname FROM `". DB_PREFIX ."customer_transaction`,`". DB_PREFIX ."customer` where `". DB_PREFIX ."customer_transaction`.customer_id = ". DB_PREFIX ."customer.customer_id and store_id = '" . (int)$this->config->get('config_store_id') . "' group by ". DB_PREFIX ."customer_transaction.customer_id";
        $voucher_result = $this->db->query($voucher_query);
        return $voucher_result;
    }
    public function getCountryId($country) {
        $country_id_query = "SELECT * FROM ". DB_PREFIX ."country WHERE name LIKE '$country'";
        $country_id_result = $this->db->query($country_id_query);
        return $country_id_result;
    }
    public function getZoneId($state) {
        $zone_id_query = "SELECT * FROM ". DB_PREFIX ."zone WHERE name LIKE '$state'";
        $zone_id_result = $this->db->query($zone_id_query);
        return $zone_id_result;
    }

    public function addTransaction($customer_id,$amount) {

        $transaction_query = "INSERT INTO ". DB_PREFIX ."customer_transaction (`customer_transaction_id`, `customer_id`, `order_id`, `description`, `amount`, `date_added`) VALUES (NULL, '$customer_id', '0', 'adding voucher from school admin panel', '$amount', NOW())";
        $this->db->query($transaction_query);
        return;
    }
    public function addCartData($cart_data,$user_id) {
        $this->db->query("UPDATE " . DB_PREFIX . "customer SET cart = '" . $this->db->escape(isset($cart_data) ? serialize($cart_data) : '') . "' WHERE customer_id = '" . (int)$user_id . "'");
    }
}
?>