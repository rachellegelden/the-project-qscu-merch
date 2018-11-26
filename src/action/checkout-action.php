/*
Assumptions:
-address is comma delimited
-orders are placed and shipped on the same day
-every shipment is coming from warehouse 1
-price in HasOrder is the cost of quantity * price
*/
<?php

include '../includes/session.php';
include '../includes/db_credentials.php';

try {
    if (isset($_SESSION['fullShippingAddress']) and $_SESSION['totalCost']) {
        $fullShippingAddress = $_SESSION['fullShippingAddress'];
        $totalPrice = $_SESSION['totalCost'];

        //TODO: fix this before merging onto dev
//        $userid = 1;
        $user = $_SESSION['user'];
        $userid = $user->id;

        $mysqli = new mysqli (DBHOST, DBUSER, DBPASS, DBNAME);

        $warehouseId = 1;

        //create shipment- a new shipment will be created for each order (:
        $createShipment = "INSERT INTO shipment(dateShipped, uid, shippedFrom) VALUES (CURRENT_DATE ,?,?)";

        if ($shipment = $mysqli -> prepare($createShipment)) {
            $shipment -> bind_param("ss",$userid, $warehouseId );
            $shipment -> execute();
            echo "<p>Shipment successfully created</p>";
        }
        else {
            throw new Exception();
        }

        //get the shipping number of the shipment that was just made
        $sNo_column_name = "sNo";
        $sNo_table_name = "Shipment";
        $sNo;
        $get_max_sNo = "SELECT MAX(sNo) AS recent FROM Shipment";
        if ($maxSno = $mysqli -> query($get_max_sNo)) {

            while ( $row = $maxSno -> fetch_assoc() ) {
                $sNo = $row['recent'];
            }
        }

        echo "<p>".$sNo."</p>";

        //insert into orders
        $orderInsertSQL = "INSERT INTO Orders(shippingAddress, totalPrice, dateOrdered, uid, sNo ) VALUES (?,?,CURRENT_DATE ,?,?)";
        if ( $user_order = $mysqli -> prepare($orderInsertSQL) ) {
            $user_order -> bind_param("ssss", $fullShippingAddress,$totalPrice, $userid, $sNo);
            $user_order -> execute();
            echo "<p>You successfully made the order</p>";
        }

        else {
            throw new Exception();
        }

        //get max order number (most recent one)
        $oNo;
        $get_max_oNo = "SELECT MAX(oNo) AS recent FROM Orders";
        if ( $maxOno = $mysqli -> query($get_max_oNo)) {

            while ( $row = $maxOno -> fetch_assoc() ) {
                $oNo = $row['recent'];
            }
        }

        //create an order and populate it with items from user's cart
        $user_cart_sql = "SELECT * FROM HasCart WHERE uid = ?";
        if ( $user_cart = $mysqli -> prepare($user_cart_sql) ) {
            $user_cart -> bind_param("s",$userid);
            $user_cart -> execute();

            $result = $user_cart -> get_result();

            echo "<p>".$result -> num_rows."</p>";
            //go thru each item in cart and update db
            while ( $row = $result -> fetch_assoc() ) {
                $pNo = $row['pNo'];
                $size = $row['size'];
                $quantity = $row['quantity'];

                $singluarProductCost;
                $product_cost_sql = "SELECT price FROM Product WHERE pNo = ? AND size = ?";
                if ( $product_cost = $mysqli -> prepare($product_cost_sql) ) {
                    $product_cost -> bind_param("ss", $pNo, $size );
                    $product_cost -> execute();

                    $product_cost_result = $product_cost -> get_result();

                    while ( $product_cost_row = $product_cost_result -> fetch_assoc() ) {
                        $singularProductCost = $product_cost_row['price'];
                    }
                }
                $productNetCost = $singularProductCost * $quantity;

                //watch out for case where user tries to buy something we don't have in inventory
                $update_inv_sql = "UPDATE HasInventory SET quantity = quantity - ? WHERE wNo = ? AND pNo = ? AND size = ? WHERE (quantity - ?) >= 0";
                if ( $update_inv = $mysqli -> prepare($update_inv_sql) ) {
                    $update_inv -> bind_param("ssss", $quantity, $warehouseId, $pNo, $size);
                    $update_inv -> execute();
                }
                //we don't enough of this product in inventory, skip this item, go to next
                else {
                    $update_order_sql = "UPDATE Orders SET totalPrice = totalPrice - ? WHERE shippingAddress = ? AND dateOrdered = CURRENT_DATE  AND uid = ? AND sNo = ?";
                    if ( $update_order = $mysqli -> prepare($update_order_sql) ) {
                        $update_order -> bind_param("ssss", $productNetCost, $fullShippingAddress, $userid, $sNo);
                        $update_order -> execute();
                    }
                    else {
                        throw new Exception();
                    }
                    continue;
                }

                //create order product
                $hasOrder_insert_sql = "INSERT INTO HasOrder(oNo, pNo, size, quantity, price) VALUES (?,?,?,?,?)";
                if ( $hasOrder_insert = $mysqli -> prepare($hasOrder_insert_sql) ) {
                    $hasOrder_insert -> bind_param("sssss", $oNo, $pNo, $size, $quantity, $productNetCost);
                    $hasOrder_insert -> execute();
                }
            }

            //check to make sure that an order has products in it
            $order_product_count_sql = "SELECT COUNT(oNo) as prodCount FROM HasOrder WHERE oNo = ? GROUP BY oNo";
            if ( $order_product_error_check = $mysqli -> prepare($order_product_count_sql) ) {
                echo "<p>the if statement executed</p>";
                $order_product_error_check -> bind_param("s", $oNo);
                $order_product_error_check -> execute();

                $order_product_error_check_result = $order_product_error_check -> get_result();

                if ( $order_product_error_check_result -> num_rows === 0 ) {
                    echo "<p>".$oNo."</p>";
                    $remove_order_sql = "DELETE FROM Orders WHERE oNo = ?";

                    if ( $remove_order = $mysqli -> prepare($remove_order_sql) ) {
                        $remove_order -> bind_param("s", $oNo);
                        $remove_order -> execute();
                    }
                    echo "<p>Our apologies! We do not have the products that you want to order in our inventory</p>";
                    echo "<p><a href = \"../homeWithoutTables.php\" >Return Home</a></p>";
                }
            }
            else {
                echo "<p>it hits the else and skips the if </p>";
            }
        }


    }
    else {
        die();
    }
}

catch (Exception $exception) {
    echo "<p>An exception was thrown</p>";
}
finally {
    $mysqli -> close();
}

