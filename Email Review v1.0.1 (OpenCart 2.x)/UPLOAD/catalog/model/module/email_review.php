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

class ModelModuleEmailReview extends Model {
	public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT o.firstname, o.lastname, o.order_status_id, o.store_name, o.email, o.order_id, o.store_url, o.date_added, (SELECT os.name FROM `" . DB_PREFIX . "order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status FROM `" . DB_PREFIX . "order` o WHERE order_id = '" . (int)$order_id . "'");

		return $order_query->row;
	}

	public function getOrders() {
		$order_query = $this->db->query("SELECT o.firstname, o.lastname, o.order_status_id, o.store_name, o.email, o.order_id, o.store_url, o.date_added, (SELECT os.name FROM `" . DB_PREFIX . "order_status` os WHERE os.order_status_id = o.order_status_id AND os.language_id = o.language_id) AS order_status FROM `" . DB_PREFIX . "order` o WHERE DATE(o.date_added)=DATE(DATE_ADD(NOW(),INTERVAL -".$this->db->escape((int)$this->config->get('email_review_day') ? (int)$this->config->get('email_review_day') : 4)." DAY))");

		return $order_query->rows;
	}

	public function getProducts($order_id) {
		$products_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_product` WHERE order_id = '" . (int)$order_id . "'");

		return $products_query->rows;
	}
}
?>