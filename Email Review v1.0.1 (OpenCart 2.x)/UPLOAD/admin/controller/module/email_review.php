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
	private $error = array();

	public function index() {
		$this->language->load('module/email_review');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('email_review', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_edit'] = $this->language->get('text_edit');

		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_alert_mail'] = $this->language->get('entry_alert_mail');
		$data['entry_order_statuses'] = $this->language->get('entry_order_statuses');
		$data['entry_order_day'] = $this->language->get('entry_order_day');
		$data['entry_subject'] = $this->language->get('entry_subject');
		$data['entry_test_email'] = $this->language->get('entry_test_email');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_order'] = $this->language->get('entry_order');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_send'] = $this->language->get('button_send');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/email_review', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$data['action'] = $this->url->link('module/email_review', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['email_review_status'])) {
			$data['email_review_status'] = $this->request->post['email_review_status'];
		} else {
			$data['email_review_status'] = $this->config->get('email_review_status');
		}

		if (isset($this->request->post['email_review_alert_mail'])) {
			$data['email_review_alert_mail'] = $this->request->post['email_review_alert_mail'];
		} else {
			$data['email_review_alert_mail'] = $this->config->get('email_review_alert_mail');
		}

		if (isset($this->request->post['email_review_day'])) {
			$data['email_review_day'] = $this->request->post['email_review_day'];
		} else {
			$data['email_review_day'] = $this->config->get('email_review_day');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();


		$data['email_review_order_statuses'] = array();

		if (isset($this->request->post['email_review_order_statuses'])) {
			$data['email_review_order_statuses'] = $this->request->post['email_review_order_statuses'];
		} else {
			$data['email_review_order_statuses'] = $this->config->get('email_review_order_statuses');
		}

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			if (isset($this->request->post['email_review_subject_' . $language['language_id']])) {
				$data['email_review_subject_' . $language['language_id']] = $this->request->post['email_review_subject_' . $language['language_id']];
			} else {
				$data['email_review_subject_' . $language['language_id']] = $this->config->get('email_review_subject_' . $language['language_id']);
			}
		}

		$data['languages'] = $languages;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/email_review.tpl', $data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/email_review')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>