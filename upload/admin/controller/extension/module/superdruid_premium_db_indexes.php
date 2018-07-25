<?php
class ControllerExtensionModuleSuperDruidPremiumDBIndexes extends Controller {

public function install() {
	$this->load->model('superdruid/premium_db_indexes');
	$this->model_superdruid_premium_db_indexes->install();
}

public function uninstall() {
	$this->load->model('superdruid/premium_db_indexes');
	$this->model_superdruid_premium_db_indexes->uninstall();
}

	
	public function index() {
		$this->load->language('extension/module/superdruid_premium_db_indexes');
		$this->load->model('superdruid/premium_db_indexes');
		

		$this->document->setTitle($this->language->get('heading_title'));
		
		$data['heading_title'] = $this->language->get('heading_title');
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], true),
      		'separator' => false
   		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true),
			'separator' => ' :: '
		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/module/superdruid_premium_db_indexes', 'user_token=' . $this->session->data['user_token'], true),
      		'separator' => ' :: '
   		);

		/*
		$this->template = 'module/superdruid_premium_db_indexes.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
		*/
		
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['cancel'] = $this->url->link('extension/module', 'user_token=' . $this->session->data['user_token'], 'SSL');

		$data['error_warning'] = '';
		/*
		if(property_exists($this, 'data'))
			{
			$tmp = $this->data;
			$merged = array_merge($tmp, $data);
			$this->data = $merged;
			}
		
		if(method_exists($this, 'render'))
			$this->response->setOutput($this->render());
		*/
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/superdruid_premium_db_indexes', $data));
		
	}
	


}
?>
