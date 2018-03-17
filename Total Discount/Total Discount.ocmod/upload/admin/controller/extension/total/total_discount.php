<?php
/**
 * EXTENSA
 *
 * Total Discount Module for OpenCart 3.0
 *
 * Copyright (c) 2009-2017, Extensa Web Development OOD (http://extensadev.com/) All rights reserved.
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
 * @copyright Copyright (c) 2009-2017, Extensa Web Development OOD (http://extensadev.com/) All rights reserved.
 * @license   http://extensadev.com/licenses/LICENSE-EULA-extensions-en.txt EXTENSA Commercial Software License (EULA)
 * @package   EXTENSA\OpenCart extensions\Total Discount Module
 * @link      http://extensadev.com/
 * @version   GIT: $Id$
 * @CID       $ClientID$
*/

class ControllerExtensionTotalTotalDiscount extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/total/total_discount');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('total_total_discount', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=total', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_total'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/total/total_total_discount', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/total/total_discount', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true);

		if (isset($this->request->post['total_total_discount_count'])) {
			$data['total_total_discount_count'] = $this->request->post['total_total_discount_count'];
		} else {
			$data['total_total_discount_count'] = $this->config->get('total_total_discount_count');
		}

		if (isset($this->request->post['total_total_discount_percent'])) {
			$data['total_total_discount_percent'] = $this->request->post['total_total_discount_percent'];
		} else {
			$data['total_total_discount_percent'] = $this->config->get('total_total_discount_percent');
		}

		if (isset($this->request->post['total_total_discount_each_count'])) {
			$data['total_total_discount_each_count'] = $this->request->post['total_total_discount_each_count'];
		} else {
			$data['total_total_discount_each_count'] = $this->config->get('total_total_discount_each_count');
		}

		if (isset($this->request->post['total_total_discount_status'])) {
			$data['total_total_discount_status'] = $this->request->post['total_total_discount_status'];
		} else {
			$data['total_total_discount_status'] = $this->config->get('total_total_discount_status');
		}

		if (isset($this->request->post['total_total_discount_sort_order'])) {
			$data['total_total_discount_sort_order'] = $this->request->post['total_total_discount_sort_order'];
		} else {
			$data['total_total_discount_sort_order'] = $this->config->get('total_total_discount_sort_order');
		}

		if (isset($this->request->post['total_total_discount_category'])) {
			$data['total_total_discount_category'] = $this->request->post['total_total_discount_category'];
		} elseif($this->config->get('total_total_discount_category')) {
			$data['total_total_discount_category'] = $this->config->get('total_total_discount_category');
		} else {
			$data['total_total_discount_category'] = array();
		}

		if (isset($this->request->post['total_total_discount_manufacturer'])) {
			$data['total_total_discount_manufacturer'] = $this->request->post['total_total_discount_manufacturer'];
		} elseif($this->config->get('total_total_discount_manufacturer')) {
			$data['total_total_discount_manufacturer'] = $this->config->get('total_total_discount_manufacturer');
		} else {
			$data['total_total_discount_manufacturer'] = array();
		}

		// Categories
		$this->load->model('catalog/category');
		$data['categories'] = array();

		$categories = $this->model_catalog_category->getCategories(0);

			foreach ($categories as $category) {
				$data['categories'][] = array(
					'category_id' => $category['category_id'], 
					'name'   => strip_tags(html_entity_decode($category['name'], ENT_QUOTES, 'UTF-8'))
				);
			}

		// Manufacturer
		$this->load->model('catalog/manufacturer');
		$data['manufacturers'] = array();

		$manufacturers = $this->model_catalog_manufacturer->getManufacturers(0);

		foreach ($manufacturers as $manufacturer) {
			$data['manufacturers'][] = array(
				'manufacturer_id' => $manufacturer['manufacturer_id'],
				'name'  => $manufacturer['name'],
				'sort_order' => $manufacturer['sort_order'],
				'selected'   => isset($this->request->post['selected']) && in_array($manufacturer['manufacturer_id'], $this->request->post['selected']),
			);
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/total/total_discount', $data));

	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/total/total_discount')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
?>