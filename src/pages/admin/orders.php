<?php

require_once("src/middleware/request.php");
require_once("src/middleware/checks.php");
require_once("src/repositories/manager.php");
require_once("src/repositories/reservation_item_repo.php");
require_once("src/repositories/reservation_repo.php");
require_once("src/components/lateral_menu.php");

allowed_methods(["GET"]);
need_warehouse();

$connection = DbManager::build_connection_from_env();
$reservation_repo = new ReservationRepo($connection);
$reservations = $reservation_repo->get_all();

?>

<html lang="en">
    <head>
        <title>Ordini</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="/css/main.css">
    </head>
    <body>
        <?php echo(show_lateral_menu("Orders", "admin")); ?>
        <div class="body_main">
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>User</th>
                        <th>Order date</th>
                        <th>Delivery date</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($reservations as $reservation){
                            $delivery_date = !$reservation->date_delivery ? "---": $reservation->date_delivery->format('d/m/Y');
                            $link = '/admin/order.php?id=' . $reservation->id;

                            echo '
                                <tr>
                                    <td>' . $reservation->id . '</td>
                                    <td>' . $reservation->user->email . '</td>
                                    <td>' . $reservation->date_order->format('d/m/Y') . '</td>
                                    <td>' . $delivery_date . '</td>
                                    <td>' . $reservation->status->string() . '</td>
                                    <td class="order_td_details"><a class="order_btn_details" href="' . $link . '">View details</a></td>
                                </tr>
                            ';
                        }
                    ?>
                </tbody>
            </table>

        </div>

        <script src="/js/main.js"></script>
        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
    </body>
</html>