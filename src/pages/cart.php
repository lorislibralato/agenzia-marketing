<?php

require_once("src/repositories/cart_item_repo.php");
require_once("src/middleware/checks.php");
require_once("src/middleware/request.php");

allowed_methods(["GET", "POST"]);
need_logged();

if (is_post()) {

}

$connection = DbManager::build_connection_from_env();

?>