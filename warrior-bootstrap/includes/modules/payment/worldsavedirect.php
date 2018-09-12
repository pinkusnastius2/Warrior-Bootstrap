<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class worldsavedirect {
    var $code, $title, $description, $enabled;

// class constructor
    function worldsavedirect() {
      global $order;

      $this->code = 'worldsavedirect';
      $this->title = MODULE_PAYMENT_WORLDSAVEDIRECT_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_WORLDSAVEDIRECT_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_WORLDSAVEDIRECT_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_WORLDSAVEDIRECT_STATUS == 'True') ? true : false);

      if ((int)MODULE_PAYMENT_WORLDSAVE_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_WORLDSAVEDIRECT_ORDER_STATUS_ID;
      }
	$this->form_action_url = HTTPS_SERVER . '/process_payment.php';
      if (is_object($order)) $this->update_status();
    
//      $this->email_footer = MODULE_PAYMENT_WORLDSAVEDIRECT_TEXT_EMAIL_FOOTER;
    }

// class methods
    function update_status() {
      global $order;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_WORLDSAVEDIRECT_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_WORLDSAVEDIRECT_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $order->billing['zone_id']) {
            $check_flag = true;
            break;
          }
        }

        if ($check_flag == false) {
          $this->enabled = false;
        }
      }
    }

    function javascript_validation() {
      return false;
    }

    function selection() {
      return array('id' => $this->code,
                   'module' => $this->title);
    }

    function pre_confirmation_check() {
      return false;
    }

    function confirmation() {
      $selection = array('id' => $this->code,
            'module' => $this->public_title);

        global $order;

        $today = getdate();

        $months_array = array();
        for ($i=1; $i<13; $i++)
        {
            $months_array[] = array('id' => sprintf('%02d', $i), 'text' => strftime('%B',mktime(0,0,0,$i,1,2000)));
        }

        $year_valid_from_array = array();
        for ($i=$today['year']-10; $i < $today['year']+1; $i++)
        {
            $year_valid_from_array[] = array('id' => strftime('%Y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
        }

        $year_expires_array = array();
        for ($i=$today['year']; $i < $today['year']+10; $i++)
        {
            $year_expires_array[] = array('id' => strftime('%Y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
        }

        $selection['fields'] = array(
		
		array('title' => '<div class="contentContainer"><div class="form-group has-feedback">
      <label for="cardName" class="control-label col-xs-6">' . MODULE_PAYMENT_WORLDSAVEDIRECT_CARD_OWNER.'</label>',
            'field' => '<div class="col-xs-6">' . tep_draw_input_field('CardName', $order->billing['firstname'] . ' ' . $order->billing['lastname'],'id="cardName" placeholder="Card Holders Name"'). '</div>
    </div>'),
            array('title' => '<div class="form-group has-feedback">
      <label for="cardNumber" class="control-label col-xs-6">' . MODULE_PAYMENT_WORLDSAVEDIRECT_CARD_NUMBER .'</label>',
                'field' =>  '<div class="col-xs-6">' . tep_draw_input_field('CardNumber','','id="cardNumber" placeholder="Card Number"'). '</div>
    </div>') ,
            array('title' =>'<div class="form-group has-feedback">
      <label for="expMonth" class="control-label col-xs-6">' .  MODULE_PAYMENT_WORLDSAVEDIRECT_CARD_EXPIRES_MONTH.'</label>',
                'field' => '<div class="col-xs-6">' . tep_draw_pull_down_menu('ExpMonth', $months_array,'', 'required aria-required="true" id="expMonth"'). '</div>
    </div>'),
            array('title' => '<div class="form-group has-feedback">
      <label for="expYear" class="control-label col-xs-6">' . MODULE_PAYMENT_WORLDSAVEDIRECT_CARD_EXPIRES_YEAR.'</label>',
                'field' =>'<div class="col-xs-6">' . tep_draw_pull_down_menu('ExpYear', $year_expires_array,'', 'required aria-required="true" id="expMonth"'). '</div>
    </div>'),

            array('title' => '<div class="form-group has-feedback">
      <label for="cardCVC" class="control-label col-xs-6">' . MODULE_PAYMENT_WORLDSAVEDIRECT_CARD_CVC.'</label>',
                'field' => '<div class="col-xs-6">' . tep_draw_input_field('CV2', '', 'size="5" maxlength="4" id="cardCVC" placeholder="CVC"'). '</div>
    </div>'),
            array('title' => '<div class="form-group has-feedback">
      <label for="issueNumber" class="control-label col-xs-6">' . MODULE_PAYMENT_WORLDSAVEDIRECT_CARD_ISSUE_NUMBER.'</label>',
                'field' => '<div class="col-xs-6">' . tep_draw_input_field('IssueNumber', '', 'size="5" maxlength="2" id="issueNumber" placeholder="Issue No."') . MODULE_PAYMENT_WORLDSAVEDIRECT_CARD_ISSUE_NUMBER_INFO. '</div>
    </div></div>'), 
            array('title' => '',
                'field' => tep_draw_hidden_field('OrderID', date('YmdHis'))),
			
			array('title' => '',
                'field' => tep_draw_hidden_field('Address1', $order->billing['street_address'])),
			array('title' => '',
                'field' => tep_draw_hidden_field('Address2', '')),
			array('title' => '',
                'field' => tep_draw_hidden_field('Address3', '')),
			array('title' => '',
                'field' => tep_draw_hidden_field('Address4', '')),
			array('title' => '',
                'field' => tep_draw_hidden_field('City', $order->billing['city'])),
			array('title' => '',
                'field' => tep_draw_hidden_field('State', $order->billing['state'])),
			array('title' => '',
                'field' => tep_draw_hidden_field('Postcode', $order->billing['postcode'])),
			array('title' => '',
                'field' => tep_draw_hidden_field('Country', $order->billing['country']['iso_code_2'])), 
			array('title' => '',
                'field' => tep_draw_hidden_field('EmailAddress', $order->customer['email_address'])),
			array('title' => '',
                'field' => tep_draw_hidden_field('PhoneNumber', $order->customer['telephone'])),
			array('title' => '',
                'field' => tep_draw_hidden_field('Amount', str_replace(".","",$this->format_raw($order->info['total'])))),
			array('title' => '',
                'field' => tep_draw_hidden_field('CurrencyCode', $Currency)),
            array('title' => '',
                'field' => tep_image(DIR_WS_IMAGES . 'icons/Card-Logos.png'))
				
        );

        return $selection;
    }

    function format_raw($number, $currency_code = '', $currency_value = '') {
      global $currencies, $currency;

      if (empty($currency_code) || !$this->is_set($currency_code)) {
        $currency_code = $currency;
      }

      if (empty($currency_value) || !is_numeric($currency_value)) {
        $currency_value = $currencies->currencies[$currency_code]['value'];
      }

      return number_format(tep_round($number * $currency_value, $currencies->currencies[$currency_code]['decimal_places']), $currencies->currencies[$currency_code]['decimal_places'], '.', '');
    }



    function process_button() {
      return false;
    }

    function before_process() {
      return false;
    }

    function after_process() {
      return false;
    }

    function get_error() {
      return false;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_WORLDSAVEDIRECT_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable WorldSave Direct Payment Module', 'MODULE_PAYMENT_WORLDSAVEDIRECT_STATUS', 'True', 'Do you want to accept credit card payments through worldpay?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now());");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Merchant ID', 'MODULE_PAYMENT_WORLDSAVEDIRECT_MERCHANTID', 'Enter your merchant ID here', '', '6', '1', now());");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Merchant Password', 'MODULE_PAYMENT_WORLDSAVEDIRECT_PASSWORD', 'Enter your merchant password here', '', '6', '1', now());");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_WORLDSAVEDIRECT_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_WORLDSAVEDIRECT_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_WORLDSAVEDIRECT_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_WORLDSAVEDIRECT_STATUS', 'MODULE_PAYMENT_WORLDSAVEDIRECT_ZONE', 'MODULE_PAYMENT_WORLDSAVEDIRECT_ORDER_STATUS_ID', 'MODULE_PAYMENT_WORLDSAVEDIRECT_SORT_ORDER', 'MODULE_PAYMENT_WORLDSAVEDIRECT_MERCHANTID','MODULE_PAYMENT_WORLDSAVEDIRECT_PASSWORD');
    }
  }
?>
