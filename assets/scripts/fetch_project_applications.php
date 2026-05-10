<?php
// assets/scripts/fetch_project_applications.php
header('Content-Type: application/json; charset=utf-8');

session_start();

/* ---------------- CONFIG / DEBUG ---------------- */
$DEBUG = 0; // set to 1 temporarily if you want to see php_error and trace in JSON

if ($DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(E_ALL);
}

function json_out($arr) {
    echo json_encode($arr, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

try {
    // If you want mysqli to throw exceptions on error (helps debugging)
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    /* ---------------- AUTH: Require logged-in user ---------------- */
    if (empty($_SESSION['user']) || empty($_SESSION['user']['username'])) {
        json_out([
            'success' => false,
            'message' => 'not_authenticated'
        ]);
    }

    $currentUser = $_SESSION['user']['username'];

    /* ---------------- INPUT: project_id ---------------- */
    $projectId = isset($_GET['project_id']) ? trim($_GET['project_id']) : '';

    if ($projectId === '') {
        json_out([
            'success' => false,
            'message' => 'missing_project_id'
        ]);
    }

    /* ---------------- DB CONNECTION ---------------- */
    $dbHost = 'localhost';
    $dbName = 'yibnyzre_cme';
    $dbUser = 'yibnyzre_cme_admin';
    $dbPass = 'As792002@';

    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    $conn->set_charset('utf8mb4');

    /* 
       We are NOT checking "project belongs to current user" here,
       because your last *working* version also didn’t.
       We just fetch all applications for this project_id.
    */

    /* --------------------------------------------------
       Fetch applications + FULL consultant details

       Tables:

       project_applications:
         - project_id     VARCHAR(20)
         - username       VARCHAR(20)  -- consultant username
         - user_msg       VARCHAR(150)
         - portfolio_link VARCHAR(100)

       consultants:
         - name
         - username
         - area_of_expertise
         - bio
         - experience
         - phone
         - email
         - state
         - hourly_rate
         - profile_img
         - identity
         - verification
         - created_at
    --------------------------------------------------- */

    $sql = "
        SELECT
            a.username          AS username,
            a.user_msg          AS user_msg,
            a.portfolio_link    AS portfolio_link,

            c.name              AS full_name,
            c.profile_img       AS profile_img,
            c.state             AS state,
            c.identity          AS identity,
            c.area_of_expertise AS area_of_expertise,
            c.bio               AS bio,
            c.experience        AS experience,
            c.phone             AS phone,
            c.email             AS email,
            c.hourly_rate       AS hourly_rate,
            c.verification      AS verification,
            c.created_at        AS created_at
        FROM project_applications a
        LEFT JOIN consultants c
            ON c.username = a.username
        WHERE a.project_id = ?
        ORDER BY c.name ASC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $projectId);
    $stmt->execute();

    $stmt->bind_result(
        $username,
        $user_msg,
        $portfolio_link,
        $full_name,
        $profile_img,
        $state,
        $identity,
        $area_of_expertise,
        $bio,
        $experience,
        $phone,
        $email,
        $hourly_rate,
        $verification,
        $created_at
    );

    $applications = [];
    while ($stmt->fetch()) {
        $applications[] = [
            'username'          => $username,
            'user_msg'          => $user_msg,
            'portfolio_link'    => $portfolio_link,
            'applied_at'        => null,          // no column yet, JS treats as optional

            'full_name'         => $full_name,
            'profile_img'       => $profile_img,
            'state'             => $state,
            'identity'          => $identity,
            'area_of_expertise' => $area_of_expertise,

            // Extra fields for the "View Profile" popup
            'bio'               => $bio,
            'experience'        => $experience,
            'phone'             => $phone,
            'email'             => $email,
            'hourly_rate'       => $hourly_rate,
            'verification'      => $verification,
            'created_at'        => $created_at,
        ];
    }

    $stmt->close();
    $conn->close();

    json_out([
        'success'      => true,
        'project_id'   => $projectId,
        'applications' => $applications
    ]);

} catch (Exception $e) {
    if ($DEBUG) {
        json_out([
            'success'   => false,
            'message'   => 'php_exception',
            'php_error' => $e->getMessage(),
            'trace'     => $e->getTraceAsString()
        ]);
    } else {
        json_out([
            'success' => false,
            'message' => 'server_error'
        ]);
    }
}