<?php

class ModelGroupDiscountGroupDiscount extends Model
{
    public function getProducts() {
        $sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";
        $sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'"; 
        $sql .= " GROUP BY p.product_id";

        $sort_data = array(
            'pd.name',
            'p.model',
            'p.price',
            'p.quantity',
            'p.status',
            'p.sort_order'
        );  
        $sql .= " ORDER BY pd.name";   
        $query = $this->db->query($sql);
    
        return $query->rows;
    }
}