<?php


/*
 * Returns the four most recent products, using the order of the elements int he array
 * @return  array   a list of the last four products in the array;
                    the most recent product is the last one in the array

*/
function get_products_recent() {

    require(ROOT_PATH . "inc/database.php");

    try {
        $results = $db->query("
            SELECT name, price, img, sku, paypal
            FROM products
            ORDER BY sku DESC
            LIMIT 4");
    } catch (Exception $e) {
        echo "Data could not be retrieved from the database.";
        exit;
    }

    $recent = $results->fetchAll(PDO::FETCH_ASSOC);
    $recent = array_reverse($recent);

    return $recent;
}

/*
 * Looks for a search term in the product names
 * @param string $s the search term
 * @return array   a list of the products that contain the search term in their name
*/
function get_products_search($s) {

    require(ROOT_PATH . "inc/database.php");

    try {
        // WHERE name LIKE looks for anything like
        // the search term, but it has to be used
        // with percent signs around the word
        // therefore, we have to bindValue instead of bindParam
        $results = $db->prepare("
            SELECT name, price, img, sku, paypal
            FROM products
            WHERE name LIKE ?
            ORDER BY sku");
        $results->bindValue(1,"%" . $s . "%");
        $results->execute();
    } catch (Exception $e) {
        echo "Data could not be retrieved from the database.";
        exit;
    }

    $matches = $results->fetchAll(PDO::FETCH_ASSOC);

    return $matches;
}

function get_products_count() {

    require(ROOT_PATH . "inc/database.php");

    try {
        $results = $db->query("
            SELECT COUNT(sku)
            FROM products");
    } catch (Exception $e) {
        echo "Data could not be retrieved from the database.";
        exit;
    }

    //fetchColumn only gets one row from a column
    //the one parameter is the number of the ro
    return intval($results->fetchColumn(0));
}

// we want this function to query the database for
// this specific subset of shirts instead of getting
// all the products and narrowing them down
function get_products_subset($positionStart, $positionEnd){

    $offset = $positionStart - 1;
    $rows = $positionEnd - $positionStart + 1;

    require(ROOT_PATH . "inc/database.php");

    //with limit, the first number represent the offset
    // and the second represents the row count
    // the query starts at the end of the offset
    $results = $db->prepare("
        SELECT name, price, img, sku, paypal
        FROM products
        ORDER BY sku
        LIMIT ?, ?");
    $results->bindParam(1,$offset,PDO::PARAM_INT);
    $results->bindParam(2, $rows,PDO::PARAM_INT);
    $results->execute();
    try {

    } catch (Exception $e) {
        echo "Data could not be retrieved from the database.";
        exit;
    }

    $subset = $results->fetchAll(PDO::FETCH_ASSOC);

    return $subset;
}



/*
 * Returns an array of product information for the product that matches the sku;
 * returns a boolean false if no product matches the sku
 * @param   int     $sku    the sku
 * @return  mixed   array   list of product information for the one matching product
 *                  bool    false if no product matches
*/

function get_product_single($sku) {

    require(ROOT_PATH . "inc/database.php");

    try {
        // the prepare method protects us from sql injections.
        // for the sku, we put a question mark to be the placeholder
        $results = $db->prepare("SELECT name, price, img, sku, paypal FROM products WHERE sku = ?");
        // we can change the placeholder by calling a method on the $results object
        // the bindParam method replaces the placeholder with the value in the $sku variable in a way that it protects it from a sql injection
        $results->bindParam(1, $sku);
        // this line loads the result set into the $results object
        $results->execute();
    } catch (Exception $e) {
        echo "Data could not be retrieved from the database.";
        exit;
    }


    // this will return bool false if there is nothing
    $product = $results->fetch(PDO::FETCH_ASSOC);

    // if no product matches the sku, we do an early return
    // with the boolean false
    if ($product === false) {
        return $product;
    }

    // this code adds a sizes array to the $product
    $product["sizes"] = array();

    // this try statement gets the sizes from the database
    try {
        $results = $db->prepare("
            SELECT size 
            FROM products_sizes ps 
            INNER JOIN sizes s ON ps.size_id = s.id
            WHERE product_sku = ?
            ORDER BY `order`");
        $results->bindParam(1, $sku);
        $results->execute();
    } catch (Exception $e) {
        echo "Data could not be retrieved from the database.";
        exit;
    }

    // load the first size into a variable
    // the argument in the while loops actually executes the
    // command and loads the return value into $row.
    // This contains the first size.
    // at the end of the while loop, it loads the next size
    // into the $row variable 
    // as long as it can fetch a record, the code in the
    // curly brackets is executed
    while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
        $product["sizes"][] = $row["size"];
    }
    return $product;
}




?>