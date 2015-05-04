<?php

const IMAGE_ADMIN_PREFIX = "../";

function getImageElement($filename) {
    return "<img class='artist' src='/~tcmc01/m2/img/" . (file_exists("img/" . $filename) ? $filename : "placeholder.png") . "' />";
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
    if ( !move_uploaded_file( $image['tmp_name'], "img/" . $newName ) ) {
        throw new RuntimeException('Failed to move uploaded file.');
    }
    return $newName;
}

function redirect($url) {
    header("Location: ".getLink($url)); die();
}

function getLink($url) {
    return "/~tcmc01/m2/" . $url;
}

?>