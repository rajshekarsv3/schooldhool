<?php 
class ControllerSaleVoodoo extends Controller {
	private $error = array();
	 
	public function index() {
		$this->load->language('sale/voodoo');
 
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('sale/customer');
		
		$this->load->model('sale/customer_group');
		
		$this->load->model('sale/affiliate');
		
		$this->load->model('sale/voodoo');
		
		$config_data = array(
				'voodoo_admin_contact',
				'voodoo_message_type',
				'voodoo_username',
				'voodoo_password'
		);
		
		foreach ($config_data as $conf) {
			if (isset($this->request->post[$conf])) {
				$this->data[$conf] = $this->request->post[$conf];
			} else {
				$this->data[$conf] = $this->config->get($conf);
			}
		}
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->model('setting/store');
		
			/*$store_info = $this->model_setting_store->getStore($this->request->post['store_id']);			
			
			if ($store_info) {
				$store_name = $store_info['name'];
			} else {
				$store_name = $this->config->get('config_name');
			}
			*/
			$emails = array();
			
			switch ($this->request->post['to']) {
				case 'customer_all':
					$customer_data = array();
								
					$email_total = $this->model_sale_customer->getTotalCustomers($customer_data);
									
					$results = $this->model_sale_customer->getCustomers($customer_data);
		
					foreach ($results as $result) {
						$emails[] = $result['telephone'];
					}						
					break;
				case 'customer_group':
					$customer_data = array(
						'filter_customer_group_id' => $this->request->post['customer_group_id']
					);
					
					$email_total = $this->model_sale_customer->getTotalCustomers($customer_data);
									
					$results = $this->model_sale_customer->getCustomers($customer_data);
			
					foreach ($results as $result) {
						$emails[$result['customer_id']] = $result['telephone'];
					}						
					break;
				case 'customer':
					if (isset($this->request->post['customer'])) {					
						foreach ($this->request->post['customer'] as $customer_id) {
							$customer_info = $this->model_sale_customer->getCustomer($customer_id);
							
							if ($customer_info) {
								$emails[] = $customer_info['telephone'];
							}
						}
					}
					break;												
			}
			
			$emails = array_unique($emails);
			
			if ($emails) {
				
				$count = 0;
				foreach ($emails as $sms) {
					$voodoo = new Voodoo();
					
					$voodoo->setVoodoo($this->data['voodoo_username'], $this->data['voodoo_password']);
					$result = $voodoo->send($this->data['voodoo_admin_contact'], $sms, $this->request->post['voodoo_message_type'], html_entity_decode($this->request->post['message'], ENT_QUOTES, 'UTF-8'), $this->db);
					$count++;
				}
			}
			
			if(!empty($result) && $result[0] < 0){
				$this->error['warning'] = $result[1];
			}
			else{
				$this->session->data['success'] = $this->language->get('text_success');
			}
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		
		$this->data['tab_voodoo_general'] = $this->language->get('tab_voodoo_general');	
		$this->data['tab_voodoo_report'] = $this->language->get('tab_voodoo_report');
		
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_newsletter'] = $this->language->get('text_newsletter');
		$this->data['text_customer_all'] = $this->language->get('text_customer_all');	
		$this->data['text_customer'] = $this->language->get('text_customer');	
		$this->data['text_customer_group'] = $this->language->get('text_customer_group');
		$this->data['text_affiliate_all'] = $this->language->get('text_affiliate_all');	
		$this->data['text_affiliate'] = $this->language->get('text_affiliate');	
		$this->data['text_product'] = $this->language->get('text_product');	
		$this->data['text_voodoo_edit'] = $this->language->get('text_voodoo_edit');		
		$this->data['text_voodoo_balance'] = $this->language->get('text_voodoo_balance');	
		$this->data['text_no_results'] = $this->language->get('text_no_results');	

		$this->data['entry_voodoo_balance'] = $this->language->get('entry_voodoo_balance');
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_to'] = $this->language->get('entry_to');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_customer'] = $this->language->get('entry_customer');
		$this->data['entry_affiliate'] = $this->language->get('entry_affiliate');
		$this->data['entry_product'] = $this->language->get('entry_product');
		$this->data['entry_subject'] = $this->language->get('entry_subject');
		$this->data['entry_message'] = $this->language->get('entry_message');
		$this->data['entry_message_type'] = $this->language->get('entry_message_type');
		
		$this->data['button_send'] = $this->language->get('button_send');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_module_settings'] = $this->language->get('button_module_settings');
		
		if($this->data['voodoo_username'] == "" || $this->data['voodoo_password'] == ""){
			$this->data['voodoo_balance'] = sprintf($this->data['text_voodoo_balance'], $this->url->link('module/voodoo', 'token=' . $this->session->data['token'], 'SSL'));
		}
		else{
			$voodoo = new Voodoo;
			$voodoo->setVoodoo($this->data['voodoo_username'], $this->data['voodoo_password']);
			$this->data['voodoo_balance'] = $voodoo->getBalance();
		}
				
		$this->data['token'] = $this->session->data['token'];
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
	 	
		if (isset($this->error['message'])) {
			$this->data['error_message'] = $this->error['message'];
		} else {
			$this->data['error_message'] = '';
		}	

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/contact', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
				
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
				
		$this->data['action'] = $this->url->link('sale/voodoo', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('sale/voodoo', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['module_settings'] = $this->url->link('module/voodoo', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['text_voodoo_edit'] = sprintf($this->data['text_voodoo_edit'], $this->url->link('module/voodoo', 'token=' . $this->session->data['token'], 'SSL'));

		if (isset($this->request->post['store_id'])) {
			$this->data['store_id'] = $this->request->post['store_id'];
		} else {
			$this->data['store_id'] = '';
		}
		
		$this->load->model('setting/store');
		
		$this->data['stores'] = $this->model_setting_store->getStores();
		
		if (isset($this->request->post['to'])) {
			$this->data['to'] = $this->request->post['to'];
		} else {
			$this->data['to'] = '';
		}
				
		if (isset($this->request->post['customer_group_id'])) {
			$this->data['customer_group_id'] = $this->request->post['customer_group_id'];
		} else {
			$this->data['customer_group_id'] = '';
		}
				
		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups(0);
				
		$this->data['customers'] = array();
		
		if (isset($this->request->post['customer'])) {					
			foreach ($this->request->post['customer'] as $customer_id) {
				$customer_info = $this->model_sale_customer->getCustomer($customer_id);
					
				if ($customer_info) {
					$this->data['customers'][] = array(
						'customer_id' => $customer_info['customer_id'],
						'name'        => $customer_info['firstname'] . ' ' . $customer_info['lastname']
					);
				}
			}
		}

		$this->data['affiliates'] = array();
		
		if (isset($this->request->post['affiliate'])) {					
			foreach ($this->request->post['affiliate'] as $affiliate_id) {
				$affiliate_info = $this->model_sale_affiliate->getAffiliate($affiliate_id);
					
				if ($affiliate_info) {
					$this->data['affiliates'][] = array(
						'affiliate_id' => $affiliate_info['affiliate_id'],
						'name'         => $affiliate_info['firstname'] . ' ' . $affiliate_info['lastname']
					);
				}
			}
		}
		
		$this->load->model('catalog/product');

		$this->data['products'] = array();
		
		if (isset($this->request->post['product'])) {					
			foreach ($this->request->post['product'] as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);
					
				if ($product_info) {
					$this->data['products'][] = array(
						'product_id' => $product_info['product_id'],
						'name'       => $product_info['name']
					);
				}
			}
		}
		//Start report
		if (isset($this->request->get['filter_id'])) {
			$filter_id = $this->request->get['filter_id'];
		} else {
			$filter_id = null;
		}
		
		if (isset($this->request->get['filter_source'])) {
			$filter_source = $this->request->get['filter_source'];
		} else {
			$filter_source = null;
		}
		
		if (isset($this->request->get['filter_destination'])) {
			$filter_destination = $this->request->get['filter_destination'];
		} else {
			$filter_destination = null;
		}
		
		if (isset($this->request->get['filter_message'])) {
			$filter_message = $this->request->get['filter_message'];
		} else {
			$filter_message = null;
		}
		
		if (isset($this->request->get['filter_message_type'])) {
			$filter_message_type = $this->request->get['filter_message_type'];
		} else {
			$filter_message_type = null;
		}
		
		if (isset($this->request->get['filter_server_status'])) {
			$filter_server_status = $this->request->get['filter_server_status'];
		} else {
			$filter_server_status = null;
		}
		
		if (isset($this->request->get['filter_sent_on'])) {
			$filter_sent_on = $this->request->get['filter_sent_on'];
		} else {
			$filter_sent_on = date("Y-m-d");
		}
		
		if (isset($this->request->get['filter_tab'])) {
			$filter_tab = $this->request->get['filter_tab'];
		} else {
			$filter_tab = "tab_general";
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'voodoo_report_id'; 
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		$this->data['button_filter'] = $this->language->get('button_filter');
		
		$this->data['filter_tab'] = $filter_tab;
		
		$this->data['text_ascii'] = $this->language->get('text_ascii');
		$this->data['text_unicode'] = $this->language->get('text_unicode');
		
		$this->data['column_id'] = $this->language->get('column_id');
		$this->data['column_source'] = $this->language->get('column_source');
		$this->data['column_destination'] = $this->language->get('column_destination');
		$this->data['column_message'] = $this->language->get('column_message');
		$this->data['column_message_type'] = $this->language->get('column_message_type');
		$this->data['column_server_status'] = $this->language->get('column_server_status');
		$this->data['column_sent_on'] = $this->language->get('column_sent_on');
		$this->data['column_action'] = $this->language->get('column_action');
		
		$this->data['voodoo_reports'] = array();
		
		$data = array(
			'filter_id' 		=> $filter_id,
			'filter_source' 		=> $filter_source,
			'filter_destination' 	=> $filter_destination,
			'filter_message' 		=> $filter_message,
			'filter_message_type' 	=> $filter_message_type,
			'filter_server_status' 	=> $filter_server_status,
			'filter_start_date'	=> $filter_sent_on." 00:00:00", 
			'filter_end_date'		=> $filter_sent_on." 23:59:59",
			'sort'                     => $sort,
			'order'                    => $order
		);
		
		$results = $this->model_sale_voodoo->getVoodooReports($data);
		
		foreach ($results as $result) {
			
			$this->data['voodoo_reports'][] = array(
				'voodoo_report_id'		=> $result['voodoo_report_id'],
				'voodoo_source'		=> $result['voodoo_source'],
				'voodoo_destination'	=> $result['voodoo_destination'],
				'voodoo_message' 		=> $result['voodoo_message'],
				'voodoo_message_type' 	=> ($result['voodoo_message_type']==1 ? "ASCII" : "Unicode"),
				'voodoo_server_status'	=> $result['voodoo_server_status'],
				'voodoo_sent_on'		=> $result['voodoo_sent_on']
			);
		}
		
		$this->data['filter_id'] = $filter_id;
		$this->data['filter_source'] = $filter_source;
		$this->data['filter_destination'] = $filter_destination;
		$this->data['filter_message'] = $filter_message;
		$this->data['filter_message_type'] = $filter_message_type;
		$this->data['filter_server_status'] = $filter_server_status;
		$this->data['filter_sent_on'] = $filter_sent_on;
		
		$url = '';

		if (isset($this->request->get['filter_id'])) {
			$url .= '&filter_id=' . $this->request->get['filter_id'];
		}
		
		if (isset($this->request->get['filter_source'])) {
			$url .= '&filter_source=' . $this->request->get['filter_source'];
		}
		
		if (isset($this->request->get['filter_destination'])) {
			$url .= '&filter_destination=' . $this->request->get['filter_destination'];
		}
			
		if (isset($this->request->get['filter_message'])) {
			$url .= '&filter_message=' . $this->request->get['filter_message'];
		}
		
		if (isset($this->request->get['filter_message_type'])) {
			$url .= '&filter_message_type=' . $this->request->get['filter_message_type'];
		}	
		
		if (isset($this->request->get['filter_server_status'])) {
			$url .= '&filter_server_status=' . $this->request->get['filter_server_status'];
		}
				
		if (isset($this->request->get['filter_sent_on'])) {
			$url .= '&filter_sent_on=' . $this->request->get['filter_sent_on'];
		}
		
		if (isset($this->request->get['filter_tab'])) {
			$url .= '&filter_tab=' . $this->request->get['filter_tab'];
		}
		
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_id'] = $this->url->link('sale/voodoo', 'token=' . $this->session->data['token'] . '&sort=voodoo_report_id' . $url, 'SSL');
		$this->data['sort_source'] = $this->url->link('sale/voodoo', 'token=' . $this->session->data['token'] . '&sort=voodoo_source' . $url, 'SSL');
		$this->data['sort_destination'] = $this->url->link('sale/voodoo', 'token=' . $this->session->data['token'] . '&sort=voodoo_destination' . $url, 'SSL');
		$this->data['sort_message'] = $this->url->link('sale/voodoo', 'token=' . $this->session->data['token'] . '&sort=voodoo_message' . $url, 'SSL');
		$this->data['sort_message_type'] = $this->url->link('sale/voodoo', 'token=' . $this->session->data['token'] . '&sort=voodoo_message_type' . $url, 'SSL');
		$this->data['sort_server_status'] = $this->url->link('sale/voodoo', 'token=' . $this->session->data['token'] . '&sort=voodoo_server_status' . $url, 'SSL');
		$this->data['sort_sent_on'] = $this->url->link('sale/voodoo', 'token=' . $this->session->data['token'] . '&sort=voodoo_sent_on' . $url, 'SSL');
		
		$url = '';
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		//End report
		if (isset($this->request->post['subject'])) {
			$this->data['subject'] = $this->request->post['subject'];
		} else {
			$this->data['subject'] = '';
		}
		
		if (isset($this->request->post['message'])) {
			$this->data['message'] = $this->request->post['message'];
		} else {
			$this->data['message'] = '';
		}

		$this->template = 'sale/voodoo.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
				
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'sale/voodoo')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['message']) {
			$this->error['message'] = $this->language->get('error_message');
		}
						
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>