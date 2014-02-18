<?php  
class ControllerTestingHello extends Controller {
	public function index() {
		$this->document->setTitle($this->config->get('config_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));

		$this->data['heading_title'] = $this->config->get('config_title');
		/*
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/home.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/home.tpl';
		} else {
			$this->template = 'default/template/common/home.tpl';
		}
                */
                if (file_exists(DIR_TEMPLATE . 'testingnaja/template/common/home.tpl')) {
			$this->template = 'testingnaja/template/common/home.tpl';
		} else {
			$this->template = 'default/template/common/home.tpl';
		}
                
                
		
		$this->children = array(
			'testing/column_left',
			'testing/column_right',
			'testing/content_top',
			'testing/content_bottom',
			'testing/footer',
			'testing/header'
		);
										
		$this->response->setOutput($this->render());
	}
}
?>