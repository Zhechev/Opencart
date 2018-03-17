<?php
/**
 * EXTENSA
 *
 * Quantity Taxation Module for OpenCart 3.0
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
 * @package   EXTENSA\OpenCart extensions\Quantity Taxation
 * @link      http://extensadev.com/
 * @version   GIT: $Id$
 * @CID       $ClientID$
*/
class ModelExtensionTotalQuantityTaxation extends Model {
	public function getTotal($total) {
		
			$products = $this->cart->getProducts();
			$cart_quantity = $this->cart->countProducts();
			$subtotal = $this->cart->getTotal();
			
			if($this->config->get('total_quantity_taxation_name') == NULL){
					$name = '';
			} else {
				$name = $this->config->get('total_quantity_taxation_name');
			}
			
			if($this->config->get('total_quantity_taxation_range') == NULL){
					$ranges = array();
			} else {
				$ranges = $this->config->get('total_quantity_taxation_range');
			}
			
			if($this->config->get('total_quantity_taxation_lang') == NULL){
					$languages = array();
			} else {
				$languages = $this->config->get('total_quantity_taxation_lang');
			}
			
			if($this->config->get('total_quantity_taxation_curr') == NULL){
					$currencies = array();
			} else {
				$currencies = $this->config->get('total_quantity_taxation_curr');
			}
			
			if($this->config->get('total_quantity_taxation_customer_group') == NULL){
					$customer_groups = array();
			} else {
				$customer_groups = $this->config->get('total_quantity_taxation_customer_group');
			}
			
			if($this->config->get('total_quantity_taxation_geo_zone') == NULL){
					$geo_zones = array();
			} else {
				$geo_zones = $this->config->get('total_quantity_taxation_geo_zone');
			}
			
			$zone = $this->config->get('config_country_id');
			$query = $this->db->query("SELECT geo_zone_id FROM `oc_zone_to_geo_zone` where country_id = '" . $zone . "'");
			$geo_zone_id = (int)$query->row['geo_zone_id'];	
		
			$user_language = $this->language->get('code');
			$user_currency = $this->config->get('config_currency');
			$user_group = $this->customer->getGroupId();
			
			if (in_array($user_language, $languages) || in_array($user_currency, $currencies)) {

				foreach($ranges as $range) {
					
					$from = (int)$range['from'];
					$to = (int)$range['to'];
					$tax = (int)$range['tax'];
					if (isset($range['type'])) {
					$type = (int)$range['type'];
					} else {
					$type = 1;
					}
					if($type == 1) {
						$tax /= 100;
						$tax *= $subtotal;
					} 

					if($cart_quantity >= $from && $cart_quantity <= $to) {

						if ((int)$user_group == 0) {
							$user_group = 1;
						}
						if (array_key_exists((int)$user_group, $customer_groups)) {
							if(array_key_exists($geo_zone_id, $geo_zones)) {
								if(!empty($products)) {
									$total['totals'][] = array(
										'code'       => 'tax',
										'title'      => $name,
										'value'      => $tax,
										'sort_order' => 2
									);
									$total['total'] += $tax;
								}
							}
						}
					} 
				}
			}
	}
}