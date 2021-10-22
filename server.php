<?php
require_once 'config/function_helper.php';
require_once 'config/db.php';
require 'config/QueryBuilder.php';
require 'config/Controller.php';
require_once 'controllers/buku.php';
require_once 'controllers/anggota.php';
require_once 'controllers/pinjam.php';

$module = null;
if (isset($_GET['module'])) {
    switch ($_GET['module']) {
        case "buku":
            $module = "Buku";
            break;
        case "anggota":
            $module = "Anggota";
            break;
        case "pinjam":
            $module = "Pinjam";
            break;
        default:
            $module = null;
            break;
    }
}

if ($module == null) {
    response_api(['success' => false, 'message' => 'module must be set']);
}

$action = null;
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case "index":
            $action = "Index";
            break;
        case "view":
            $action = "View";
            break;
        case "create":
            $action = "Create";
            break;
        case "update":
            $action = "Update";
            break;
        case "delete":
            $action = "Delete";
            break;
        default:
            $action = null;
            break;
    }
}

if ($action == null) {
    response_api(['success' => false, 'message' => 'action must be set']);
}

$class = new $module($db);
$action = "action$action";

unset($_GET['module']);
unset($_GET['action']);

// dd("$module->$action()");
$class->$action($_POST ?? $_GET ?? []);
