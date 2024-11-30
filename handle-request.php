<?php
session_start();
include 'config.php';

if (!isset($_SESSION['guide_name'])) {
    echo json_encode(['success' => false, 'message' => 'Guide not logged in']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];
    $requestId = $_POST['id'];

    // Fetch guide details
    $guideUsername = $_SESSION['guide_name'];
    $sqlGuide = "SELECT id, allot, slots FROM guides WHERE guide_name = ?";
    $stmtGuide = $conn->prepare($sqlGuide);
    $stmtGuide->bind_param('s', $guideUsername);
    $stmtGuide->execute();
    $resultGuide = $stmtGuide->get_result();

    if ($resultGuide->num_rows > 0) {
        $guide = $resultGuide->fetch_assoc();
        $guideId = $guide['id'];
        $allotted = $guide['allot'];
        $slots = $guide['slots'];

        // Fetch request details to get student details
        $sqlRequest = "SELECT * FROM student_requests WHERE id = ? AND guide_name = ? ";
        $stmtRequest = $conn->prepare($sqlRequest);
        $stmtRequest->bind_param('is', $requestId, $guideUsername);
        $stmtRequest->execute();
        $resultRequest = $stmtRequest->get_result();

        if ($resultRequest->num_rows > 0) {
            if ($action === 'accept') {
                if ($allotted < $slots) {
                    // Update request status to accepted in student_requests
                    $sqlAccept = "UPDATE student_requests SET status = 'accepted' WHERE id = ? AND guide_name = ?";
                    $stmtAccept = $conn->prepare($sqlAccept);
                    $stmtAccept->bind_param('is', $requestId, $guideUsername);
                    if ($stmtAccept->execute()) {
                        // Update guide's allotted count
                        $sqlUpdateGuide = "UPDATE guides SET allot = allot + 1 WHERE id = ?";
                        $stmtUpdateGuide = $conn->prepare($sqlUpdateGuide);
                        $stmtUpdateGuide->bind_param('i', $guideId);
                        if ($stmtUpdateGuide->execute()) {
                            echo json_encode(['success' => true, 'message' => 'Request accepted successfully.']);
                        } else {
                            echo json_encode(['success' => false, 'message' => 'Error updating guide allocation: ' . $conn->error]);
                        }
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Error accepting request: ' . $conn->error]);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'No remaining slots available.']);
                }
            } elseif ($action === 'reject') {
                // Update request status to rejected in student_requests
                $sqlReject = "UPDATE student_requests SET status = 'rejected' WHERE id = ? AND guide_name = ?";
                $stmtReject = $conn->prepare($sqlReject);
                $stmtReject->bind_param('is', $requestId, $guideUsername);
                if ($stmtReject->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Request rejected successfully.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error rejecting request: ' . $conn->error]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid action.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Request details not found or not authorized.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Guide not found.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

$conn->close();
?>
