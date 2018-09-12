<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  if ( (!isset($new_products_category_id)) || ($new_products_category_id == '0') ) {
    $new_products_query = tep_db_query("select p.products_id, p.products_image, p.products_tax_class_id, pd.products_name, if(s.status, s.specials_new_products_price, p.products_price) as products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by p.products_date_added desc limit " . MAX_DISPLAY_NEW_PRODUCTS);
  } else {
    $new_products_query = tep_db_query("select distinct p.products_id, p.products_image, p.products_tax_class_id, pd.products_name, if(s.status, s.specials_new_products_price, p.products_price) as products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c where p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and c.parent_id = '" . (int)$new_products_category_id . "' and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by p.products_date_added desc limit " . MAX_DISPLAY_NEW_PRODUCTS);
  }
/********************************************************************************************************************/
/*									Fading Bestsellers Box, 														*/
/*									Using Javascript for effects													*/
/*									Written By R. Pink																*/
/*									Copyright 2008 Wannacee Media Ltd.												*/
/********************************************************************************************************************/
$query = 'SELECT p.products_id, p.products_image, p.manufacturers_id, p.products_tax_class_id, IF (s.status, s.specials_new_products_price, NULL) AS specials_new_products_price, p.products_price, pd.products_name ';

	if ( defined('FEATURED_PRODUCTS_SPECIALS_ONLY') AND FEATURED_PRODUCTS_SPECIALS_ONLY == 'true' ) {
      $query .= 'FROM ' . TABLE_SPECIALS . ' s LEFT JOIN ' . TABLE_PRODUCTS . ' p ON s.products_id = p.products_id ';
	} else {
      $query .= 'FROM ' . TABLE_PRODUCTS . ' p LEFT JOIN ' . TABLE_SPECIALS . ' s ON p.products_id = s.products_id ';
	}

    $query .= 'LEFT JOIN ' . TABLE_PRODUCTS_DESCRIPTION . " pd ON p.products_id = pd.products_id AND pd.language_id = '" . $languages_id . "'
    LEFT JOIN " . TABLE_FEATURED . "1 f ON p.products_id = f.products_id
    WHERE p.products_status = '1' AND f.status = '1' order by rand($mtm) DESC limit " . MAX_DISPLAY_FEATURED_PRODUCTS;

    $featured_products_query = tep_db_query( $query );	
  $num_featured_products = tep_db_num_rows($featured_products_query);
  

  if ($num_featured_products > 0) {

    $new_prods_content = NULL;

    while ($featured_products = tep_db_fetch_array($featured_products_query)) {
      $featured_prods_content .= '<div class="col-sm-6 col-md-4">';
      $featured_prods_content .= '  <div class="thumbnail equal-height">';
      $featured_prods_content .= '    <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $featured_products['products_image'], $featured_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
      $featured_prods_content .= '    <div class="caption">';
      $featured_prods_content .= '      <p class="text-center"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '">' . $featured_products['products_name'] . '</a></p>';
      $featured_prods_content .= '      <hr>';
      $featured_prods_content .= '      <p class="text-center">' . $currencies->display_price($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</p>';
      $featured_prods_content .= '      <div class="text-center">';
      $featured_prods_content .= '        <div class="btn-group">';
      $featured_prods_content .= '          <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'products_id=' . $new_products['products_id']) . '" class="btn btn-default" role="button">' . SMALL_IMAGE_BUTTON_VIEW . '</a>';
      $featured_prods_content .= '          <a href="' . tep_href_link($PHP_SELF, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $featured_products['products_id']) . '" class="btn btn-success" role="button">' . SMALL_IMAGE_BUTTON_BUY . '</a>';
      $featured_prods_content .= '        </div>';
      $featured_prods_content .= '      </div>';
      $featured_prods_content .= '    </div>';
      $featured_prods_content .= '  </div>';
      $featured_prods_content .= '</div>';
    }
?>

  <h3><?php echo '<a href="'. tep_href_link('BeginnersGuide.php').'">' .  FEATURED1_TITLE . '</a>'; ?></h3>

  <div class="row">
    <?php echo $featured_prods_content; ?>
  </div>

<?php
  }
?>
