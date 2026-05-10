<?php
header("Content-Type: application/json");

// ✅ Database connection
$host = 'localhost';
$db   = 'yibnyzre_cme';
$user = 'yibnyzre_cme_admin';
$pass = 'As792002@';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => "Database connection failed."]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

// ✅ Required fields
$required = [
    'consultant_name',
    'users_name',
    'consultant_username',
    'user_username',
    'appointment_date',
    'appointment_time',
    'mode_of_appointment'
];

foreach ($required as $field) {
    if (empty($data[$field])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => "Missing field: $field"]);
        exit;
    }
}

// ✅ Assign variables
$appointment_id = bin2hex(random_bytes(6)); // better unique ID
$consultant_name = trim($data['consultant_name']);
$users_name = trim($data['users_name']);
$consultant_username = trim($data['consultant_username']);
$user_username = trim($data['user_username']);
$appointment_date = $data['appointment_date'];
$appointment_time = $data['appointment_time'];
$mode_of_appointment = $data['mode_of_appointment'];
$user_request = $data['user_request'] ?? null;

// ✅ Validate formats
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $appointment_date)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid date format. Use YYYY-MM-DD.']);
    exit;
}
if (!preg_match('/^\d{2}:\d{2}$/', $appointment_time)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid time format. Use HH:MM.']);
    exit;
}

try {
    // ✅ Prevent duplicate same-time booking by same user-consultant pair
    $checkStmt = $pdo->prepare("
        SELECT appointment_id 
        FROM appointments 
        WHERE consultant_username = :consultant_username 
          AND user_username = :user_username 
          AND appointment_date = :appointment_date 
          AND appointment_time = :appointment_time
          AND status IN ('pending', 'confirmed')
        LIMIT 1
    ");
    $checkStmt->execute([
        ':consultant_username' => $consultant_username,
        ':user_username' => $user_username,
        ':appointment_date' => $appointment_date,
        ':appointment_time' => $appointment_time
    ]);

    if ($checkStmt->fetch()) {
        echo json_encode([
            'success' => false,
            'message' => 'You have already requested this appointment slot.'
        ]);
        exit;
    }

    // ✅ Fetch consultant details
    $consultantStmt = $pdo->prepare("SELECT email, name FROM consultants WHERE TRIM(username)=:username LIMIT 1");
    $consultantStmt->execute([':username' => $consultant_username]);
    $consultant = $consultantStmt->fetch();
    if (!$consultant) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Consultant not found.']);
        exit;
    }

    $consultant_email = trim($consultant['email']);
    $consultant_name_db = $consultant['name'];

    // ✅ Fetch user details
    $userStmt = $pdo->prepare("SELECT email, full_name FROM users WHERE TRIM(username)=:username LIMIT 1");
    $userStmt->execute([':username' => $user_username]);
    $user = $userStmt->fetch();
    if (!$user) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'User not found.']);
        exit;
    }

    $user_email = trim($user['email']);
    $user_name_db = $user['full_name'];

    // ✅ Insert appointment
    $insertStmt = $pdo->prepare("
        INSERT INTO appointments (
            appointment_id,
            consultant_name,
            users_name,
            consultant_username,
            user_username,
            status,
            appointment_date,
            appointment_time,
            mode_of_appointment,
            user_request
        ) VALUES (
            :appointment_id,
            :consultant_name,
            :users_name,
            :consultant_username,
            :user_username,
            'pending',
            :appointment_date,
            :appointment_time,
            :mode_of_appointment,
            :user_request
        )
    ");
    $insertStmt->execute([
        ':appointment_id' => $appointment_id,
        ':consultant_name' => $consultant_name,
        ':users_name' => $users_name,
        ':consultant_username' => $consultant_username,
        ':user_username' => $user_username,
        ':appointment_date' => $appointment_date,
        ':appointment_time' => $appointment_time,
        ':mode_of_appointment' => $mode_of_appointment,
        ':user_request' => $user_request
    ]);

    // ✅ Send emails (single attempt)
    $headers  = "From: ConsultME <no-reply@consultmee.in>\r\n";
    $headers .= "Reply-To: info@consultmee.in\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    $subjectConsultant = "📅 New Appointment Booking Received - ConsultME";

    $messageConsultant = "
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
            .details-box table {
              width: 100%;
              border-collapse: collapse;
            }
            .details-box td {
              padding: 5px 0;
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
              <h2>📅 New Appointment Request</h2>
              <p>ConsultME Notification</p>
            </div>
            <div class='email-body'>
              <p>Dear <strong>$consultant_name_db</strong>,</p>
              <p>You have received a new appointment request from <strong>$user_name_db</strong> on <strong>ConsultME</strong>.</p>
        
              <div class='details-box'>
                <table>
                  <tr><td><strong>🆔 Appointment ID:</strong></td><td>$appointment_id</td></tr>
                  <tr><td><strong>📅 Date:</strong></td><td>$appointment_date</td></tr>
                  <tr><td><strong>⏰ Time:</strong></td><td>$appointment_time</td></tr>
                  <tr><td><strong>💬 Mode:</strong></td><td>$mode_of_appointment</td></tr>
                </table>
              </div>
        
              <p><strong>📋 User Request:</strong></p>
              <p style='background:#f9fafc; border-left:3px solid #00bfff; padding:10px 15px; border-radius:6px;'>
                " . (!empty($user_request) ? nl2br(htmlspecialchars($user_request)) : "No specific request provided.") . "
              </p>
        
              <p>Please log in to your <strong>ConsultME Dashboard</strong> to confirm or reschedule the appointment.</p>
              <p><a href='https://consultmee.in/login-account' class='button' target='_blank'>Go to Dashboard</a></p>
            </div>
        
            <div class='footer'>
              <p>If you face any issues, contact us at 
                <a href='mailto:info@consultmee.in' style='color:#007bff;'>info@consultmee.in</a>.
              </p>
              <p style='font-size: 0.9em; color:#777;'>
                Best regards,<br><strong>ConsultME Team</strong><br>
                <a href='https://consultmee.in' style='color:#007bff;'>www.consultmee.in</a>
              </p>
              <hr style='border:none; border-top:1px solid #eee; margin:10px 0;'>
              <p style='font-size:12px;color:#888;'>This is an automated message. Please do not reply directly.</p>
            </div>
          </div>
        </body>
        </html>";

    
    $mailConsultant = mail($consultant_email, $subjectConsultant, $messageConsultant, $headers);


    $subjectUser = "✅ Appointment Request Submitted Successfully - ConsultME";
    $messageUser = "
        <html>
          <body style='font-family: Arial, sans-serif; background-color: #f4f9ff; color: #333; line-height: 1.7; margin: 0; padding: 0;'>
            <div style='max-width: 650px; margin: 40px auto; background: #ffffff; border-radius: 16px; box-shadow: 0 6px 18px rgba(0,0,0,0.08); overflow: hidden;'>
              
              
              <div style='padding: 30px;'>
                <p style='font-size: 16px;'>Hi <strong>$user_name_db</strong>,</p>
                
                <p style='font-size: 15px;'>
                  Thank you for booking your appointment through <strong>ConsultME</strong>! 🎉  
                  Your request has been successfully sent to <strong>$consultant_name_db</strong>.
                </p>
        
                <div style='background-color: #f1f9ff; border: 1px solid #cce5ff; border-radius: 10px; padding: 15px 20px; margin-top: 20px;'>
                  <table style='width: 100%; border-collapse: collapse; font-size: 15px;'>
                    <tr>
                      <td style='padding: 8px 0;'><strong>🆔 Appointment ID:</strong></td>
                      <td>$appointment_id</td>
                    </tr>
                    <tr>
                      <td style='padding: 8px 0;'><strong>📅 Date:</strong></td>
                      <td>$appointment_date</td>
                    </tr>
                    <tr>
                      <td style='padding: 8px 0;'><strong>⏰ Time:</strong></td>
                      <td>$appointment_time</td>
                    </tr>
                    <tr>
                      <td style='padding: 8px 0;'><strong>💬 Mode:</strong></td>
                      <td>$mode_of_appointment</td>
                    </tr>
                  </table>
                </div>
        
                <p style='margin-top: 20px;'>
                  Once the consultant reviews your request, you’ll receive another email confirming your appointment details.
                </p>
        
                <p style='font-size: 15px;'>
                  You can track your appointment status anytime from your  
                  <a href='https://consultmee.in/login-account' style='color: #0077b6; text-decoration: none; font-weight: bold;'>ConsultME Account</a>.
                </p>
        
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
        
              <div style='background: #f1f9ff; text-align: center; padding: 12px; font-size: 12px; color: #666;'>
                This is an automated message from <strong>ConsultME</strong>. Please do not reply directly.
              </div>
            </div>
          </body>
        </html>";

    
    $mailUser = mail($user_email, $subjectUser, $messageUser, $headers);

    echo json_encode([
        'success' => true,
        'appointment_id' => $appointment_id,
        'message' => 'Appointment booked successfully. Notifications sent.',
        'consultant_mail' => $mailConsultant,
        'user_mail' => $mailUser
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error. Please try again later.']);
}
?>