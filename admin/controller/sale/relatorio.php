<?

    class Controllersalerelatorio extends Controller{
        public function index(){
                    // VARS
                    $template="sale/relatorio.tpl"; // .tpl location and file
            $this->load->model('sale/relatorio');
            $this->template = ''.$template.'';
            $this->children = array(
                'common/header',
                'common/footer'
            );
			$hoje=date("Y-m-d");
			if (isset($this->request->get['filter_delivery_date'])) {
				$filter_delivery_date = $this->request->get['filter_delivery_date'];
			} else {
				$filter_delivery_date = $hoje;
			}

			if (isset($this->request->get['filter_delivery_date2'])) {
				$filter_delivery_date2 = $this->request->get['filter_delivery_date2'];
			} else {
				$filter_delivery_date2 = $hoje;
			}
			if (isset($this->request->get['filter_order_status_id'])) {
				$filter_order_status_id = $this->request->get['filter_order_status_id'];
			} else {
				$filter_order_status_id = "";
			}
			if (isset($this->request->get['filter_tipo'])) {
				$filter_tipo = $this->request->get['filter_tipo'];
			} else {
				$filter_tipo = "";
			}
			$url = '';
			if (isset($this->request->get['filter_order_status_id'])) {
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
			}
			if (isset($this->request->get['filter_delivery_date'])) {
				$url .= '&filter_delivery_date=' . $this->request->get['filter_delivery_date'];
			}
			if (isset($this->request->get['filter_delivery_date2'])) {
				$url .= '&filter_delivery_date2=' . $this->request->get['filter_delivery_date2'];
			}
			if (isset($this->request->get['filter_tipo'])) {
				$url .= '&filter_tipo=' . $this->request->get['filter_tipo'];
			}
			$data = array(
			'filter_order_status_id'   => $filter_order_status_id,
			'filter_delivery_date'   => $filter_delivery_date,
			'filter_delivery_date2'   => $filter_delivery_date2,
			'filter_tipo'   => $filter_tipo,
			'token'   => $this->session->data['token']
			//'sort'                   => $sort,
			//'order'                  => $order,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
			);
			$this->data['filter_order_status_id'] = $filter_order_status_id;//data da entrega
			$this->data['filter_delivery_date'] = $filter_delivery_date;//data da entrega
			$this->data['filter_delivery_date2'] = $filter_delivery_date2;//data da entrega
			$this->data['filter_tipo'] = $filter_tipo;

			$this->data['order_statuses'] = $this->model_sale_relatorio->getOrderStatuses();

			/*sagawa*/
			$this->results_sagawa = $this->model_sale_relatorio->EntregaSagawa($data);
			$results_sagawa = $this->results_sagawa;
			foreach ($results_sagawa as $result) {
				$this->data['dsagawa'][] = array(
					'order_id' => $result['order_id'],
					'products'       => $this->model_sale_relatorio->getProducts($result['order_id']),
					'ddw_time_slot'      => $result['ddw_time_slot'],
					'ddw_delivery_date'      => $result['ddw_delivery_date']
				);
			}

			$this->totais_sagawa = $this->model_sale_relatorio->getProductsDaySagawa($data);
			$totais_sagawa = $this->totais_sagawa;
			foreach ($totais_sagawa as $result) {
				$this->data['tsagawa'][] = array(
					'qtdgeral' => $result['qtdgeral'],
					'name'      => $result['name'],
          'location'      => $result['location'],
        //  'product_id'      => $result['product_id'],


				);
			}
			/*fim sagawa*/


      /*Exportar xls*/


      $this->totalxls = $this->model_sale_relatorio->getProdutoxls($data);
      $totalxls = $this->totalxls;
      foreach ($totalxls as $result) {
        $this->data['trsultxls'][] = array(
          'qtdgeral'  => $result['qtdgeral'],
          'name'      => $result['name'],
          'location'  => $result['location'],

        );
      }
      /*Exportar Xls*/


			/*local manha*/
			$this->results_lmanha = $this->model_sale_relatorio->EntregaLocalManha($data);
			$results_lmanha = $this->results_lmanha;
			foreach ($results_lmanha as $result) {
				$this->data['lmanha'][] = array(
					'order_id' => $result['order_id'],
					'products'       => $this->model_sale_relatorio->getProducts($result['order_id']),
					'ddw_time_slot'      => $result['ddw_time_slot'],
					'ddw_delivery_date'      => $result['ddw_delivery_date']
				);
			}

			$this->totais_lmanha = $this->model_sale_relatorio->getProductsDayLocalManha($data);
			$totais_lmanha = $this->totais_lmanha;
			foreach ($totais_lmanha as $result) {
				$this->data['tlmanha'][] = array(
					'qtdgeral' => $result['qtdgeral'],
					'name'      => $result['name'],

				);
			}
			/*fim local manha*/



			/*local noite*/
			$this->results_lnoite = $this->model_sale_relatorio->EntregaLocalNoite($data);
			$results_lnoite = $this->results_lnoite;
			foreach ($results_lnoite as $result) {
				$this->data['lnoite'][] = array(
					'order_id' => $result['order_id'],
					'products'       => $this->model_sale_relatorio->getProducts($result['order_id']),
					'ddw_time_slot'      => $result['ddw_time_slot'],
					'ddw_delivery_date'      => $result['ddw_delivery_date']
				);
			}

			$this->totais_lnoite = $this->model_sale_relatorio->getProductsDayLocalNoite($data);
			$totais_lnoite = $this->totais_lnoite;
			foreach ($totais_lnoite as $result) {
				$this->data['tlnoite'][] = array(
					'qtdgeral' => $result['qtdgeral'],
					'name'      => $result['name'],

				);
			}
			/*fim local noite*/

			/*retirar na loja*/
			$this->results_lretirar = $this->model_sale_relatorio->BuscaLocal($data);
			$results_lretirar = $this->results_lretirar;
			foreach ($results_lretirar as $result) {
				$this->data['lretirar'][] = array(
					'order_id' => $result['order_id'],
					'products'       => $this->model_sale_relatorio->getProducts($result['order_id']),
					'ddw_time_slot'      => $result['ddw_time_slot'],
					'ddw_delivery_date'      => $result['ddw_delivery_date']
				);
			}

			$this->totais_lretirar = $this->model_sale_relatorio->getProductsDayRetirar($data);
			$totais_lretirar = $this->totais_lretirar;
			foreach ($totais_lretirar as $result) {
				$this->data['tlretirar'][] = array(
					'qtdgeral' => $result['qtdgeral'],
					'name'      => $result['name'],
          'location'  => $result['location'],

				);
			}
			/*fim retirar na loja*/

			//$this->data['dlocalmanha'] = $this->model_sale_relatorio->EntregaLocalManha();
			//$this->data['dlocalnoite'] = $this->model_sale_relatorio->EntregaLocalNoite();
			//$this->data['dpickup'] = $this->model_sale_relatorio->BuscaLocal();


			$this->data['breadcrumbs'] = array();

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
			);

			$this->data['breadcrumbs'][] = array(
				'text'      => "Relatório",
				'href'      => $this->url->link('sale/relatorio', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
			);

            $this->response->setOutput($this->render());
        }


        public function exportarxls(){
            $this->load->model('sale/relatorio');

			$hoje=date("Y-m-d");
			if (isset($this->request->get['filter_delivery_date'])) {
				$filter_delivery_date = $this->request->get['filter_delivery_date'];
			} else {
				$filter_delivery_date = $hoje;
			}

			if (isset($this->request->get['filter_delivery_date2'])) {
				$filter_delivery_date2 = $this->request->get['filter_delivery_date2'];
			} else {
				$filter_delivery_date2 = $hoje;
			}
			if (isset($this->request->get['filter_order_status_id'])) {
				$filter_order_status_id = $this->request->get['filter_order_status_id'];
			} else {
				$filter_order_status_id = "";
			}
			if (isset($this->request->get['filter_tipo'])) {
				$filter_tipo = $this->request->get['filter_tipo'];
			} else {
				$filter_tipo = "";
			}
			$url = '';
			if (isset($this->request->get['filter_order_status_id'])) {
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
			}
			if (isset($this->request->get['filter_delivery_date'])) {
				$url .= '&filter_delivery_date=' . $this->request->get['filter_delivery_date'];
			}
			if (isset($this->request->get['filter_delivery_date2'])) {
				$url .= '&filter_delivery_date2=' . $this->request->get['filter_delivery_date2'];
			}
			if (isset($this->request->get['filter_tipo'])) {
				$url .= '&filter_tipo=' . $this->request->get['filter_tipo'];
			}
			$data = array(
			'filter_order_status_id'   => $filter_order_status_id,
			'filter_delivery_date'   => $filter_delivery_date,
			'filter_delivery_date2'   => $filter_delivery_date2,
			'filter_tipo'   => $filter_tipo,
			'token'   => $this->session->data['token']
			//'sort'                   => $sort,
			//'order'                  => $order,
			//'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			//'limit'                  => $this->config->get('config_admin_limit')
			);
			$this->data['filter_order_status_id'] = $filter_order_status_id;//data da entrega
			$this->data['filter_delivery_date'] = $filter_delivery_date;//data da entrega
			$this->data['filter_delivery_date2'] = $filter_delivery_date2;//data da entrega
			$this->data['filter_tipo'] = $filter_tipo;

			$this->data['order_statuses'] = $this->model_sale_relatorio->getOrderStatuses();

      /*Exportar xls*/
      $this->totalxls = $this->model_sale_relatorio->getProdutoxls($data);
      $totalxls = $this->totalxls;

      /*Exportar Xls*/
       $arraydata = array();
       $i = 0;
       foreach ($totalxls as $key => $value) {
          $arraydata[$i]['Nome Produto'] = html_entity_decode($value['name']);
          $arraydata[$i]['Quantidade'] = $value['qtdgeral'];
          $arraydata[$i]['Localização'] = $value['location'];
          $i ++;
       }


       function filterData(&$str)
    {
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
    }


    $fileName = "Exportar_" . date('Ymd') . ".xls";


    header("Content-Disposition: attachment; filename=\"$fileName\"");
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");

    $flag = false;
    foreach($arraydata as $row) {
        if(!$flag) {
            echo implode("\t", array_keys($row)) . "\n";
            $flag = true;
        }
        array_walk($row, 'filterData');
        echo implode("\t", array_values($row)) . "\n";

    }

    exit;

    //    // Definimos o nome do arquivo que será exportado $arquivo = "MinhaPlanilha.xls";
    //  // Configurações header para forçar o download
    //  header('Content-Type: application/vnd.ms-excel');
    // //  header("Content-type: application/force-download");
    //  header('Content-Disposition: attachment;filename="exportar_'.$filter_delivery_date.'.xls"');
    // //  header("Pragma: no-cache");
    // //  header('Cache-Control: max-age=0'); // Se for o IE9, isso talvez seja necessário
    // //  header('Cache-Control: max-age=1'); // Envia o conteúdo do arquivo
    //  //
    // //  header("Content-Disposition: attachment; filename=\"$fileName\"");
    // //  header("Content-Type: application/vnd.ms-excel");
    //
    //  echo $dadosXls;
    // exit;


      //    $this->response->setOutput($this->render());
        }

    }
    ?>
