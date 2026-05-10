<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Only POST method is allowed.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['appointment_id'], $data['new_status'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required parameters.']);
    exit;
}

$appointmentId = trim($data['appointment_id']);
$newStatus = strtolower(trim($data['new_status']));

$validStatuses = ['pending', 'confirmed', 'cancelled', 'completed'];
if (!in_array($newStatus, $validStatuses)) {
    echo json_encode(['success' => false, 'message' => 'Invalid status value.']);
    exit;
}

$host = 'localhost';
$db   = 'yibnyzre_cme';
$user = 'yibnyzre_cme_admin';
$pass = 'As792002@';
$charset = 'utf8mb4';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'DB connection failed: ' . $conn->connect_error]));
}

try {
    error_log("Updating appointment: appointment_id = $appointmentId, new_status = $newStatus");

    // Fetch usernames from appointments table
    $stmtFetch = $conn->prepare("SELECT user_username, consultant_username FROM appointments WHERE appointment_id = ?");
    $stmtFetch->bind_param("s", $appointmentId);
    $stmtFetch->execute();
    $result = $stmtFetch->get_result();
    $appointmentData = $result->fetch_assoc();
    $stmtFetch->close();

    if (!$appointmentData) {
        echo json_encode(['success' => false, 'message' => 'No matching appointment found.']);
        exit;
    }

    // Fetch user details
    $userStmt = $conn->prepare("SELECT full_name, email FROM users WHERE username = ?");
    $userStmt->bind_param("s", $appointmentData['user_username']);
    $userStmt->execute();
    $userResult = $userStmt->get_result();
    $userData = $userResult->fetch_assoc();
    $userStmt->close();

    // Fetch consultant details
    $consultantStmt = $conn->prepare("SELECT name AS full_name, email FROM consultants WHERE username = ?");
    $consultantStmt->bind_param("s", $appointmentData['consultant_username']);
    $consultantStmt->execute();
    $consultantResult = $consultantStmt->get_result();
    $consultantData = $consultantResult->fetch_assoc();
    $consultantStmt->close();

    if (!$userData || !$consultantData) {
        echo json_encode(['success' => false, 'message' => 'User or consultant not found.']);
        exit;
    }

    // Update appointment status
    $stmtUpdate = $conn->prepare("UPDATE appointments SET status = ? WHERE appointment_id = ?");
    $stmtUpdate->bind_param("ss", $newStatus, $appointmentId);

    if ($stmtUpdate->execute() && $stmtUpdate->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Appointment status updated.']);

        // Send emails if cancelled
        if ($newStatus === 'cancelled') {
            $subject = "Appointment Cancelled - ConsultME";

            // Email headers
            $headers = "From: ConsultME <no-reply@consultmee.in>\r\n";
            $headers .= "Reply-To: info@consultmee.in\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

            // HTML email for user
            $userMessage = "
                <html>
                  <body style='font-family: Arial, sans-serif; background-color: #f4f9ff; color: #333; line-height: 1.7; margin: 0; padding: 0;'>
                    <div style='max-width: 650px; margin: 40px auto; background: #ffffff; border-radius: 16px; box-shadow: 0 6px 18px rgba(0,0,0,0.08); overflow: hidden;'>
                      
                      <!-- Header -->
                      <div style='background: linear-gradient(135deg, #0077b6, #00b4d8); padding: 24px; text-align: center; color: #fff;'>
                        <h1 style='margin: 0; font-size: 22px;'>🚫 Appointment Cancelled</h1>
                      </div>
                
                      <!-- Body -->
                      <div style='padding: 30px;'>
                        <p style='font-size: 16px;'>Hi <strong>{$userData['full_name']}</strong>,</p>
                        
                        <p style='font-size: 15px;'>
                          We regret to inform you that your appointment with the consultant 
                          <strong>{$consultantData['full_name']}</strong> has been cancelled.
                        </p>
                
                        <div style='background-color: #f1f9ff; border: 1px solid #cce5ff; border-radius: 10px; padding: 15px 20px; margin-top: 20px;'>
                          <table style='width: 100%; border-collapse: collapse; font-size: 15px;'>
                            <tr>
                              <td style='padding: 8px 0;'><strong>🆔 Appointment ID:</strong></td>
                              <td>$appointmentId</td>
                            </tr>
                          </table>
                        </div>
                
                        <p style='margin-top: 20px; font-size: 15px;'>
                          If you wish to reschedule, please log in to your 
                          <a href='https://consultmee.in/login-account' style='color: #0077b6; text-decoration: none; font-weight: bold;'>ConsultME Account</a> 
                          and book a new appointment at your convenience.
                        </p>
                
                        <p style='font-size: 15px;'>We sincerely apologize for any inconvenience caused. Thank you for understanding! 🙏</p>
                
                        <hr style='border: none; border-top: 1px solid #e0e0e0; margin: 25px 0;'>
                
                        <p style='font-size: 14px; color: #555;'>
                          Need help? Our support team is here for you at  
                          <a href='mailto:info@consultmee.in' style='color: #0077b6; text-decoration: none;'>info@consultmee.in</a>.
                        </p>
                
                        <p style='font-size: 13px; color: #777;'>
                          Warm regards,<br>
                          <strong>ConsultME Team</strong><br>
                          <a href='https://consultmee.in' style='color: #0077b6; text-decoration: none;'>www.consultmee.in</a>
                        </p>
                      </div>
                
                      <!-- Footer -->
                      <div style='background: #f1f9ff; text-align: center; padding: 12px; font-size: 12px; color: #666;'>
                        This is an automated message from <strong>ConsultME</strong>. Please do not reply directly. 📧
                      </div>
                
                    </div>
                  </body>
                </html>";


            // HTML email for consultant
            $consultantMessage = "
                <html>
                  <body style='font-family: Arial, sans-serif; background-color: #f4f9ff; color: #333; line-height: 1.7; margin: 0; padding: 0;'>
                    <div style='max-width: 650px; margin: 40px auto; background: #ffffff; border-radius: 16px; box-shadow: 0 6px 18px rgba(0,0,0,0.08); overflow: hidden;'>
                      
                      <!-- Header -->
                      <div style='background: linear-gradient(135deg, #0077b6, #00b4d8); padding: 24px; text-align: center; color: #fff;'>
                        <h1 style='margin: 0; font-size: 22px;'>🚫 Appointment Cancelled</h1>
                      </div>
                
                      <!-- Body -->
                      <div style='padding: 30px;'>
                        <p style='font-size: 16px;'>Hi <strong>{$consultantData['full_name']}</strong>,</p>
                        
                        <p style='font-size: 15px;'>
                          The appointment with your client <strong>{$userData['full_name']}</strong> has been cancelled.
                        </p>
                
                        <div style='background-color: #f1f9ff; border: 1px solid #cce5ff; border-radius: 10px; padding: 15px 20px; margin-top: 20px;'>
                          <table style='width: 100%; border-collapse: collapse; font-size: 15px;'>
                            <tr>
                              <td style='padding: 8px 0;'><strong>🆔 Appointment ID:</strong></td>
                              <td>$appointmentId</td>
                            </tr>
                          </table>
                        </div>
                
                        <p style='margin-top: 20px; font-size: 15px;'>
                          You can log in to your 
                          <a href='https://consultmee.in/clogin.html' style='color: #0077b6; text-decoration: none; font-weight: bold;'>ConsultME Consultant Dashboard</a> 
                          to manage your appointments and view available slots. 📅
                        </p>
                
                        <p style='font-size: 15px;'>
                          We appreciate your professionalism and understanding. 🤝
                        </p>
                
                        <hr style='border: none; border-top: 1px solid #e0e0e0; margin: 25px 0;'>
                
                        <p style='font-size: 14px; color: #555;'>
                          Need assistance? Our support team is here for you at 
                          <a href='mailto:info@consultmee.in' style='color: #0077b6; text-decoration: none;'>info@consultmee.in</a>.
                        </p>
                
                        <p style='font-size: 13px; color: #777;'>
                          Warm regards,<br>
                          <strong>ConsultME Team</strong><br>
                          <a href='https://consultmee.in' style='color: #0077b6; text-decoration: none;'>www.consultmee.in</a>
                        </p>
                      </div>
                
                      <!-- Footer -->
                      <div style='background: #f1f9ff; text-align: center; padding: 12px; font-size: 12px; color: #666;'>
                        This is an automated message from <strong>ConsultME</strong>. Please do not reply directly. 📧
                      </div>
                
                    </div>
                  </body>
                </html>";


            // Send emails
            @mail($userData['email'], $subject, $userMessage, $headers);
            @mail($consultantData['email'], $subject, $consultantMessage, $headers);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No matching appointment found or database error.']);
    }

    $stmtUpdate->close();
    $conn->close();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
?>