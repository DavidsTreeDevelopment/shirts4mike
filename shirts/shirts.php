<?php 
require_once("../inc/config.php");
require_once(ROOT_PATH . "inc/products.php"); 

if (empty($_GET["pg"])) {
	$current_page = 1;
} else {
	$current_page = $_GET["pg"];
}

$current_page = intval($current_page);

$total_products = get_products_count();
$products_per_page = 8;
$total_pages = ceil($total_products / $products_per_page);

if ($current_page > $total_pages) {
	header("Location: ./?pg=" . $total_pages);
}

if ($current_page < 1) {
	header("Location: ./");
}

$start = (($current_page - 1) * $products_per_page) + 1;
$end = $current_page * $products_per_page;
if ($end > $total_products) {
	$end = $total_products;
}


echo "<pre>";
echo "Total products: ";
echo $total_products;
echo "\nTotal pages ";
echo $total_pages;
echo "\nCurrent Page: ";
echo $current_page;
echo " (" . $start . "-" . $end . ")";
exit;

$products = get_products_subset($start, $end);



?>
<?php 
$pageTitle = "Mike's Full Catalog of Shirts";
$section = "shirts";
include(ROOT_PATH . 'inc/header.php'); ?>

	<div class="section shirts page">

		<div class="wrapper">

			<h1>Mike&rsquo;s Full Catalog of Shirts </h1>
			
			<ul class="products">
				<?php foreach($products as $product) { 

						echo get_list_view_html($product);
					
					} 

				?>
			</ul>
		</div>

	</div>

<?php include(ROOT_PATH . 'inc/footer.php'); ?>