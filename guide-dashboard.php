<?php
session_start();
include 'config.php';

if (!isset($_SESSION['guide_name'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: guide-login.html');
    exit();
}

$username = $_SESSION['guide_name'];

// Fetch guide requests for this guide
$sql = "SELECT * FROM student_requests WHERE guide_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$guiderequests = $stmt->get_result();

if (!$guiderequests) {
    die("Error fetching guide requests: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guide Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .sidebar {
            width: 220px;
            height: 100vh;
            background-color: #2c3e50;
            color: white;
            position: fixed;
            padding-top: 20px;
        }
        .sidebar h2 {
            text-align: center;
        }
        .sidebar nav ul {
            list-style-type: none;
            padding: 0;
        }
        .sidebar nav ul li {
            margin: 20px 0;
        }
        .sidebar nav ul li a {
            color: white;
            text-decoration: none;
            display: block;
            border-radius: 5px;
        }
        .sidebar .logout {
            margin-top: auto;
            background-color: #e74c3c;
        }
        .sidebar .logout:hover {
            background-color: #2bc0c0;
        }
        .sidebar nav ul li a:hover {
            background-color: #2c3e50;
        }
        .content {
            margin-left: 220px;
            padding: 20px;
        }
        .group {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
        .group button {
            margin-right: 10px;
        }
        .group .status {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Guide Allocation</h2>
        <nav>
            <ul>
                <li><a href="guide-login.html">Back</a></li>
                <li><a href="#">Guidelines</a></li>
                <li><a onclick="downloadReport()">Download Report</a></li>
            </ul>
            <a href="index.html" class="logout">Logout</a>
        </nav>
    </div>

    <div class="content">
        <h1>Guide Dashboard - Requests</h1>
        <?php
        $groupNumber = 1;
        while ($request = $guiderequests->fetch_assoc()): ?>
            <div id="group<?php echo $request['id']; ?>" class="group">
                <h2>Group <?php echo $groupNumber++; ?></h2>
                <p><strong>Project Title:</strong> <?php echo htmlspecialchars($request['project_title']); ?></p>
                <p><strong>Domain:</strong> <?php echo htmlspecialchars($request['domain']); ?></p>
                <p><strong>Team Members:</strong></p>
                <ul>
                    <li><?php echo htmlspecialchars($request['name1']); ?> (USN: <?php echo htmlspecialchars($request['usn1']); ?>)</li>
                    <li><?php echo htmlspecialchars($request['name2']); ?> (USN: <?php echo htmlspecialchars($request['usn2']); ?>)</li>
                    <li><?php echo htmlspecialchars($request['name3']); ?> (USN: <?php echo htmlspecialchars($request['usn3']); ?>)</li>
                    <li><?php echo htmlspecialchars($request['name4']); ?> (USN: <?php echo htmlspecialchars($request['usn4']); ?>)</li>
                </ul>
                <?php if ($request['status'] === 'pending'): ?>
                    <button onclick="handleRequest('accept', <?php echo $request['id']; ?>, '<?php echo $request['guide_name']; ?>')">Accept</button>
                    <button onclick="handleRequest('reject', <?php echo $request['id']; ?>)">Reject</button>
                <?php else: ?>
                    <p class="status"><?php echo ucfirst($request['status']); ?></p>
                <?php endif; ?>
                <button onclick="viewConsent('<?php echo $request['consent_letter']; ?>')">View Consent Letter</button>
            </div>
        <?php endwhile; ?>
    </div>

    <script>
        function handleRequest(action, requestId) {
            if (confirm(`Are you sure you want to ${action} this request?`)) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "handle-request.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            alert(response.message);
                            // Update the UI to reflect the change
                            const groupDiv = document.getElementById(`group${requestId}`);
                            if (action === 'accept') {
                                // Disable buttons
                                groupDiv.querySelectorAll('button').forEach(button => button.disabled = true);
                                groupDiv.querySelector('.status').innerHTML = 'Accepted';
                            } else {
                                groupDiv.remove(); // Remove the rejected request from the UI
                            }
                        } else {
                            alert(response.message);
                        }
                    }
                };
                xhr.send(`action=${action}&id=${requestId}`);
            }
        }

        function viewConsent(consentLetter) {
            window.location.href = 'uploads/' + consentLetter;
        }

        function downloadReport() {
            // Implement download report functionality here
            window.location.href = 'generate-report.php';
        }
    </script>
</body>
</html>
