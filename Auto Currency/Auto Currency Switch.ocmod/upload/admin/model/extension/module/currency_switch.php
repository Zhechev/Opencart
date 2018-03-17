<?php
/**
 * EXTENSA
 *
 * Auto Currency/Language Switch by Location Module for OpenCart 3.0
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
 * @package   EXTENSA\OpenCart extensions\Auto Currency/Language Switch by Location Module
 * @link      http://extensadev.com/
 * @version   GIT: $Id$
 * @CID       $ClientID$
*/

class ModelExtensionModuleCurrencySwitch extends Model {
  public function install () {
    $query = $this->db->query("CREATE TABLE IF NOT EXISTS oc_country_currency (
        country_currency_id INT(11) AUTO_INCREMENT PRIMARY KEY, 
        country_id INT(11) NOT NULL,
        currency_id INT(11) NOT NULL
      )");

    $query = $this->db->query("CREATE TABLE IF NOT EXISTS oc_country_language (
        country_language_id INT(11) AUTO_INCREMENT PRIMARY KEY, 
        country_id INT(11) NOT NULL,
        language_id INT(11) NOT NULL
      )");
  }

  public function uninstall () {
    $query = $this->db->query("DROP TABLE IF EXISTS country_currency");
    $query = $this->db->query("DROP TABLE IF EXISTS country_language");
  }
}
