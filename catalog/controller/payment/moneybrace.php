<?php
/* Moneybrace online payment
 *
 * @version 1.0
 * @date 11/03/2012
 * @author george zheng <xinhaozheng@gmail.com>
 * @more info available on mzcart.com
 */
class ControllerPaymentMoneybrace extends Controller {
	protected function index() {
		$this->language->load('payment/moneybrace');
 
		$this->data['button_confirm'] = $this->language->get('button_confirm');
 
		$this->data['action'] = 'https://payment.moneybrace.com/payment/paypage.aspx';
 
		$this->load->model('checkout/order');
 
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
 
		if ($order_info) {
		    $this->data['merchantid'] = trim($this->config->get('moneybrace_merchantacct'));
			$this->data['encoding'] = 'utf-8';
			$this->data['transtype'] = 'IC';
			$this->data['version'] = '1.0.0';
			$this->data['orderid'] = date('His') . $this->session->data['order_id'];
 
			switch($this->session->data['language']) {
				case 'de':
					 $this->data['language'] = 'de-de';
					 break;
				case 'fr':
					 $this->data['language'] = 'fr-fr';
					 break;
				case 'it':
					 $this->data['language'] = 'it-it';
					 break;
				case 'es':
					 $this->data['language'] = 'es-es';
					 break;
				case 'pt':
					 $this->data['language'] = 'pt-pt';
					 break;
				case 'jp':
					 $this->data['language'] = 'ja-jp';
					 break;
				default:
					 $this->data['language'] = 'en-us';
		    }
			$this->data['callbackurl'] = $this->url->link('payment/moneybrace/callback');
			$this->data['browserbackurl'] = $this->url->link('checkout/success');
			$this->data['accessurl'] = 'https://payment.moneybrace.coms';
			$this->data['orderdate'] = date('YmdHis');
			$this->data['currency'] = $order_info['currency_code'];
            $allowed_cur = array('USD', 'EUR', 'GBP', 'CAD', 'AUD', 'JPY');
			$currency = $_SESSION['currency'];
			if ( !in_array($currency, $allowed_cur)) {
				$currency = 'USD';
			}
 
			$this->data['orderamount'] = $this->currency->format($order_info['total'], $currency , false, false);
 
			$this->data['first_name'] = html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8');
			$this->data['last_name'] = html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
 
			$this->data['billemail'] = $order_info['email'];
			$this->data['billphone'] = html_entity_decode($order_info['telephone'], ENT_QUOTES, 'UTF-8');
			$this->data['billaddress'] = html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8');
			$this->data['billcountry'] = html_entity_decode($order_info['payment_iso_code_2'], ENT_QUOTES, 'UTF-8');
			$this->data['billprovince'] = html_entity_decode($order_info['payment_zone'], ENT_QUOTES, 'UTF-8');;
			$this->data['billcity'] = html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8');
			$this->data['billpost'] = html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');
 
			$this->data['deliveryname'] = html_entity_decode($order_info['shipping_firstname'] . $order_info['shipping_lastname'], ENT_QUOTES, 'UTF-8');
			$this->data['deliveryaddress'] = html_entity_decode($order_info['shipping_address_1'], ENT_QUOTES, 'UTF-8');
			$this->data['deliverycity'] = html_entity_decode($order_info['shipping_city'], ENT_QUOTES, 'UTF-8');
			$this->data['deliverycountry'] = html_entity_decode($order_info['shipping_iso_code_2'], ENT_QUOTES, 'UTF-8');
			$this->data['deliveryprovince'] = html_entity_decode($order_info['shipping_zone'], ENT_QUOTES, 'UTF-8');
			$this->data['deliveryemail'] = $order_info['email'];
			$this->data['deliveryphone'] = html_entity_decode($order_info['telephone'], ENT_QUOTES, 'UTF-8');
			$this->data['deliverypost'] = html_entity_decode($order_info['shipping_postcode'], ENT_QUOTES, 'UTF-8');
 
			$strProducts = '';
			$htmlProducts = '';
			foreach ($this->cart->getProducts() as $product) {
			    $pname = trim(str_replace('"', '', $product['name']));
 
			    if ( $pname == '' && $strProducts == '' && $htmlProducts = '') {
				    $pname = 'Order ' . $this->data['orderid'];
					$psn = $this->data['orderid'];
					$qty = 1;
					$price = $this->currency->format($product['price'], $currency, false, false);
				    $strProducts = 'Order ' . $this->data['orderid'] . '1';
					$htmlProducts = '<input name="productname1" value="" ' . $pname . ' />' .
									'<input name="productsn1" value="" ' . $psn . '/>' .
									'<input name="quantity1" value="" ' . $qty . ' />' .
									'<input name="unit1" value="" ' . $price . '/>' ;
					break;
				}
 
				$psn = $product['model'];
				$qty = $product['quantity'];
				$price = $this->currency->format($product['price'], $currency, false, false);
 
				$strProducts =  $pname . $psn . $qty . $price;
				$htmlProducts = '<input type="hidden" name="productname1" value="' . $pname . '" />' .
								'<input type="hidden" name="productsn1" value="' . $psn . '" />' .
								'<input type="hidden" name="quantity1" value="' . $qty . '" />' .
								'<input type="hidden" name="unit1" value="' . $price . '"/>' ;
 
			}
			$this->data['htmlProducts'] = $htmlProducts;
 
			$cert = $this->config->get('moneybrace_cert');
 
			$strSource =$cert . $this->data['version'] . $this->data['encoding'] . $this->data['language'] . $this->data['merchantid'] .
			$this->data['orderid'] . $this->data['orderdate'] . $this->data['currency'] . $this->data['orderamount'] . $this->data['transtype'] .
			$this->data['callbackurl'] . $this->data['browserbackurl'] . $this->data['accessurl'] .
            $strProducts .
			$this->data['billaddress'] . $this->data['billcountry'] . $this->data['billprovince'] . $this->data['billcity'] .
			$this->data['billemail'] . $this->data['billphone'] . $this->data['billpost'] .
			$this->data['deliveryname'] . $this->data['deliveryaddress'] . $this->data['deliverycountry'] . $this->data['deliveryprovince'] .
			$this->data['deliverycity'] . $this->data['deliveryemail'] . $this->data['deliveryphone'] . $this->data['deliverypost'];
            if ($this->config->get('moneybrace_debug')) {
				$this->log->write('Submit source string:' . $strSource);
			}
			$signature = md5($strSource);
			$this->data['signature'] = $signature;
 
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/moneybrace.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/payment/moneybrace.tpl';
			} else {
				$this->template = 'default/template/payment/moneybrace.tpl';
			}
 
			$this->render();
		}
	}
 
	public function callback() {
		if (isset($this->request->post['orderid'])) {
			$order_id = trim(substr(($this->request->post['orderid']), 6));
		} else {
			die('Illegal Access');
		}
 
		$this->load->model('checkout/order');
 
		$order_info = $this->model_checkout_order->getOrder($order_id);
 
		if ($order_info) {
		    $data = array_merge($this->request->post,$this->request->get);
			foreach ($data as $key => $value) {
				${$k} = $value;
			}
 
			$product='';
			for($i=1;$i<=10;$i++) {
				if(!isset($data['productname'.$i]) || $data['productname'.$i] == '') {
					break;
				}
				$product = $product . $data['productname'.$i] . $data['productsn'.$i] . $data['quantity'.$i] . $data['unit'.$i];
			}
 
			$cert = $this->config->get('moneybrace_cert');
			$strSource = $cert . $version . $encoding . $lang . $merchantid . $transtype . $orderid .
            $orderdate . $currency. $orderamount . $paycurrency . $payamount .$remark1 . $remark2 .
            $remark3 .  $product . $shippingfee . $deliveryname . $deliveryaddress . $deliverycountry .$deliveryprovince.
            $deliverycity . $deliveryemail . $deliveryphone . $deliverypost . $transid . $transdate . $status;
 
			if ($this->config->get('moneybrace_debug')) {
				$this->log->write('Return source string:' . $strSource);
			}
 
			$getsignature=md5($strSource);
			if ( $getsignature != $signature) {
			    $order_status_id = $this->config->get('moneybrace_pending_status_id');
			    $this->model_checkout_order->confirm($order_id, $this->config->get('config_order_status_id'));
			    die('Data validate failed');
			}
 
			//payment was made succ
			if ($status == 'Y' || $status == 'y') {
			    $order_status_id = $this->config->get('moneybrace_processed_status_id');
				if (!$order_info['order_status_id'] || $order_info['order_status_id'] != $order_status_id) {
					$this->model_checkout_order->confirm($order_id, $order_status_id);
				} else {
					$this->model_checkout_order->update($order_id, $order_status_id);
				}
			}
		}
	}
}
?>