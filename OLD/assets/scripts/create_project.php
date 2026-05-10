<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// -------------------- DB CONNECT --------------------
$host = 'localhost';
$db   = 'yibnyzre_cme';
$user = 'yibnyzre_cme_admin';
$pass = 'As792002@';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode([
        'success' => false,
        'message' => "DB Error: " . $conn->connect_error
    ]);
    exit;
}

$conn->set_charset('utf8mb4');

// -------------------- SESSION / AUTH --------------------
session_start();

if (!isset($_SESSION['user']['username']) || empty($_SESSION['user']['username'])) {

    echo json_encode([
        'success' => false,
        'message' => "not_authenticated"
    ]);

    exit;
}

$username = $_SESSION['user']['username'];

// -------------------- HEX ID GENERATOR --------------------
function generateHexId($length = 20) {

    if ($length % 2 !== 0) {
        throw new InvalidArgumentException(
            'Length must be even for bin2hex.'
        );
    }

    return bin2hex(random_bytes((int)$length / 2));
}

// -------------------- UNIQUE PROJECT ID --------------------
function getUniqueProjectId($conn, $maxAttempts = 10) {

    $attempt = 0;

    while ($attempt < $maxAttempts) {

        $attempt++;

        $id = generateHexId(20);

        $check = $conn->prepare(
            "SELECT 1 FROM projects WHERE project_id = ?"
        );

        if ($check === false) {
            throw new RuntimeException(
                "Prepare failed (check): " . $conn->error
            );
        }

        $check->bind_param("s", $id);

        if (!$check->execute()) {

            $err = $check->error;

            $check->close();

            throw new RuntimeException(
                "Execute failed (check): " . $err
            );
        }

        $check->store_result();

        $num = $check->num_rows;

        $check->close();

        if ($num === 0) {
            return $id;
        }
    }

    throw new RuntimeException(
        "Failed to generate a unique project_id"
    );
}

// -------------------- GENERATE PROJECT ID --------------------
try {

    $project_id = getUniqueProjectId($conn);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'message' => 'Failed generating project_id',
        'error' => $e->getMessage()
    ]);

    exit;
}

// -------------------- INPUTS --------------------
$title             = trim($_POST['title'] ?? '');
$short_description = trim($_POST['short_description'] ?? '');
$description       = trim($_POST['description'] ?? '');
$budget            = trim($_POST['budget'] ?? '');
$expiry_date       = trim($_POST['expiry_date'] ?? '');
$billing_cycle     = trim($_POST['billing'] ?? '');
$links             = trim($_POST['links'] ?? '');

// -------------------- VALIDATION --------------------
if ($title === "" || $description === "") {

    echo json_encode([
        'success' => false,
        'message' => "Missing required fields"
    ]);

    exit;
}

if ($links === null) {
    $links = '';
}

// -------------------- INSERT PROJECT --------------------
$sql = "
INSERT INTO projects 
(
    project_id,
    username,
    title,
    short_description,
    description,
    budget,
    billing_cycle,
    links,
    posted_at,
    expiry_date,
    created_at,
    updated_at
) 
VALUES 
(
    ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, NOW(), NOW()
)
";

$stmt = $conn->prepare($sql);

if ($stmt === false) {

    echo json_encode([
        'success' => false,
        'message' => 'Prepare failed',
        'error' => $conn->error
    ]);

    exit;
}

$bind = $stmt->bind_param(
    "sssssssss",
    $project_id,
    $username,
    $title,
    $short_description,
    $description,
    $budget,
    $billing_cycle,
    $links,
    $expiry_date
);

if ($bind === false) {

    echo json_encode([
        'success' => false,
        'message' => 'bind_param failed',
        'error' => $stmt->error
    ]);

    $stmt->close();
    exit;
}

// -------------------- EXECUTE INSERT --------------------
if ($stmt->execute()) {

    // -------------------- FETCH CONSULTANTS --------------------
    $consultant_sql = "
        SELECT name, email
        FROM consultants
        WHERE email IS NOT NULL
        AND email != ''
    ";

    $consultants = $conn->query($consultant_sql);

    $mail_sent = 0;
    $mail_failed = 0;

    if ($consultants) {

        // -------------------- MAIL CONFIG --------------------
        $subject = "New Project Alert - ConsultME";

        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8\r\n";
        $headers .= "From: ConsultME <info@consultmee.in>\r\n";

        // -------------------- SEND MAIL LOOP --------------------
        while ($row = $consultants->fetch_assoc()) {

            $consultant_name  = htmlspecialchars($row['name']);
            $consultant_email = trim($row['email']);

            $message = '
            <html>
            <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            
            <title>New Project Alert</title>
            </head>
            
            <body style="
                margin:0;
                padding:0;
                background:#0b1120;
                font-family:Arial, Helvetica, sans-serif;
            ">
            
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="
                background:linear-gradient(135deg,#020617,#0f172a,#1e293b);
                padding:40px 15px;
            ">
            
            <tr>
            <td align="center">
            
            <table width="650" cellpadding="0" cellspacing="0" border="0" style="
                background:#111827;
                border-radius:20px;
                overflow:hidden;
                box-shadow:0 10px 40px rgba(0,0,0,0.45);
            ">
            
            <!-- HEADER -->
            <tr>
            <td style="
                background:linear-gradient(135deg,#0f172a,#1e3a8a,#2563eb);
                padding:40px 30px;
                text-align:center;
            ">
            
            <h1 style="
                margin:0;
                color:#ffffff;
                font-size:32px;
                font-weight:700;
                letter-spacing:1px;
            ">
            ConsultME
            </h1>
            
            <p style="
                margin-top:12px;
                color:#cbd5e1;
                font-size:16px;
            ">
            New Project Opportunity Available
            </p>
            
            </td>
            </tr>
            
            <!-- BODY -->
            <tr>
            <td style="padding:40px 35px;">
            
            <h2 style="
                color:#ffffff;
                margin-top:0;
                font-size:24px;
            ">
            Hello ' . $consultant_name . ',
            </h2>
            
            <p style="
                color:#cbd5e1;
                font-size:16px;
                line-height:28px;
            ">
            A new project has been posted on
            <span style="
                color:#60a5fa;
                font-weight:bold;
            ">
            ConsultME
            </span>.
            You can now review the details and apply directly from your dashboard.
            </p>
            
            <!-- PROJECT CARD -->
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="
                margin-top:30px;
                background:#0f172a;
                border:1px solid #1e293b;
                border-radius:16px;
            ">
            
            <tr>
            <td style="padding:30px;">
            
            <h2 style="
                margin-top:0;
                color:#ffffff;
                font-size:26px;
            ">
            ' . htmlspecialchars($title) . '
            </h2>
            
            <div style="
                height:1px;
                background:#334155;
                margin:20px 0;
            "></div>
            
            <p style="
                color:#93c5fd;
                font-size:15px;
                margin-bottom:8px;
                font-weight:bold;
            ">
            Short Description
            </p>
            
            <p style="
                color:#d1d5db;
                line-height:26px;
                font-size:15px;
            ">
            ' . nl2br(htmlspecialchars($short_description)) . '
            </p>
            
            <p style="
                color:#93c5fd;
                font-size:15px;
                margin-top:25px;
                margin-bottom:8px;
                font-weight:bold;
            ">
            Project Description
            </p>
            
            <p style="
                color:#d1d5db;
                line-height:28px;
                font-size:15px;
            ">
            ' . nl2br(htmlspecialchars($description)) . '
            </p>
            
            <!-- INFO BOX -->
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="
                margin-top:25px;
                background:#111827;
                border-radius:12px;
            ">
            
            <tr>
            
            <td style="
                padding:18px;
                border-right:1px solid #1e293b;
            ">
            <p style="
                margin:0;
                color:#94a3b8;
                font-size:13px;
            ">
            Budget
            </p>
            
            <p style="
                margin:8px 0 0;
                color:#ffffff;
                font-size:18px;
                font-weight:bold;
            ">
            ₹ ' . htmlspecialchars($budget) . '
            </p>
            </td>
            
            <td style="padding:18px;">
            <p style="
                margin:0;
                color:#94a3b8;
                font-size:13px;
            ">
            Billing Cycle
            </p>
            
            <p style="
                margin:8px 0 0;
                color:#ffffff;
                font-size:18px;
                font-weight:bold;
            ">
            ' . htmlspecialchars($billing_cycle) . '
            </p>
            </td>
            
            </tr>
            </table>
            
            <!-- BUTTON -->
            <div style="text-align:center;margin-top:35px;">
            
            <a href="https://consultmee.in/login-account"
            style="
                display:inline-block;
                padding:16px 34px;
                background:linear-gradient(135deg,#2563eb,#1d4ed8);
                color:#ffffff;
                text-decoration:none;
                border-radius:12px;
                font-weight:bold;
                font-size:16px;
                box-shadow:0 6px 20px rgba(37,99,235,0.35);
            ">
            Apply Now
            </a>
            
            </div>
            
            </td>
            </tr>
            </table>
            
            <!-- FOOTER -->
            <p style="
                color:#64748b;
                font-size:13px;
                text-align:center;
                margin-top:35px;
                line-height:24px;
            ">
            You are receiving this email because you are registered as a consultant on ConsultME.
            <br><br>
            © ' . date("Y") . ' ConsultME. All rights reserved.
            </p>
            
            </td>
            </tr>
            
            </table>
            
            </body>
            </html>
            ';


            $mail = mail(
                $consultant_email,
                $subject,
                $message,
                $headers
            );

            if ($mail) {
                $mail_sent++;
            } else {
                $mail_failed++;
            }
        }
    }

    // -------------------- FINAL RESPONSE --------------------
    echo json_encode([
        'success' => true,
        'message' => "Project created",
        'project_id' => $project_id,
        'mail_sent' => $mail_sent,
        'mail_failed' => $mail_failed
    ]);

} else {

    echo json_encode([
        'success' => false,
        'message' => "Insert failed",
        'error' => $stmt->error
    ]);
}

$stmt->close();
$conn->close();

exit;
?>