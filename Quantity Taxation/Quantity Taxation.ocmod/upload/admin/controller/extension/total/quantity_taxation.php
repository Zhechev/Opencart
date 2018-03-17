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
class ControllerExtensionTotalQuantityTaxation extends Controller {
    private $error = array(); // This is used to set the errors, if any.
 
    public function index() {
        // Loading the language file of quantity_taxation
        $this->load->language('extension/total/quantity_taxation');
		
        // Set the title of the page to the heading title in the Language file i.e., Quantity Taxation
        $this->document->setTitle($this->language->get('heading_title'));
     
        // Load the Setting Model  (All of the OpenCart Module & General Settings are saved using this Model )
        $this->load->model('setting/setting');
		
		$this->load->model('localisation/currency');
		$this->load->model('localisation/language');
		$this->load->model('localisation/geo_zone');
		$this->load->model('customer/customer_group');
		
		$data['currencies'] = $this->model_localisation_currency->getCurrencies();
		
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();
		
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
     
        // Start If: Validates and check if data is coming by save (POST) method
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
            // Parse all the coming data to Setting Model to save it in database.
            $this->model_setting_setting->editSetting('total_quantity_taxation', $this->request->post);
     
            // To display the success text on data save
            $this->session->data['success'] = $this->language->get('text_success');
     
            // Redirect to the Module Listing
            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token']. '&type=total', true));
        }
         
        // This Block returns the warning if any
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
     
        
        if (isset($this->error['code'])) {
            $data['error_code'] = $this->error['code'];
        } else {
            $data['error_code'] = '';
        }  
     
        // Making of Breadcrumbs to be displayed on site
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
            'separator' => false
        );
        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_module'),
            'href'      => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true),
            'separator' => ' :: '
        );
        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('extension/total/quantity_taxation', 'user_token=' . $this->session->data['user_token'], true),
            'separator' => ' :: '
        );
          
        $data['action'] = $this->url->link('extension/total/quantity_taxation', 'user_token=' . $this->session->data['user_token'], true); // URL to be directed when the save button is pressed
     
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true); // URL to be redirected when cancel button is pressed
        
		// This block parses the input field for tax name
        if (isset($this->request->post['total_quantity_taxation_name'])) {
            $data['total_quantity_taxation_name'] = $this->request->post['total_quantity_taxation_name'];
        } else {
            $data['total_quantity_taxation_name'] = $this->config->get('total_quantity_taxation_name');
        }
		
        // This block parses the checkbox status for currency
        if (isset($this->request->post['total_quantity_taxation_curr'])) {
            $data['total_quantity_taxation_curr'] = $this->request->post['total_quantity_taxation_curr'];
        } else {
            $data['total_quantity_taxation_curr'] = $this->config->get('total_quantity_taxation_curr');
        }
		
		// This block parses the checkbox status for language
		if (isset($this->request->post['total_quantity_taxation_lang'])) {
            $data['total_quantity_taxation_lang'] = $this->request->post['total_quantity_taxation_lang'];
        } else {
            $data['total_quantity_taxation_lang'] = $this->config->get('total_quantity_taxation_lang');
        } 
		
		// This block parses the input for customer_group
		if (isset($this->request->post['total_quantity_taxation_customer_group'])) {
            $data['total_quantity_taxation_customer_group'] = $this->request->post['total_quantity_taxation_customer_group'];
        } else {
            $data['total_quantity_taxation_customer_group'] = $this->config->get('total_quantity_taxation_customer_group');
        } 
		
		// This block parses the input for geo_zone
		if (isset($this->request->post['total_quantity_taxation_geo_zone'])) {
            $data['total_quantity_taxation_geo_zone'] = $this->request->post['total_quantity_taxation_geo_zone'];
        } else {
            $data['total_quantity_taxation_geo_zone'] = $this->config->get('total_quantity_taxation_geo_zone');
        }
		
		// This block parses the input for range from
		$data['total_quantity_taxation_range'] = array();
		if (isset($this->request->post['quantity_taxation_range'])) {
            $data['total_quantity_taxation_range'] = $this->request->post['total_quantity_taxation_range'];
        } else {
            $data['total_quantity_taxation_range'] = $this->config->get('total_quantity_taxation_range');
			if(!is_array($data['total_quantity_taxation_range'])){
				$data['total_quantity_taxation_range'] = array();
			}	
        }
		
		// This block parses the input for range from
		if (isset($this->request->post['total_quantity_taxation_sort_order'])) {
            $data['total_quantity_taxation_sort_order'] = $this->request->post['total_quantity_taxation_sort_order'];
        } else {
            $data['total_quantity_taxation_sort_order'] = $this->config->get('total_quantity_taxation_sort_order');
        }
          
        // This block parses the status (enabled / disabled)
        if (isset($this->request->post['total_quantity_taxation_status'])) {
            $data['total_quantity_taxation_status'] = $this->request->post['total_quantity_taxation_status'];
        } else {
            $data['total_quantity_taxation_status'] = $this->config->get('total_quantity_taxation_status');
        }
		
		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}
		

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/total/quantity_taxation', $data));

    }

    /* Function that validates the data when Save Button is pressed */
    protected function validate() {
 
        // Block to check the user permission to manipulate the module
        if (!$this->user->hasPermission('modify', 'extension/total/quantity_taxation')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
 
 
        // Block returns true if no error is found, else false if any error detected
        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
}