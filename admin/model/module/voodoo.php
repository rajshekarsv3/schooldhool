<?php
class ModelModuleVoodoo extends Model {

    public function createVoodooTables() {
        $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "voodoosms` (
                `voodoo_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `d` varchar(255) NOT NULL DEFAULT '',
                `fromorder_i` varchar(255) NOT NULL DEFAULT '',
                `to` varchar(255) NOT NULL DEFAULT '',
                `sms_message` text NOT NULL,
                `status` varchar(255) NOT NULL DEFAULT '',
                `status_message` varchar(255) NOT NULL DEFAULT '',
                `created_time` datetime DEFAULT NULL,
                PRIMARY KEY (`voodoo_id`)
             )";
        $query = $this->db->query($sql);
    }

    public function deleteVoodooTables() {
        $query = $this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "voodoosms");
    }
}
?>