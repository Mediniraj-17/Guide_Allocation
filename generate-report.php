<?php
session_start();
include 'config.php';

if (!isset($_SESSION['guide_name'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: guide-login.html');
    exit();
}

$guide_name = $_SESSION['guide_name'];

// Fetch accepted requests for this guide
$sql = "SELECT * FROM student_requests WHERE guide_name = ? AND status = 'accepted'";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $guide_name);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Error fetching accepted requests: " . $conn->error);
}

// Generate CSV content
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=accepted_requests.csv');
$output = fopen('php://output', 'w');
fputcsv($output, array('Project Title', 'Domain', 'Member 1', 'USN 1', 'Member 2', 'USN 2', 'Member 3', 'USN 3', 'Member 4', 'USN 4'));

while ($row = $result->fetch_assoc()) {
    fputcsv($output, array(
        $row['project_title'],
        $row['domain'],
        $row['name1'], $row['usn1'],
        $row['name2'], $row['usn2'],
        $row['name3'], $row['usn3'],
        $row['name4'], $row['usn4']
    ));
}

fclose($output);
exit();
?>
