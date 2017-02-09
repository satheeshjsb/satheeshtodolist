<?php
include 'db.class.php';
$config = parse_ini_file('config.ini');
$userId = $_REQUEST['userId'];
$action = $_REQUEST['action'];
$db = new db();
if ($action == 'add') {
	$txtProduct = $_REQUEST['txtProduct'];
	$productsQry = "SELECT * FROM products where user_id = '".$userId."' AND product_name = '".$txtProduct."'";
	$productsRes = $db->getAll($productsQry);
	if (count($productsRes) == 0) {
		$insertQry = "INSERT INTO products (product_name, user_id) VALUES ('".$txtProduct."', '".$userId."')";
		$db->execute($insertQry);
		//echo 1;
	} else {
		echo 2; exit;
	}
}
if ($action == 'delete') {
	$pid = $_REQUEST['pid'];
	$deleteQry = "DELETE FROM products WHERE product_id = ".$pid;
	$db->execute($deleteQry);
	//echo 3;
}
if ($action == 'complete') {
	$pid = $_REQUEST['pid'];
	$complete = $_REQUEST['complete'];
	$updateQry = "UPDATE products SET is_completed = ".$complete." WHERE product_id = ".$pid;
	$db->execute($updateQry);
	//echo 4;
}
//exit;
/* else if ($action == 'remove') {
	$deleteQry = "UPDATE projects SET project_path = '' WHERE project_id = ".$pid;
	$db->execute($deleteQry);
}*/
$productsQry = "SELECT * FROM products WHERE user_id = '".$userId."'";
$productsRes = $db->getAll($productsQry);
$itemsQry = "SELECT * FROM products WHERE user_id = '".$userId."' AND is_completed = 0";
$itemsRes = $db->getAll($itemsQry);
foreach ($productsRes as $value) {
	?>
	<div style="padding:10px; border-bottom:1px solid #ccc;">
		<?php
		if ($value['is_completed'] == 1) {
			?>
			<div style="float:left;"><input type="checkbox" name="chkComplete" id="<?php echo $value['product_id'];?>" onclick="completeStatus(this)" checked="checked"></div>
			<div style="float:left; padding-left:10px;color:#cccccc;"><strike><?php echo $value['product_name'];?></strike></div>
			<?php
		} else {
			?>
			<div style="float:left;"><input type="checkbox" name="chkComplete" id="<?php echo $value['product_id'];?>" onclick="completeStatus(this)"></div>
			<div style="float:left; padding-left:10px;"><?php echo $value['product_name'];?></div>
			<?php
		}
		?>
		<div style="float:right;"><a href="javascript:void(0)" onclick="removeProduct(<?php echo $value['product_id'];?>)"><i class="fa fa-times"></i></a></div>
		<div style="clear:both;"></div>
	</div>
	<?php
}
?>
<div id="itemsLeft" style="padding:10px; border-bottom:1px solid #ccc; font-size:12px;"><?php echo count($itemsRes);?> items left</div>