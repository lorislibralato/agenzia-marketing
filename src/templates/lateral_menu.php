<?php

/* lm = lateral_menu*/

// Return the html code of the lateral menu
function show_lateral_menu(string $opened_section): string
{
    $lm_links = array(
        array("icon_url" => "https://img.icons8.com/ios-filled/344/ffffff/dog-house.png", "link_url" => "index.php", "link_text" => "Home"),
        array("icon_url" => "https://img.icons8.com/wired/344/ffffff/circled-user.png", "link_url" => "login.php", "link_text" => "Account"),
        array("icon_url" => "https://img.icons8.com/wired/344/ffffff/bulleted-list.png", "link_url" => "items.php", "link_text" => "Items"),
        array("icon_url" => "https://img.icons8.com/ios-glyphs/344/ffffff/shopping-cart--v1.png", "link_url" => "login.php", "link_text" => "Cart")
    );

    $lm_html_links = '';
    foreach($lm_links as $lm_link){
        if($lm_link["link_text"] != $opened_section){
            $lm_html_links .= add_link($lm_link["icon_url"], $lm_link["link_url"], $lm_link["link_text"]);
        }
    }

    $html_code = '
                <div class="lateral_menu">
                    <div class="lm_header">
                        ' . add_header() . '
                    </div>
                    <div class="lm_body">
                        ' . $lm_html_links . '
                    </div>
                </div>
                ';

    return $html_code;
}

// Return the header of the lateral menu
function add_header(){
    $html_code = '
                <img class="lm_title" src="https://peroni.it/wp-content/themes/birraperoni/assets/svg/peroni.svg">
                ';

    return $html_code;
}

// Return the html code of a link in the lateral menu
function add_link(string $icon_url, string $link_url, string $link_text): string
{
    $img_html_code = '
                    <img class="lm_link_img" src="' . $icon_url . '" alt="' . $link_text . '_icon">
                    ';
    $link_text = '<p class="lm_link_text">' . $link_text . '</p>';
    $link_text = (!empty($icon_url)) ? $img_html_code . $link_text : $link_text;
    $html_code = '
                <a class="lm_link" href="' . $link_url . '">' . $link_text . '</a>
                ';

    return $html_code;
}

?>