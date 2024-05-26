<?php
$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $file = './json/user_info.json';
    $currentData = array();

    if (file_exists($file)) {
        $currentData = json_decode(file_get_contents($file), true);
    }

    $currentData[] = $data;
    file_put_contents($file, json_encode($currentData, JSON_PRETTY_PRINT));

    echo json_encode(array('status' => 'success'));
} else {
    echo json_encode(array('status' => 'error'));
}
?>
