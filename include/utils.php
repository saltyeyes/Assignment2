<?php

const DEBUG_MODE = FALSE;

define("IMG_WEBDIR", DEBUG_MODE ? '/img/' : '/~tcmc01/m2/img/');
define("IMG_DIR", DEBUG_MODE ? dirname(dirname(__FILE__))."/img/" : "/home/tcmc01/public_html/m2/img/");

date_default_timezone_set("Australia/Queensland");
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

class AccessLevels {
    const Unregistered = 1;
    const RegularMember = 2;
    const PaidMember = 3;
    const Admin = 4;
}

function getImageElement($filename) {
    return "<img class='artist' src='" . IMG_WEBDIR . (file_exists(IMG_DIR . $filename) ? $filename : "placeholder.png") . "' />";
}

function uploadImage($image, $name) {
    switch ($image['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Exceeded filesize limit.');
        default:
            throw new RuntimeException('Unknown errors.');
    }

    // You should also check filesize here. 
    if ($image['size'] > 4096 * 1024 * 1024) {
        throw new RuntimeException('Exceeded filesize limit.');
    }

    // DO NOT TRUST $_FILES['image']['mime'] VALUE !!
    // Check MIME Type by yourself.
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (false === $ext = array_search(
        $finfo->file($image['tmp_name']),
        array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
        ),
        true
    )) {
        throw new RuntimeException('Invalid file format.');
    }

    $newName = sprintf('%s.%s', $name, $ext);
    if ( !move_uploaded_file( $image['tmp_name'], IMG_DIR . $newName ) ) {
        throw new RuntimeException('Failed to move uploaded file.');
    }
    return $newName;
}

function redirect($url) {
    header("Location: ".getLink($url)); die();
}

function getLink($url) {
    return str_replace("//","/",(DEBUG_MODE ? "/" : "/~tcmc01/m2/") . $url);
}

function dateFromTimestamp($date) {
    return date('l jS \of F Y h:i:s A', $date);
}

?>