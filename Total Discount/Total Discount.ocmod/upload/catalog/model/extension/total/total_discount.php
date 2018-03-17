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
	
class ModelExtensionTotalTotalDiscount extends Model {
	
	public function getTotal($total) {
		
		$count = 0;
		$price = 0;
		$prices = array();
		
		$this->load->model('catalog/product');
		
		foreach ($this->cart->getProducts() as $product) {
			if ($this->config->get('total_total_discount_category')){
				$category_info = $this->model_catalog_product->getCategories($product['product_id']);

				$product_in_total_discount_category = false;

				foreach($category_info as $category){
					if (in_array($category['category_id'], $this->config->get('total_total_discount_category'))){
						$product_in_total_discount_category = true;
						break;
					}
				}
			}

			if($this->config->get('total_total_discount_manufacturer')){
				$product_info = $this->model_catalog_product->getProduct($product['product_id']);
				if (in_array($product_info['manufacturer_id'], $this->config->get('total_total_discount_manufacturer'))){
					$product_in_total_discount_manufacturer = true;
				}else{
					$product_in_total_discount_manufacturer = false;
				}
			}

			if (($this->config->get('total_total_discount_category') && $this->config->get('total_total_discount_manufacturer') && $product_in_total_discount_category && $product_in_total_discount_manufacturer) || ($this->config->get('total_total_discount_category') && !$this->config->get('total_total_discount_manufacturer') && $product_in_total_discount_category) || (!$this->config->get('total_total_discount_category') && $this->config->get('total_total_discount_manufacturer') && $product_in_total_discount_manufacturer) || (!$this->config->get('total_total_discount_category') && !$this->config->get('total_total_discount_manufacturer'))) {
				$count += $product['quantity'];
				for ($i = 0; $i < $product['quantity']; $i++) {
					$prices[] = $product['price'];
				}
			}
			
		}

		sort($prices);
		if ($count >= (int)$this->config->get('total_total_discount_count')) {
			if ($this->config->get('total_total_discount_each_count')) {
				$items_count = floor($count / (int)$this->config->get('total_total_discount_manufacturer'));

				for ($i = 0; $i < $items_count; $i++) {
					$price += $prices[$i];
				}
			} else {
				if(isset($prices[0])){
					$price += $prices[0];
				}
			}

			$this->load->language('extension/total/total_discount');

			$price *= (float)$this->config->get('total_total_discount_percent') / 100;

			$total['totals'][] = array(
				'code'       => 'total_discount',
				'title'      => $this->language->get('text_total_discount'),
				'text'       => $this->currency->format(-$price,$this->config->get('config_currency')),
				'value'      => -$price,
				'sort_order' => $this->config->get('total_total_discount_sort_order')
			);

			$total['total'] -= $price;
		}
	}
}