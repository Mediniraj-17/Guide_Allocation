<?php
include 'config.php';

$action = $_POST['action'];
$id = isset($_POST['id']) ? $_POST['id'] : null;
$guide_name = isset($_POST['guide_name']) ? $_POST['guide_name'] : null;
$slots = isset($_POST['slots']) ? $_POST['slots'] : null;

switch ($action) {
    case 'edit':
        $sql = "UPDATE guides SET slots='$slots' WHERE id='$id'";
        break;
    case 'add':
        $sql = "INSERT INTO guides (guide_name, slots) VALUES ('$guide_name', '$slots')";
        break;
    default:
        $sql = '';
        break;
}

if ($sql && $conn->query($sql) === TRUE) {
    echo "Success";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
