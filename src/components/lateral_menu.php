<?php

require_once("src/middleware/session.php");

$lm_links = array(
    "user" => array(
        array("icon_url" => "https://img.icons8.com/wired/344/ffffff/circled-user.png", "link_url" => "/dashboard.php", "link_text" => "Dashboard", "method" => "GET"),
        array("icon_url" => "https://img.icons8.com/wired/344/ffffff/bulleted-list.png", "link_url" => "/items.php", "link_text" => "Items", "method" => "GET"),
        array("icon_url" => "https://img.icons8.com/ios-glyphs/344/ffffff/shopping-cart--v1.png", "link_url" => "/cart.php", "link_text" => "Cart", "method" => "GET"),
        array("icon_url" => "https://img.icons8.com/windows/344/ffffff/edit-online-order.png", "link_url" => "/orders.php", "link_text" => "Orders", "method" => "GET"),
        array("icon_url" => "https://img.icons8.com/wired/344/ffffff/logout-rounded-left.png", "link_url" => "/api/logout.php", "link_text" => "Logout", "method" => "POST")
    ),
    "admin" => array(
        array("icon_url" => "https://img.icons8.com/wired/344/ffffff/circled-user.png", "link_url" => "/admin/dashboard.php", "link_text" => "Dashboard", "method" => "GET"),
        array("icon_url" => "https://img.icons8.com/ios-glyphs/344/ffffff/shopping-cart--v1.png", "link_url" => "/admin/orders.php", "link_text" => "Orders", "method" => "GET"),
        array("icon_url" => "https://img.icons8.com/wired/344/ffffff/logout-rounded-left.png", "link_url" => "/api/logout.php", "link_text" => "Logout", "method" => "POST")
    )
);

// Return the html code of the lateral menu
function show_lateral_menu(string $opened_section, string $user_type): string
{
    global $lm_links;
    global $session;
    
    $lm_html_links = '';
    foreach($lm_links[$user_type] as $lm_link) {
        if ($lm_link["link_text"] != $opened_section) {
            $lm_html_links .= add_link($lm_link["icon_url"], $lm_link["link_url"], $lm_link["link_text"], $lm_link["method"]);
        }
    }

    $headers = add_header();

    return "
        <div class=\"lateral_menu\">
            <div class=\"lm_header\">
                $headers
            </div>
            <div class=\"lm_body\">
                $lm_html_links
                <p class=\"lm_link_text\" style=\"position: absolute; left: 10px; bottom: 10px; font-size: small;\">" . $session->get_user()->email . "</p>
            </div>
        </div>
    ";
}

// Return the header of the lateral menu
function add_header()
{
    return '<img class="lm_title" src="https://peroni.it/wp-content/themes/birraperoni/assets/svg/peroni.svg">';
}

// Return the html code of a link in the lateral menu
function add_link(string $icon_url, string $link_url, string $link_text, string $link_method): string
{
    $img_html_code = "<img class=\"lm_link_img\" src=\"$icon_url\"  alt=\"$link_text icon\">";
    $link_text = "<p class=\"lm_link_text\">$link_text</p>";
    $link_text = (!empty($icon_url)) ? $img_html_code . $link_text : $link_text;
    return "
        <form class=\"lm_link\" method=\"$link_method\" action=\"$link_url\">
            <button class=\"hidden_submit\">
                $link_text
            </button>
        </form>
    ";
}

?>