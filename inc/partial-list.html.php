<?php

function get_list_view_html($product) {

    $output = "";

    $output = $output . "<li>";
    $output = $output . '<a href="' . BASE_URL . 'shirts/' . $product["sku"] .  '/">';
    $output = $output . '<img src="' . BASE_URL . $product["img"] . '" alt="' . $product["name"] . '">';
    $output = $output . "<p>View Details</p>";
    $output = $output . "</a>";
    $output = $output . "</li>";
    
    return $output;
}

?>