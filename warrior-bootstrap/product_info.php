<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!isset($HTTP_GET_VARS['products_id'])) {
    tep_redirect(tep_href_link(FILENAME_DEFAULT));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);

  $product_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
  $product_check = tep_db_fetch_array($product_check_query);

  require(DIR_WS_INCLUDES . 'template_top.php');

  if ($product_check['total'] < 1) {
?>
  
<div class="contentContainer">
  <div class="contentText">
    <div class="alert alert-warning"><?php echo TEXT_PRODUCT_NOT_FOUND; ?></div>
  </div>

  <div class="pull-right">
    <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'glyphicon glyphicon-chevron-right', tep_href_link(FILENAME_DEFAULT)); ?>
  </div>
</div>

<?php
  } else {
    $product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, pd.products_image_spec, pd.products_links, pd.products_history, pd.products_spec, p.products_image,p.products_image_med, p.products_image_lrg, p.products_image_sm_1, p.products_image_xl_1, p.products_image_sm_2, p.products_image_xl_2, p.products_image_sm_3, p.products_image_xl_3, p.products_image_sm_4, p.products_image_xl_4, p.products_image_sm_5, p.products_image_xl_5, p.products_image_sm_6, p.products_image_xl_6, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
    $product_info = tep_db_fetch_array($product_info_query);

    tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and language_id = '" . (int)$languages_id . "'");

    if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
      $products_price = '<span class="special-price"><b>Was <del>' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</del></b></span><br /> <span class="productpageSpecialPrice">Now Only ' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
    } else {
      $products_price = '<span class="productpageSpecialPrice">Our Price Only ' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
    }

    if (tep_not_null($product_info['products_model'])) {
      $products_name = $product_info['products_name'] . '<br /><small>[' . $product_info['products_model'] . ']</small>';
    } else {
      $products_name = $product_info['products_name'];
    }
?>

<?php echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')). 'action=add_product', 'NONSSL'), 'post', 'class="form-horizontal" role="form"'); ?>
<div class="page-header">
  <h1 class="pull-right"><?php echo $products_price; ?></h1>
  <h1><?php echo $products_name; ?></h1>
</div>

<?php
  if ($messageStack->size('product_action') > 0) {
    echo $messageStack->output('product_action');
  }
?>

<div class="contentContainer">
  <div class="contentText">

<?php
    if (tep_not_null($product_info['products_image'])) {
      $photoset_layout = '1';

      $pi_query = tep_db_query("select image, htmlcontent from " . TABLE_PRODUCTS_IMAGES . " where products_id = '" . (int)$product_info['products_id'] . "' order by sort_order");
      $pi_total = tep_db_num_rows($pi_query);

      if ($pi_total > 0) {
        $pi_sub = $pi_total-1;

        while ($pi_sub > 5) {
          $photoset_layout .= 5;
          $pi_sub = $pi_sub-5;
        }

        if ($pi_sub > 0) {
          $photoset_layout .= ($pi_total > 5) ? 5 : $pi_sub;
        }
	
	
   
?>

    <div id="piGal" data-imgcount="<?php echo $photoset_layout; ?>">

<?php
        $pi_counter = 0;
        $pi_html = array();

        while ($pi = tep_db_fetch_array($pi_query)) {
          $pi_counter++;

          if (tep_not_null($pi['htmlcontent'])) {
            $pi_html[] = '<div id="piGalDiv_' . $pi_counter . '">' . $pi['htmlcontent'] . '</div>';
          }

          echo tep_image(DIR_WS_IMAGES . $pi['image'], '', '', '', 'id="piGalImg_' . $pi_counter . '"');
        }
		
?>

    </div>

<?php
        if ( !empty($pi_html) ) {
          echo '    <div style="display: none;">' . implode('', $pi_html) . '</div>';
        }
      } else {
		$photoset_layout=1;		  
		   if (tep_not_null($product_info['products_image_sm_1'])) {
	$photoset_layout ++;
	}
	
    if (tep_not_null($product_info['products_image_sm_2'])) {
	$photoset_layout ++;
	}
	
    if (tep_not_null($product_info['products_image_sm_3'])) {
	$photoset_layout ++;
	}
	
    if (tep_not_null($product_info['products_image_sm_4'])) {
	$photoset_layout ++;
	}
	
	?>
    
    <div class="imageGroup">
        <div id="piGal2">
      <?php echo tep_image(DIR_WS_IMAGES . $product_info['products_image'], addslashes($product_info['products_name'])); ?>
    </div>
	<div id="piGal" data-imgcount="<?php echo $photoset_layout-1; ?>">
	<?php
	$i=1;
    if($photoset_layout>2){
	while($i<$photoset_layout){
		echo tep_image(DIR_WS_IMAGES . $product_info['products_image_sm_'.$i], '', '', '', 'id="piGalImg_' . $i . '"');
		$i++;
	}}
?>
</div>
   <?php if($product_info['products_spec'] !=NULL){?> 
      <div class="specs">         
      <h4>Specifications</h4>
      <table width="300px" cellpadding="0" cellspacing="0" >
              
              <tr><td>
            
<?php $product_specs=strip_tags($product_info['products_spec']);
$specs = preg_split('[!]', $product_specs);
	for($i=0;$i<sizeof($specs);$i++){
		list($spec_name, $spec_size) = preg_split('[:]', $specs[$i]);
?>
	<tr>
             <td height="30px"><table width="100%"><tr><td width="200px"><div class="spec_bar" width="200px"><div style="width:<?php echo $spec_size;?>%"></div></div></td><td align="center"><b><?php echo $spec_name;?></b></td></tr></table></td></tr>
<?php		
		
	}
	?> </tr></td></table> </div><?php
   }
   ?>


</div>
<?php
      }
    }
?>
  <?php if($product_info['products_image_spec'] != NULL){
				  $product_specs=strip_tags($product_info['products_image_spec']);
$specs = preg_split('[!]', $product_specs);
	echo '<div id="imageSpecs">';
	echo '<table><tr><td><table><tr>';
	$row=0;
	for($i=0;$i<sizeof($specs)-1;$i++){
		$row++;
		list($spec_image, $spec_info) = preg_split('[:]', $specs[$i]);
		//echo $spec_image . $spec_info;
		echo '<td><table cellpadding="0" cellspacing="0" align="center"><tr><td>' . tep_image(DIR_WS_IMAGES . 'specs/'.$spec_image.'.gif', $spec_image,'', '', 'hspace="1" vspace="2" name='.$spec_image.'') .'</td></tr><tr><td align="center" style="font-size:14px; font-weight:bold;">'. $spec_info . '</td></tr></table></td>';
		//if($row==3){ echo'</tr><tr>';}
	}
	echo '</td></tr></table></td></tr></table></div>';
			  }?>


<div class="features">
<?php echo stripslashes($product_info['products_description']); ?>

<?php
    $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
    $products_attributes = tep_db_fetch_array($products_attributes_query);
    if ($products_attributes['total'] > 0) {
?>

    <h4><?php echo TEXT_PRODUCT_OPTIONS; ?></h4>

    <p>
<?php
      $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_name");
      while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
        $products_options_array = array();
        $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "' order by pa.products_options_sort_order");
        while ($products_options = tep_db_fetch_array($products_options_query)) {
          $products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);
          if ($products_options['options_values_price'] != '0') {
            $products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
          }
        }

        if (is_string($HTTP_GET_VARS['products_id']) && isset($cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']])) {
          $selected_attribute = $cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']];
        } else {
          $selected_attribute = false;
        }
?>
      <strong><?php echo $products_options_name['products_options_name'] . ':'; ?></strong><br /><?php echo tep_draw_pull_down_menu('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, $selected_attribute, 'style="width: 200px;"'); ?><br />
<?php
      }
?>
    </p>

<?php
    }
?>
</div>
      <?php  if($product_info['products_history']!=NULL){?>
      <div role="tabpanel" class="history">
             <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#history" aria-controls="history" role="tab" data-toggle="tab">More Info</a></li>
    <?php //<li role="presentation"><a href="#ordering" aria-controls="ordering" role="tab" data-toggle="tab">Ordering</a></li>?>
    <li role="presentation"><a href="#delivery" aria-controls="delivery" role="tab" data-toggle="tab">Delivery</a></li>
    <li role="presentation"><a href="#returns" aria-controls="returns" role="tab" data-toggle="tab">Returns</a></li>
    </ul>
    <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="history">
			<p><?php echo stripslashes($product_info['products_history']); ?></p>
	</div>
   <div role="tabpanel" class="tab-pane" id="ordering">
        <p>Once you have placed an order with us you will receive an email confirmation that you have placed an order including an invoice for the goods you have ordered. Your order will be marked as pending until a member of our dedicated team have processed it, at which point you will receive an email notification that your order is processing. Whilst your goods are processing a member of our dedicated team will be picking you goods from our warehouse, securely wrapping your parcel(s) & preparing it for dispatch with the appropriate courier. It may take us up to 5 working days to process your order, however if the items you have ordered are in stock at the time & your order is placed before 12 pm on a working day the we aim to despatch your order on the same day.</p>
        <p>Once we have dispatched your order you will receive an email notification including a tracking number & a link so that you can track the progress of your delivery. You can also log into your account at Airsoft-Warrior and use the link associated with your order to track your delivery. Once we have fully prepared your order for collection by our couriers & entered it onto their systems with tracking numbers you will receive the dispatch notification, however please note that it may not show up on the tracking links we send you until it has been collected & is in transit.</p>
        <p>If you should have any questions regarding your delivery or our shipping policy please email our team shipping@Airsoft-Warrior.com.</p>
</div>
   <div role="tabpanel" class="tab-pane" id="delivery">
<p>To the Mainland UK we offer a next working day courier service with UK Mail at a flat rate of £ 7.99. Your order is fully tracked form the moment you place an order until the moment it arrives at your door. If you select this service, UK Mail will make an effort to warn you ahead of time when they expect to deliver your order by text message if you provide us with a mobile phone number with your account. If you are not available to receive your order then you will have a chance to easily rearrange delivery for another more suitable time. Should you not be available to receive your delivery at the time you have arranged for it to be, delivered then UK Mail will card you to let you know they have tried to deliver your order & it is then up to you to contact them to re-arrange a second delivery attempt at a time that is more convenient for you, you can also arrange to collect your order form your local depot. Unfortunately we are not able to offer this service in some parts of the UK e.g the Scottish Highlands & Channel islands at the present time.</p>
<p>
We also off a 1-3 day tracked service with Royal Mail. The Price for this service is weight dependant and will be calculated at the checkout when you place your order. This service is available throughout the UK. For you security & peace of mind we only offer fully tracked services so that you know the location & status of your order from the moment it leaves us until the moment that you receive it.</p>
<p>
You are able to leave special delivery instructions when placing your order in the comments box during the checkout process. If you have a hard to find address, or are able & happy to have a neighbour sign for your order on your behalf please let us know & we will pass this information on to our couriers so that you receive your order as quickly & efficiently as possible.</p>
   </div>
        
   <div role="tabpanel" class="tab-pane" id="returns">
		<p>If you no longer require your goods before we have dispatched them to you please contact our support team, support@Airsoft-Warrior.com, as soon as possible so that they can cancel your order and organise a refund.  When contacting our support team please include your full name, telephone number and order number so that we can respond to you in a timely manner.</p><p>

You have the right to cancel your order within 7 days commencing from the day you receive the goods, the goods must be returned to us within 14 days of you contacting us at support@Airsoft-Warrior.com to cancel the order and request the refund.</p>
<p>
Refunds will be processed once the returned items are received back to us and checked by our returns team.
</p><p>
We will refund you on the process you used to pay (Paypal instant payment/credit/debit card) once we have received and processed the item(s) (please allow up to 14 working days).
</p><p>
Faulty Item(s)
</p><p>
Please do not try to repair the item(s) yourself as this will invalidate your warranty. All returns are thouroughly checked and tested (Please allow up to 14 days for our return team to inspect your item).
</p><p>
If you believe your item is faulty please contact support@airsoft-warrior.com or alternatively call us on 01392 581209 ensuring you have your order information readily available.
</p>
<p>
Damaged during transit
</p><p>
Should you receive your item(s) and they have been damaged during transit to you then please contact us at support@Airsoft-Warrior.com as soon as possible (within 7 days). Please include as much information as you are able ie. Full name, contact number,e-mail, order number. We will reply as soon as possible to request any extra information we may need and then arrange for the courier company responsible to collect the item in question from you. As soon as Airsoft-Warrior receives confirmation from the courier that the item in question has been collected Airsoft-Warrior will send you a replacement or offer a refund. Please note replacements could take up to 5 working days and refunds 14 days.
</p><p>
Please do not sign for the item(s) if the outside of the packaging seems to be mishandled/damaged.
</p><p>Please find our <a href="https://www.airsoft-warrior.com/conditions.php">Full Policy Here</a> </p>
   </div>

   </div>
    
    
    
    
    </div><?php }?>
    <div class="clearfix"></div>

<?php
    if ($product_info['products_date_available'] > date('Y-m-d H:i:s')) {
?>

    <div class="alert alert-info"><?php echo sprintf(TEXT_DATE_AVAILABLE, tep_date_long($product_info['products_date_available'])); ?></div>

<?php
    }
?>

  </div>

<?php
    $reviews_query = tep_db_query("select count(*) as count from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' and reviews_status = 1");
    $reviews = tep_db_fetch_array($reviews_query);
?>

  <div class="buttonSet">
    <span class="buttonAction"><?php echo tep_draw_hidden_field('products_id', $product_info['products_id']) . tep_draw_button(IMAGE_BUTTON_IN_CART, 'glyphicon glyphicon-shopping-cart', null, 'primary', NULL,'btn btn-success"' ); ?></span>

    <?php echo tep_draw_button(IMAGE_BUTTON_REVIEWS . (($reviews['count'] > 0) ? ' (' . $reviews['count'] . ')' : ''), 'glyphicon glyphicon-comment', tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params()),NULL, NULL,'btn btn-info"'); ?>
    
<?php //wishlist?>
<?php echo tep_draw_button(TEXT_ADD_WISHLIST, 'clipboard', null, 'primary', array('params' => 'name="wishlist" value="wishlist"')); ?>

  </div>
  
  <div class="row">
    <?php echo $oscTemplate->getContent('product_info'); ?>
  </div>
<div class='shareaholic-canvas' data-app='share_buttons' data-app-id='14679289'></div>
<?php
  include(DIR_WS_MODULES . 'related_products.php');
    if ((USE_CACHE == 'true') && empty($SID)) {
      echo tep_cache_also_purchased(3600);
    } else {
      include(DIR_WS_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS);
    }
?>

</div>

</form>

<?php
  }
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
