<?php 

/**
 * EXTENSA
 *
 * Product Review Email Reminder for OpenCart 2.x
 *
 * Copyright (c) 2009-2016, Extensa Web Development OOD (http://extensadev.com/) All rights reserved.
 *
 * This source file may not be redistributed in whole or significant part!
 * You have no rights to make any derivative works or to modify the source code!
 * This copyright notice MUST APPEAR in all copies of the file!
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EXTENSA Commercial Software License (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://extensadev.com/licenses/LICENSE-EULA-extensions-en.txt
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@extensadev.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.extensadev.com/ for more information
 * or send an email to info@extensadev.com
 *
 * @author    EXTENSA <info@extensadev.com>
 * @copyright Copyright (c) 2009-2016, Extensa Web Development OOD (http://extensadev.com/) All rights reserved.
 * @license   http://extensadev.com/licenses/LICENSE-EULA-extensions-en.txt EXTENSA Commercial Software License (EULA)
 * @package   EXTENSA\OpenCart extensions\Email Review
 * @link      http://extensadev.com/
 * @version   GIT: $Id$
 * @CID       $ClientID$
*/
 
class ControllerModuleEmailReview extends Controller {
	public function index() {

		// Admin Alert Mail
		if ($this->config->get('email_review_alert_mail')) {

			$this->load->model('module/email_review');
			$orders_statuses = $this->config->get('email_review_order_statuses');

			if (!isset($this->request->get['email_review_test_email']) && !isset($this->request->get['email_review_test_order'])) {
				$orders_info = $this->model_module_email_review->getOrders();

				foreach ($orders_info as $order_info) {
					if ($orders_statuses && in_array($order_info['order_status_id'], $orders_statuses)) {
						$this->language->load('module/email_review');

						$subject = $this->config->get('email_review_subject_' . $this->config->get('config_language_id'));

						// HTML Mail
						$data['text_greeting'] = sprintf($this->language->get('text_new_greeting'), html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
						$data['text_hello'] = sprintf($this->language->get('text_new_hello'), html_entity_decode($order_info['firstname']), html_entity_decode($order_info['lastname'], ENT_QUOTES, 'UTF-8'));
						$data['text_desc'] = sprintf($this->language->get('text_desc'), html_entity_decode(date($this->language->get('date_format_short'), strtotime($order_info['date_added'])), ENT_QUOTES, 'UTF-8'));
						$data['logo'] = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');
						$data['store_name'] = $order_info['store_name'];
						$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
						$data['store_url'] = $order_info['store_url'];
						$data['text_footer'] = $this->language->get('text_new_footer');
						$data['text_product'] = $this->language->get('text_new_product');
						$data['text_model'] = $this->language->get('text_new_model');
						$data['text_quantity'] = $this->language->get('text_new_quantity');
						$data['text_new_link'] = $this->language->get('text_new_link');
						$data['text_link'] = $this->language->get('text_link');

						$data['products'] = array();

						$order_products = $this->model_module_email_review->getProducts($order_info['order_id']);
						foreach($order_products as $product) {
							$data['products'][] = array(
								'name'     => $product['name'],
								'model'    => $product['model'],
								'link'     => $order_info['store_url'] . 'index.php?route=product/product&product_id=' . $product['product_id'],
								'quantity' => $product['quantity']
							);
						}
						
						if (preg_match("/2.2.0/i", VERSION)){
							$html = $this->load->view('module/email_review', $data); 
						}

						if ((preg_match("/2.0.0/i", VERSION) || preg_match("/2.0.1/i", VERSION)) && !preg_match("/2.2.0/i", VERSION)) {
							$mail = new Mail($this->config->get('config_mail')); 
						} else {
							$mail = new Mail();
							$mail->protocol = $this->config->get('config_mail_protocol');
							$mail->parameter = $this->config->get('config_mail_parameter');
							$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
							$mail->smtp_username = $this->config->get('config_mail_smtp_username');
							$mail->smtp_password = $this->config->get('config_mail_smtp_password');
							$mail->smtp_port = $this->config->get('config_mail_smtp_port');
							$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
						}
						$mail->setTo($order_info['email']);
						$mail->setFrom($this->config->get('config_email'));
						$mail->setSender($order_info['store_name']);
						$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
						$mail->setHtml($html);
						$mail->send();
					}
				}
			} elseif (!empty($this->request->get['email_review_test_email']) && !empty($this->request->get['email_review_test_order'])) {

				$order_inform = $this->model_module_email_review->getOrder($this->request->get['email_review_test_order']);

				if ($orders_statuses && $order_inform && in_array($order_inform['order_status_id'], $orders_statuses)) {
					$this->language->load('module/email_review');

					$subject = $this->config->get('email_review_subject_' . $this->config->get('config_language_id'));

					// HTML Mail
					$data['text_greeting'] = sprintf($this->language->get('text_new_greeting'), html_entity_decode($order_inform['store_name'], ENT_QUOTES, 'UTF-8'));
					$data['text_hello'] = sprintf($this->language->get('text_new_hello'), html_entity_decode($order_inform['firstname']), html_entity_decode($order_inform['lastname'], ENT_QUOTES, 'UTF-8'));
					$data['text_desc'] = sprintf($this->language->get('text_desc'), html_entity_decode(date($this->language->get('date_format_short'), strtotime($order_inform['date_added'])), ENT_QUOTES, 'UTF-8'));
					$data['logo'] = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');
					$data['store_name'] = $order_inform['store_name'];
					$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_inform['date_added']));
					$data['store_url'] = $order_inform['store_url'];
					$data['text_footer'] = $this->language->get('text_new_footer');
					$data['text_product'] = $this->language->get('text_new_product');
					$data['text_model'] = $this->language->get('text_new_model');
					$data['text_quantity'] = $this->language->get('text_new_quantity');
					$data['text_new_link'] = $this->language->get('text_new_link');
					$data['text_link'] = $this->language->get('text_link');

					$data['products'] = array();

					$order_products = $this->model_module_email_review->getProducts($order_inform['order_id']);
					foreach($order_products as $product) {
						$data['products'][] = array(
							'name'     => $product['name'],
							'model'    => $product['model'],
							'link'     => $order_inform['store_url'] . 'index.php?route=product/product&product_id=' . $product['product_id'],
							'quantity' => $product['quantity']
						);
					}
					
					if (preg_match("/2.2.0/i", VERSION)){
						$html = $this->load->view('module/email_review', $data);
					}
					
					if ((preg_match("/2.0.0/i", VERSION) || preg_match("/2.0.1/i", VERSION)) && !preg_match("/2.2.0/i", VERSION)) {
						$mail = new Mail($this->config->get('config_mail')); 
					} else {
						$mail = new Mail();
						$mail->protocol = $this->config->get('config_mail_protocol');
						$mail->parameter = $this->config->get('config_mail_parameter');
						$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
						$mail->smtp_username = $this->config->get('config_mail_smtp_username');
						$mail->smtp_password = $this->config->get('config_mail_smtp_password');
						$mail->smtp_port = $this->config->get('config_mail_smtp_port');
						$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
					}
					$mail->setTo($this->request->get['email_review_test_email']);
					$mail->setFrom($this->config->get('config_email'));
					$mail->setSender($order_inform['store_name']);
					$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
					$mail->setHtml($html);
					$mail->send();
				}
			}
		}
	}
}
?>