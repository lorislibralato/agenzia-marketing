<?php

require_once("src/middleware/checks.php");
require_once("src/middleware/request.php");
require_once("src/repositories/reservation_repo.php");
require_once("src/dtos/update_status.php");

allowed_methods(["POST"]);
need_warehouse();

try {
    $dto = UpdateStatusDto::from_array($_POST);
} catch (ValidateDtoError $e) {
    $session->add_error("order", "order doesn't exist");
    header("location: /admin/orders.php");
    exit();
}

$connection = DbManager::build_connection_from_env();
$reservation_repo = new ReservationRepo($connection);

if (!$reservation_repo->update_status($dto->id, $dto->status)) {
    $session->add_error("order", "error updating status");
}

header("location: /admin/order.php?id=".$dto->id);

?>