<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['consultant']['username'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$required = ['id', 'date', 'time', 'mode', 'status', 'meeting_link', 'consultant_message'];
foreach ($required as $field) {
    if (!isset($data[$field])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => "Missing field: $field"]);
        exit;
    }
}

$appointment_id = $data['id'];
$date = $data['date'];
$time = $data['time'];
$mode = $data['mode'];
$status = strtolower($data['status']);
$meeting_link = $data['meeting_link'];
$consultant_message = $data['consultant_message'];
$consultant_username = $_SESSION['consultant']['username'];

$conn = new mysqli('localhost', 'yibnyzre_cme_admin', 'As792002@', 'yibnyzre_cme');
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

// Update the appointment details
$sql = "UPDATE appointments 
        SET appointment_date = ?, appointment_time = ?, mode_of_appointment = ?, status = ?, meeting_link = ?, consultant_message = ?
        WHERE appointment_id = ? AND consultant_username = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Query preparation failed']);
    exit;
}

$stmt->bind_param('ssssssss', $date, $time, $mode, $status, $meeting_link, $consultant_message, $appointment_id, $consultant_username);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);

    // Fetch user info to send email
    $stmtUser = $conn->prepare("SELECT u.full_name, u.email 
                                FROM appointments a 
                                JOIN users u 
                                  ON CONVERT(a.user_username USING utf8mb4) COLLATE utf8mb4_general_ci 
                                     = CONVERT(u.username USING utf8mb4) COLLATE utf8mb4_general_ci
                                WHERE a.appointment_id = ?");
    $stmtUser->bind_param("s", $appointment_id);
    $stmtUser->execute();
    $resultUser = $stmtUser->get_result();
    $userData = $resultUser->fetch_assoc();
    $stmtUser->close();

    if ($userData) {
        $subject = "Appointment Updated - ConsultME";

        $headers = "From: ConsultME <no-reply@consultmee.in>\r\n";
        $headers .= "Reply-To: info@consultmee.in\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $userMessage = "
            <html>
            <head>
              <style>
                body {
                  font-family: 'Segoe UI', Arial, sans-serif;
                  background-color: #f4f7fb;
                  color: #333;
                  margin: 0;
                  padding: 0;
                }
                .email-container {
                  max-width: 600px;
                  margin: 40px auto;
                  background-color: #ffffff;
                  border-radius: 12px;
                  box-shadow: 0 4px 10px rgba(0,0,0,0.05);
                  overflow: hidden;
                }
                .email-header {
                  background: linear-gradient(135deg, #007bff, #00bfff);
                  color: white;
                  text-align: center;
                  padding: 25px 20px;
                }
                .email-header h2 {
                  margin: 0;
                  font-size: 24px;
                }
                .email-body {
                  padding: 30px 40px;
                }
                .email-body p {
                  margin: 10px 0;
                  font-size: 15px;
                  line-height: 1.6;
                }
                .details-box {
                  background-color: #f0f8ff;
                  border-left: 4px solid #007bff;
                  padding: 15px 20px;
                  margin: 20px 0;
                  border-radius: 8px;
                }
                .details-box p {
                  margin: 5px 0;
                  font-size: 14.5px;
                }
                a.button {
                  display: inline-block;
                  background-color: #007bff;
                  color: white !important;
                  text-decoration: none;
                  padding: 10px 18px;
                  border-radius: 8px;
                  font-weight: 500;
                  margin-top: 15px;
                }
                .footer {
                  background-color: #f8f9fa;
                  text-align: center;
                  padding: 15px;
                  font-size: 13px;
                  color: #666;
                }
              </style>
            </head>
            <body>
              <div class='email-container'>
                <div class='email-header'>
                  <h2>Appointment Updated</h2>
                  <p>ConsultME Notification</p>
                </div>
                <div class='email-body'>
                  <p>Hi <strong>{$userData['full_name']}</strong>,</p>
                  <p>Your appointment with <strong>{$consultant_username}</strong> has been <strong>updated</strong>.</p>
            
                  <div class='details-box'>
                    <p><strong>Appointment ID:</strong> $appointment_id</p>
                    <p><strong>Date:</strong> $date</p>
                    <p><strong>Time:</strong> $time</p>
                    <p><strong>Mode:</strong> $mode</p>
                    <p><strong>Status:</strong> <span style='color:#007bff;text-transform:capitalize;'>$status</span></p>
                    <p><strong>Meeting Link:</strong> <a href='$meeting_link' target='_blank'>$meeting_link</a></p>
                  </div>
            
                  <p><strong>Consultant Message:</strong></p>
                  <p style='background:#f9fafc; border-left:3px solid #00bfff; padding:10px 15px; border-radius:6px;'>$consultant_message</p>
            
                  <p>You can log in to your <strong>ConsultME account</strong> to view or manage your appointments.</p>
                  <p><a href='https://consultmee.in/login-account' class='button' target='_blank'>Go to Dashboard</a></p>
                </div>
                <div class='footer'>
                  <p>This is an automated message from <strong>ConsultME</strong>. Please do not reply directly.</p>
                  <p>&copy; ".date('Y')." ConsultME. All rights reserved.</p>
                </div>
              </div>
            </body>
            </html>";


        $maiUser = mail($userData['email'], $subject, $userMessage, $headers);
    }

} else {
    echo json_encode(['success' => false, 'error' => 'No changes made or invalid appointment ID']);
}

$stmt->close();
$conn->close();
?>