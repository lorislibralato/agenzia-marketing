<?php

require_once("manager.php");
require_once("src/entities/item.php");
require_once("src/entities/cart_item.php");
require_once("src/entities/product.php");
require_once("src/dtos/add_to_cart.php");

class CartItemRepo extends DbManager
{

    function parse_fetch(PDOStatement $statement): array
    {
        $list = array();
        // metadata of the query result
        $metadata = new QueryMetadata($statement);

        // iterate over rows
        while ($row = $statement->fetch(PDO::FETCH_NUM)) {
            
            // build the temp cartItem from the row
            $cart_item = CartItem::build_from_row($metadata, $row);

            // add the cart_item in the list
            $list[$cart_item->user_id][$cart_item->item->id] = $cart_item;      
        }

        return $list;
    }

    // get cart_items of a user by its id
    function get_by_user_id(int $user_id): ?array
    {
        $stmt = $this->get_connection()->prepare("
        SELECT * FROM cart_item
        LEFT JOIN item ON item.id = cart_item.item_id
        LEFT JOIN product ON item.product_sku = product.sku
        WHERE cart_item.user_id = :user_id;
        ");

        if ($stmt->execute(["user_id" => $user_id])) {
            $items = $this->parse_fetch($stmt);
            return $items;
        }

        return null;
    }

    function add_or_update(int $user_id, AddToCartDto $dto): bool
    {
        $stmt = $this->get_connection()->prepare("
        INSERT INTO cart_item (user_id, item_id, quantity)
        VALUES (:user_id, :item_id, :quantity)
        ON DUPLICATE KEY UPDATE quantity = quantity + :quantity_update;
        ");

        if ($stmt->execute([
            "user_id" => $user_id,
            "item_id" => $dto->item_id,
            "quantity" => $dto->quantity,
            "quantity_update" => $dto->quantity
        ])) {
            return $stmt->rowCount() > 0;
        }
        
        return false;
    }

    function delete_by_user_id(int $user_id): bool
    {
        $stmt = $this->get_connection()->prepare("
        DELETE FROM cart_item
        WHERE user_id = :user_id;
        ");

        if ($stmt->execute(["user_id" => $user_id])) {
            return $stmt->rowCount() > 0;
        }
        
        return false;
    }
}

?>