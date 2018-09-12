<div class="panel panel-default">
  <div class="panel-heading">
    <?php echo '<a href="' . tep_href_link('specials.php') . '">' . MODULE_BOXES_SPECIALS_BOX_TITLE . '</a>'; ?>
  </div>
    <div class="panel-body text-center">
 <div id="specialContainer" >
  <?php $rand_prod_query = tep_db_query("select p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where p.products_status = '1' and p.products_id = s.products_id and pd.products_id = s.products_id and pd.language_id = '" . (int)$languages_id . "' and s.status = '1' order by s.specials_date_added desc");
  while($random_product = tep_db_fetch_array($rand_prod_query)){?>
	<div>
    <?php echo '<a href="' . tep_href_link('product_info.php', 'products_id=' . $random_product['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $random_product['products_image'], $random_product['products_name'], '170', '170') . '</a><br /><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $random_product['products_id']) . '">' . $random_product['products_name'] . '</a><br /><span class="special-price">Was <del>' . $currencies->display_price($random_product['products_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</del></span><br /><span class="productSpecialPrice">Now Only ' . $currencies->display_price($random_product['specials_new_products_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</span>'; ?>
  </div>
  <?php }?>
  </div>
  </div>
  <script type="text/javascript">
	$(function() {
		$('#specialContainer').cycle({
			fx: 'fade',
			delay: 4000,
			pause: 1,
			sync: true
		});
	});
	</script>
</div>
