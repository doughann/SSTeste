<?php
class Journal2DBUpgrade {

    private $db;
    private $config;

    public function __construct() {
        global $registry;
        $this->db       = $registry->get('db');
        $this->config   = $registry->get('config');
    }

    private function checkColumn($table, $column) {
        if ($this->config->get('journal_db_check_journal2_newsletter_store_id') == 1) {
            return true;
        }

        $query = $this->db->query('SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = "' . $this->db->escape(DB_DATABASE) . '" AND TABLE_NAME = "' . $this->db->escape(DB_PREFIX . $table) . '" AND LCASE(DATA_TYPE) NOT IN ("blob", "text")');

        if (Front::$IS_OC2) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = 'config', `key` = '" . $this->db->escape('journal_db_check_' . $table . '_' . $column) . "', `value` = '1'");
        } else {
            $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `group` = 'config', `key` = '" . $this->db->escape('journal_db_check_' . $table . '_' . $column) . "', `value` = '1'");
        }


        if (!$query->num_rows) {
            return true;
        }

        foreach ($query->rows as $row) {
            if ($row['COLUMN_NAME'] === $column) {
                return true;
            }
        }

        return false;
    }

    private function addColumn($table, $column, $type) {
        $this->db->query('ALTER TABLE `' . $this->db->escape(DB_PREFIX . $table) . '` ADD ' . $column . ' ' . $type);
    }

    private function checkNewsletterStoreId() {
        if (!$this->checkColumn('journal2_newsletter', 'store_id')) {
            $this->addColumn('journal2_newsletter', 'store_id', 'INT NOT NULL DEFAULT 0');
        }
    }

    public static function check() {
        $obj = new Journal2DBUpgrade();

        $obj->checkNewsletterStoreId();
    }

}