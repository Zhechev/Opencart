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

include('config.php');

$url_test = '';
if (isset($_POST['email_review_test_email']) && isset($_POST['email_review_test_order'])) {
    $url_test .= '&email_review_test_email=' . trim($_POST['email_review_test_email']).'&email_review_test_order=' . trim($_POST['email_review_test_order']);
}

$url = HTTP_SERVER . 'index.php?route=module/email_review' . $url_test;

header('Location: ' . $url);
//file_get_contents($url);

// $_GET['route'] = 'module/email_review';
// require_once('index.php');
?>