<?php
    class ModelSaleRelatorio extends Model {
        public function HellWorld($data = array()) {
            $sql = "SELECT * FROM `" . DB_PREFIX . "order` where 1 order by order_id DESC limit 3";
            $query = $this->db->query($sql);
            return $query->rows;
        }
		public function getOrderStatuses($data = array()) {
			if ($data) {
				$sql = "SELECT * FROM " . DB_PREFIX . "order_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'";

				$sql .= " ORDER BY name";

				if (isset($data['order']) && ($data['order'] == 'DESC')) {
					$sql .= " DESC";
				} else {
					$sql .= " ASC";
				}

				if (isset($data['start']) || isset($data['limit'])) {
					if ($data['start'] < 0) {
						$data['start'] = 0;
					}

					if ($data['limit'] < 1) {
						$data['limit'] = 20;
					}

					$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
				}

				$query = $this->db->query($sql);

				return $query->rows;
			} else {
				$order_status_data = $this->cache->get('order_status.' . (int)$this->config->get('config_language_id'));

				if (!$order_status_data) {
					$query = $this->db->query("SELECT order_status_id, name FROM " . DB_PREFIX . "order_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name");

					$order_status_data = $query->rows;

					$this->cache->set('order_status.' . (int)$this->config->get('config_language_id'), $order_status_data);
				}

				return $order_status_data;
			}
		}

		public function getProducts($orderid) {
			$sql = "SELECT op.*, pd.*, pt.location FROM oc_order_product op
LEFT JOIN oc_product_description pd ON (op.product_id = pd.product_id)
LEFT JOIN oc_product pt ON (op.product_id = pt.product_id)
where op.order_id='".$orderid."' and pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
order by pd.name asc ";


      // "SELECT * FROM " . DB_PREFIX . "order_product op LEFT JOIN " .
      // DB_PREFIX . "product_description pd ON (op.product_id = pd.product_id)
      // where op.order_id='".$orderid."' and pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

    //  echo $sql;

			$query = $this->db->query($sql);
			return $query->rows;


        }
		public function getProductsDaySagawa($data = array()) {
			$filtra=$data['filter_delivery_date']." 00:00:00";
			$filtra2=$data['filter_delivery_date2']." 23:59:59";
			$adendo="";
			if(isset($data['filter_order_status_id']) and $data['filter_order_status_id']!=""){
				$adendo = " and order_status_id='".$data['filter_order_status_id']."' ";
			}


			$sql = "SELECT o.*, op.*, sum(op.quantity) as qtdgeral, pt.location,
      pt.product_id FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX .
      "order_product op on(o.order_id=op.order_id) LEFT JOIN " .
      DB_PREFIX . "product_description pd ON (op.product_id = pd.product_id)
      LEFT JOIN oc_product pt ON (op.product_id = pt.product_id)
      where shipping_method like'%sagawa%' and ddw_delivery_date>='" .
      $filtra . "' and ddw_delivery_date<='" . $filtra2 . "' and
      pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ".$adendo."
      group by pd.product_id order by pd.name ASC";
			//echo $sql;
            $query = $this->db->query($sql);
            return $query->rows;




        }
		public function getProductsDayLocalManha($data = array()) {
			$filtra=$data['filter_delivery_date']." 00:00:00";
			$filtra2=$data['filter_delivery_date2']." 23:59:59";
			$adendo="";
			if(isset($data['filter_order_status_id']) and $data['filter_order_status_id']!=""){
				$adendo = " and order_status_id='".$data['filter_order_status_id']."' ";
			}

			$sql = "SELECT o.*, op.*, sum(op.quantity) as qtdgeral FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op on(o.order_id=op.order_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (op.product_id = pd.product_id) where shipping_method like'%entrega local%' and ddw_delivery_date>='" . $filtra . "' and ddw_delivery_date<='" . $filtra2 . "' and pd.language_id = '" . (int)$this->config->get('config_language_id') . "' and ddw_time_slot like '%09:00 - 12:00%' ".$adendo." group by pd.product_id order by o.order_id DESC";
			//echo $sql;
            $query = $this->db->query($sql);
            return $query->rows;
        }


        public function getProdutoxls($data = array()) {
    			$filtra=$data['filter_delivery_date']." 00:00:00";
    			$filtra2=$data['filter_delivery_date2']." 23:59:59";
    			$adendo="";
    			if(isset($data['filter_order_status_id']) and $data['filter_order_status_id']!=""){
    				$adendo = " and order_status_id='".$data['filter_order_status_id']."' ";
    			}

    			$sql = "SELECT pd.name, sum(op.quantity) as qtdgeral, pt.location FROM `" .
          DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX .
          "order_product op on(o.order_id=op.order_id) LEFT JOIN " .
          DB_PREFIX . "product_description pd ON (op.product_id = pd.product_id)
          LEFT JOIN oc_product pt ON (op.product_id = pt.product_id)
          where shipping_method like'%sagawa%' and ddw_delivery_date>='" .
          $filtra . "' and ddw_delivery_date<='" . $filtra2 . "' and
          pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ".$adendo."
          group by pd.product_id order by pd.name ASC";
    			//echo $sql;
                $query = $this->db->query($sql);
                return $query->rows;
            }


		public function getProductsDayLocalNoite($data = array()) {
			$filtra=$data['filter_delivery_date']." 00:00:00";
			$filtra2=$data['filter_delivery_date2']." 23:59:59";
			$adendo="";
			if(isset($data['filter_order_status_id']) and $data['filter_order_status_id']!=""){
				$adendo = " and order_status_id='".$data['filter_order_status_id']."' ";
			}

			$sql = "SELECT o.*, op.*, sum(op.quantity) as qtdgeral FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op on(o.order_id=op.order_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (op.product_id = pd.product_id) where shipping_method like'%entrega local%' and ddw_delivery_date>='" . $filtra . "' and ddw_delivery_date<='" . $filtra2 . "' and pd.language_id = '" . (int)$this->config->get('config_language_id') . "' and ddw_time_slot like '%19:00 - 22:00%' ".$adendo." group by pd.product_id order by o.order_id DESC";
			//echo $sql;
            $query = $this->db->query($sql);
            return $query->rows;




        }
		public function getProductsDayRetirar($data = array()) {
			$filtra=$data['filter_delivery_date']." 00:00:00";
			$filtra2=$data['filter_delivery_date2']." 23:59:59";
			$adendo="";
			if(isset($data['filter_order_status_id']) and $data['filter_order_status_id']!=""){
				$adendo = " and order_status_id='".$data['filter_order_status_id']."' ";
			}

			$sql = "SELECT o.*, op.*, sum(op.quantity) as qtdgeral, pt.location FROM `" . DB_PREFIX . "order` o LEFT JOIN " . DB_PREFIX . "order_product op on(o.order_id=op.order_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (op.product_id = pd.product_id) LEFT JOIN oc_product pt ON (op.product_id = pt.product_id)  where shipping_method like'%retirar na loja%' and ddw_delivery_date>='" . $filtra . "' and ddw_delivery_date<='" . $filtra2 . "' and pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ".$adendo." group by pd.product_id order by pd.name ASC";
			//echo $sql;
            $query = $this->db->query($sql);
            return $query->rows;




        }

		public function EntregaSagawa($data = array()) {
			$adendo="";
			if(isset($data['filter_order_status_id']) and $data['filter_order_status_id']!=""){
				$adendo = " and order_status_id='".$data['filter_order_status_id']."' ";
			}
			$filtra=$data['filter_delivery_date']." 00:00:00";
			$filtra2=$data['filter_delivery_date2']." 23:59:59";
			$sql = "SELECT * FROM `" . DB_PREFIX . "order` where shipping_method like'%sagawa%' and ddw_delivery_date>='" . $filtra . "'  and ddw_delivery_date<='" . $filtra2 . "' ".$adendo." order by order_id DESC";
            $query = $this->db->query($sql);
            return $query->rows;
		}

		public function EntregaLocalManha($data = array()) {
			$adendo="";
			if(isset($data['filter_order_status_id']) and $data['filter_order_status_id']!=""){
				$adendo = " and order_status_id='".$data['filter_order_status_id']."' ";
			}
			$filtra=$data['filter_delivery_date']." 00:00:00";
			$filtra2=$data['filter_delivery_date2']." 23:59:59";
			$sql = "SELECT * FROM `" . DB_PREFIX . "order` where shipping_method like '%entrega local%' and ddw_time_slot like '%09:00 - 12:00%' and ddw_delivery_date>='" . $filtra . "'  and ddw_delivery_date<='" . $filtra2 . "' ".$adendo." order by order_id DESC";

            $query = $this->db->query($sql);
            return $query->rows;
		}
		public function EntregaLocalNoite($data = array()) {
			$adendo="";
			if(isset($data['filter_order_status_id']) and $data['filter_order_status_id']!=""){
				$adendo = " and order_status_id='".$data['filter_order_status_id']."' ";
			}
			$filtra=$data['filter_delivery_date']." 00:00:00";
			$filtra2=$data['filter_delivery_date2']." 23:59:59";
			$sql = "SELECT * FROM `" . DB_PREFIX . "order` where shipping_method like '%entrega local%' and ddw_time_slot like '%19:00 - 22:00%' and ddw_delivery_date>='" . $filtra . "'  and ddw_delivery_date<='" . $filtra2 . "' ".$adendo." order by order_id DESC";
			//echo $sql;
            $query = $this->db->query($sql);
            return $query->rows;
		}
		public function BuscaLocal($data = array()) {
			$adendo="";
			if(isset($data['filter_order_status_id']) and $data['filter_order_status_id']!=""){
				$adendo = " and order_status_id='".$data['filter_order_status_id']."' ";
			}
			$filtra=$data['filter_delivery_date']." 00:00:00";
			$filtr2a=$data['filter_delivery_date2']." 23:59:59";
			$sql = "SELECT * FROM `" . DB_PREFIX . "order` where shipping_method like'%retirar na loja%' and ddw_delivery_date='" . $filtra . "' ".$adendo." order by order_id DESC";
            $query = $this->db->query($sql);
            return $query->rows;
		}







    }

?>
