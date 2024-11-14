<?php
$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $targetDir = "https://www.neti.com.ph/AndroidFileUploadRepo/";
    $targetFile = $targetDir . basename($_FILES['image']['name']);
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        $response['status'] = 'success';
        $response['message'] = 'Image uploaded successfully';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to upload image';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request';
}

echo json_encode($response);
?>


