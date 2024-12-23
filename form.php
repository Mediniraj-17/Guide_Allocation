<?php
session_start();
include 'config.php';

if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: student-login.html');
    exit();
}
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: student-login.html");
    exit();
}

$username = $_SESSION['username'];
$guide_name = isset($_POST['guide_name']) ? $_POST['guide_name'] : '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guide Allocation Form</title>
    <link rel="stylesheet" href="form.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Guide Allocation</h2>
            <nav>
                <ul>
                    <li><a href="student-dashboard.php">Back</a></li>
                    <li><a href="#">Guidelines</a></li>
                </ul>
            </nav>
            <a href="index.html" class="logout">Logout</a>
        </div>
        <div class="main-content">
            <div class="header">
                <div class="search-container">
                    <input type="text" placeholder="Search for anything...">
                </div>
                <div class="profile">
                    <span class="profile-name">Monisha</span>
                    <span class="profile-role">MCE</span>
                    <div class="notifications">
                        <span class="bell">&#128276;</span>
                    </div>
                </div>
            </div>
            <div class="form-container">
                <h2>Request Form</h2>
                <form id="requestForm" action="request.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="guide_name">Guide Name:</label>
                        <input type="text" id="guide_name" name="guide_name" value="<?php echo htmlspecialchars($guide_name); ?>" readonly>
                        
                    </div>
                    <h3>Group Information</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>USN</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Section</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" id="usn1" name="usn1"></td>
                                <td><input type="text" id="name1" name="name1"></td>
                                <td><input type="email" id="email1" name="email1"></td>
                                <td><input type="text" id="contact1" name="contact1"></td>
                                <td><input type="text" id="section1" name="section1"></td>
                            </tr>
                            <tr>
                                <td><input type="text" id="usn2" name="usn2"></td>
                                <td><input type="text" id="name2" name="name2"></td>
                                <td><input type="email" id="email2" name="email2"></td>
                                <td><input type="text" id="contact2" name="contact2"></td>
                                <td><input type="text" id="section2" name="section2"></td>
                            </tr>
                            <tr>
                                <td><input type="text" id="usn3" name="usn3"></td>
                                <td><input type="text" id="name3" name="name3"></td>
                                <td><input type="email" id="email3" name="email3"></td>
                                <td><input type="text" id="contact3" name="contact3"></td>
                                <td><input type="text" id="section3" name="section3"></td>
                            </tr>
                            <tr>
                                <td><input type="text" id="usn4" name="usn4"></td>
                                <td><input type="text" id="name4" name="name4"></td>
                                <td><input type="email" id="email4" name="email4"></td>
                                <td><input type="text" id="contact4" name="contact4"></td>
                                <td><input type="text" id="section4" name="section4"></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="form-group">
                        <label for="domain">Domain:</label>
                        <input type="text" id="domain" name="domain">
                    </div>
                    <div class="form-group">
                        <label for="projectTitle">Project Title:</label>
                        <input type="text" id="projectTitle" name="projectTitle">
                    </div>
                    <div class="form-group">
                        <label for="consentLetter">Upload Consent Letter:</label>
                        <input type="file" name="consentLetter" accept=".pdf, .doc, .docx" required>
                        
                    </div>
                   
                    <button type="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>

    
</body>
</html>

