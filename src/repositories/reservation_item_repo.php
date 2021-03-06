<?php

require_once("manager.php");
require_once("src/entities/item.php");
require_once("src/entities/cart_item.php");
require_once("src/entities/product.php");
require_once("src/entities/reservation_item.php");

class ReservationItemRepo extends DbManager
{

    function parse_fetch(PDOStatement $statement): array
    {
        $list = array();
        // metadata of the query result
        $metadata = new QueryMetadata($statement);

        // iterate over rows
        while ($row = $statement->fetch(PDO::FETCH_NUM)) {
            
            // build the temp reservationItem from the row
            $reservation_item = ReservationItem::build_from_row($metadata, $row);

            // add the reservation_item in the list
            $list[$reservation_item->reservation_id][$reservation_item->item_id] = $reservation_item;      
        }

        return $list;
    }

    // get reservation_items of a user by its id
    function get_by_reservation_id(int $reservation_id): ?array
    {
        $stmt = $this->get_connection()->prepare("
        SELECT * FROM reservation_item
        LEFT JOIN item ON item.id = reservation_item.item_id
        LEFT JOIN product ON item.product_sku = product.sku
        WHERE reservation_item.reservation_id = :reservation_id
        ORDER BY product.name ASC;
        ");

        if ($stmt->execute(["reservation_id" => $reservation_id])) {
            $reservations = $this->parse_fetch($stmt);
            return $reservations;
        }

        return null;
    }

    function add_from_cart(int $user_id, int $reservation_id): bool
    {
        $stmt = $this->get_connection()->prepare("
        INSERT INTO reservation_item (reservation_id, item_id, quantity)
        SELECT :reservation_id, item_id, quantity
        FROM cart_item
        WHERE user_id = :user_id;
        ");

        if ($stmt->execute([
            "reservation_id" => $reservation_id,
            "user_id" => $user_id
        ])) {
            return $stmt->rowCount() > 0;
        }

        return false;
    }
}

?>