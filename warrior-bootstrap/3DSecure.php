<?php
 require('includes/application_top.php');
//session_start();
/*GET POST VARIABLES AND SET SESSION VARIABLES*/
$_SESSION['CardSave_Direct_ACSURL'] = $_POST['ACSURL'];
$_SESSION['CardSave_Direct_PaREQ'] = $_POST['PaReq'];
$_SESSION['CardSave_Direct_MD'] = $_POST['MD'];
$_SESSION['CardSave_Direct_TermURL'] = $_POST['TermUrl'];
$_SESSION['CardSave_Direct_Process3DSURL'] = $_POST['Process3DSURL'];

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<link rel="stylesheet" href="css/menu.css" media="screen">
<link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">
<link href="css/liteaccordion.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="ext/jquery/ui/redmond/jquery-ui-1.8.22_create.css" />
<script type="text/javascript" src="ext/jquery/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="ext/jquery/ui/jquery-ui-1.8.22.min.js"></script>
<script src="js/jquery-1.9.0.min.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
		<script src="js/hoverIntent.js"></script>
		<script src="js/superfish.js"></script>
        <script src="js/jquery.easing.1.3.js"></script>
        <script src="js/jquery.cycle.all.js"></script>
        <script src="js/liteaccordion.jquery.min.js"></script>
        <script src="js/jquery.mousewheel.min.js"></script>
<script src="js/jquery.mCustomScrollbar.min.js"></script>
        <script>
		$(document).ready(function() {
   
        $('.shop_cart_content').mCustomScrollbar();
		});
		</script>
        </head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="1024px" cellspacing="3" cellpadding="3">
<tr>
<td> <?php tep_draw_separator('pixel_trans.gif', '100%', '5');?>
</td></tr>
<tr><td align="center">
<iframe frameborder="0" src="<?php echo $_SESSION['CardSave_Direct_TermURL']; ?>" id="cardsave-direct-3ds-iframe" width="370" height="383"></iframe>
</td></tr></table>
</body></html>