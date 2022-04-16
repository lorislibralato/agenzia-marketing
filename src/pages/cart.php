<?php

require_once("src/repositories/cart_item_repo.php");
require_once("src/middleware/checks.php");
require_once("src/middleware/request.php");
require_once("src/dtos/add_to_cart.php");

allowed_methods(["GET", "POST"]);
need_logged();

if (is_post()) {
    try {
        $dto = AddToCartDto::from_array($_POST);
    } catch (ValidateDtoError $e) {
        header("location: /items.php");
        exit();
    }
    
    $user = $session->get_user();
    $connection = DbManager::build_connection_from_env();
    $cart_item_repo = new CartItemRepo($connection);

    try {
        $cart_item_repo->add_cart_item_tx($user->id, $dto);
    } catch (Exception $e) {
        $session->add_error("cart", "error adding to the cart");
    }

    header("location: /cart.php");
    exit();
}



?>