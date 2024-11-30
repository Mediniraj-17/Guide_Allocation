<?php
include 'config.php';

$sql = "SELECT id, guide_name, slots FROM guides";
$result = $conn->query($sql);

$guides = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $guides[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($guides);

$conn->close();
?>
