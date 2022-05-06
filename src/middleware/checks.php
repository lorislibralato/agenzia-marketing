<?php

require_once("session.php");
require_once("request.php");
require_once("src/entities/user.php");

function need_logged()
{
    global $session;

    if (!$session->is_logged()) {
        header("location: /login.php");
        exit();
    }
}

function need_warehouse()
{
    global $session;
    need_logged();

    if (!$session->get_user()->role->important_than(Role::Warehouse)) {
        header("location: /dashboard.php");
        exit();
    }
}

function redirect_if_logged()
{
    global $session;

    if ($session->is_logged()) {
        if ($session->get_user()->role == Role::Warehouse) {
            header("location: /dashboard.php");
        } else {
            header("location: /admin/dashboard.php");
        }
        
        exit();
    }
    
}

$session = new SessionManager();

?>