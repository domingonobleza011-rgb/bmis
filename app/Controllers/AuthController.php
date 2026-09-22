<?php

class AuthController extends Controller
{
    public function login()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
error_reporting(E_ALL ^ E_WARNING);
ini_set('display_errors',1);
bmis_session_start();

date_default_timezone_set('Asia/Manila');
$_SESSION['storedate'] = date("Y-m-d");
$_SESSION['storetime'] = date("h:i:a");

if (isset($_POST['login'])) {
    $token = $_POST['cf-turnstile-response'] ?? '';
    $secret = '0x4AAAAAAEHzfRghdiBNzcb0lvRy5zy2Zok';

    $ch = curl_init('https://challenges.cloudflare.com/turnstile/v0/siteverify');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'secret'   => $secret,
        'response' => $token,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $verify = curl_exec($ch);
    $curlErr = curl_error($ch);
    curl_close($ch);

    $result = json_decode($verify, true);

    if (!$result || !$result['success']) {
        $_SESSION['login_error'] = 'CAPTCHA failed: ' . ($curlErr ?: implode(', ', $result['error-codes'] ?? ['no response']));
        header('Location: index.php');
        exit;
    }
}

$bmis->login();
$userdetails = $bmis->get_userdata();
$view = $staffbmis->view_staff();



// ── Role descriptions per position ────────────────────────────────────
$roleDescriptions = [
    'Punong Barangay' => [
        'icon'  => 'fa-star',
        'color' => '#1b74e4',
        'roles' => [
            'Heads the Sangguniang Barangay and presides over all sessions',
            'Signs and executes all barangay ordinances and resolutions',
            'Supervises barangay officials and employees',
            'Enforces all laws and ordinances applicable within the barangay',
            'Represents the barangay in all official transactions and matters',
            'Calls and presides meetings of the Sangguniang Barangay',
        ]
    ],
    'Secretary' => [
        'icon'  => 'fa-file-alt',
        'color' => '#1b74e4',
        'roles' => [
            'Records and prepares minutes of Sangguniang Barangay sessions',
            'Maintains barangay records, archives and official documents',
            'Certifies barangay records and official documents',
            'Issues certified copies of barangay documents upon request',
            'Assists in preparing barangay ordinances and resolutions',
        ]
    ],
    'Treasurer' => [
        'icon'  => 'fa-coins',
        'color' => '#1b74e4',
        'roles' => [
            'Collects and receives all taxes, fees, and other charges due to the barangay',
            'Disburses funds as authorized by the Punong Barangay',
            'Maintains and safeguards all barangay funds and assets',
            'Prepares financial statements and budget reports',
            'Issues official receipts for all collections and payments',
        ]
    ],
    'Clerk' => [
        'icon'  => 'fa-clipboard',
        'color' => '#1b74e4',
        'roles' => [
            'Assists the Secretary in recording and filing documents',
            'Processes and releases barangay certifications and clearances',
            'Manages day-to-day administrative correspondence',
            'Maintains a logbook of daily transactions and requests',
        ]
    ],
    'Book Keeper' => [
        'icon'  => 'fa-book',
        'color' => '#1b74e4',
        'roles' => [
            'Records all financial transactions in the barangay ledger',
            'Prepares financial summaries and accounting reports',
            'Reconciles accounts and verifies financial entries',
            'Assists the Treasurer in budgeting and audit preparation',
        ]
    ],
    'Committee on Appropriation' => [
        'icon'  => 'fa-hand-holding-usd',
        'color' => '#1b74e4',
        'roles' => [
            'Reviews and recommends the Annual Budget of the Barangay',
            'Evaluates proposed appropriations and expenditures',
            'Ensures funds are allocated properly and transparently',
            'Monitors utilization of appropriated barangay funds',
        ]
    ],
    'Committee on Health' => [
        'icon'  => 'fa-heartbeat',
        'color' => '#1b74e4',
        'roles' => [
            'Oversees barangay health programs and sanitation drives',
            'Coordinates with health centers for community health services',
            'Promotes health awareness and disease prevention campaigns',
            'Monitors the health and welfare of barangay constituents',
        ]
    ],
    'Committee on Women and Children' => [
        'icon'  => 'fa-female',
        'color' => '#1b74e4',
        'roles' => [
            'Formulates programs for the welfare of women and children',
            'Addresses cases of abuse and violence against women and children',
            'Coordinates with DSWD and other agencies for assistance',
            'Promotes gender sensitivity and children\'s rights in the barangay',
        ]
    ],
    'Committee on Education' => [
        'icon'  => 'fa-graduation-cap',
        'color' => '#1b74e4',
        'roles' => [
            'Supports educational programs and scholarships in the barangay',
            'Liaises with schools and DepEd for educational initiatives',
            'Promotes literacy and continuing education for all residents',
            'Assists learners in need of financial and academic support',
        ]
    ],
    'Committee on Peace and Order' => [
        'icon'  => 'fa-shield-alt',
        'color' => '#1b74e4',
        'roles' => [
            'Maintains peace, order, and public safety in the barangay',
            'Coordinates with PNP and BFP for security concerns',
            'Mediates and resolves community disputes and conflicts',
            'Oversees the Barangay Tanod and community watch programs',
        ]
    ],
    'Committee on Infrastructure' => [
        'icon'  => 'fa-hard-hat',
        'color' => '#1b74e4',
        'roles' => [
            'Plans and oversees barangay infrastructure projects',
            'Monitors construction and maintenance of roads and pathways',
            'Coordinates with DPWH and LGU for infrastructure funding',
            'Ensures public facilities are maintained and serviceable',
        ]
    ],
    'Committee on Ways and Means' => [
        'icon'  => 'fa-chart-line',
        'color' => '#1b74e4',
        'roles' => [
            'Identifies and develops sources of barangay revenue',
            'Proposes income-generating projects for the community',
            'Reviews and recommends measures to improve financial capacity',
            'Assists in maximizing IRA and other resource allocations',
        ]
    ],
    'Committee on Agriculture' => [
        'icon'  => 'fa-seedling',
        'color' => '#1b74e4',
        'roles' => [
            'Supports farmers and fisherfolk in the barangay',
            'Coordinates with DA for agricultural programs and assistance',
            'Promotes sustainable farming and food security initiatives',
            'Assists in distributing farm inputs and livelihood support',
        ]
    ],
    'Committee on Tourism' => [
        'icon'  => 'fa-map-marked-alt',
        'color' => '#1b74e4',
        'roles' => [
            'Promotes local tourism attractions and heritage sites',
            'Coordinates with DOT and LGU for tourism development',
            'Organizes cultural events and festivals in the barangay',
            'Supports local tourism-based livelihood programs',
        ]
    ],
    'IPMRR Representative' => [
        'icon'  => 'fa-hands',
        'color' => '#1b74e4',
        'roles' => [
            'Represents the interests of indigenous peoples in the barangay',
            'Coordinates with NCIP for indigenous peoples\' rights and welfare',
            'Promotes cultural heritage and traditions of indigenous communities',
            'Assists IP members in accessing government programs and services',
        ]
    ],
    'Sk Chairperson' => [
        'icon'  => 'fa-bolt',
        'color' => '#1b74e4',
        'roles' => [
            'Heads the Sangguniang Kabataan and presides over SK sessions',
            'Implements youth development programs and activities',
            'Manages the SK budget and funds for youth programs',
            'Represents the youth sector in Sangguniang Barangay sessions',
            'Coordinates with national youth agencies and organizations',
        ]
    ],
];
        $this->view('auth/login.php');

    }

    public function logout()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;

    $bmis->logout();

    // Make sure logout.php itself is never cached/bfcache'd either.
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");
    header("Expires: 0");

    header("Location: login.php");
    exit();

    }

    public function forgotPassword()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
error_reporting(E_ALL ^ E_WARNING);
ini_set('display_errors', 1);
bmis_session_start();
date_default_timezone_set('Asia/Manila');

$step    = $_GET['step'] ?? 'identify';
$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['find_account'])) {
    bmis_verify_csrf();
    $identity = trim($_POST['identity']);
    $stmt = $conn->prepare(
        "SELECT id_resident, fname, lname, security_question, security_answer
         FROM tbl_resident
         WHERE (email = ? OR phone_number = ?) LIMIT 1"
    );
    $stmt->execute([$identity, $identity]);
    $resident = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$resident) {
        $error = 'No account found with that email or phone number.';
    } elseif (empty($resident['security_question'])) {
        $_SESSION['reset_id']   = $resident['id_resident'];
        $_SESSION['reset_name'] = $resident['fname'] . ' ' . $resident['lname'];
        header('Location: forgot_password.php?step=admin_request');
        exit;
    } else {
        $_SESSION['reset_id']       = $resident['id_resident'];
        $_SESSION['reset_name']     = $resident['fname'] . ' ' . $resident['lname'];
        $_SESSION['reset_question'] = $resident['security_question'];
        $_SESSION['reset_answer']   = $resident['security_answer'];
        header('Location: forgot_password.php?step=security_question');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_answer'])) {
    bmis_verify_csrf();
    if (empty($_SESSION['reset_id'])) { header('Location: forgot_password.php'); exit; }
    $answer = strtolower(trim($_POST['security_answer']));
    $stored = strtolower(trim($_SESSION['reset_answer'] ?? ''));
    if ($answer !== $stored) {
        $error = 'Incorrect answer. Please try again.';
        $step  = 'security_question';
    } else {
        $_SESSION['reset_verified'] = true;
        header('Location: forgot_password.php?step=new_password');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['set_password'])) {
    bmis_verify_csrf();
    if (empty($_SESSION['reset_verified']) || empty($_SESSION['reset_id'])) {
        header('Location: forgot_password.php'); exit;
    }
    $new  = $_POST['new_password'];
    $conf = $_POST['confirm_password'];
    if (strlen($new) < 8) {
        $error = 'Password must be at least 8 characters.'; $step = 'new_password';
    } elseif ($new !== $conf) {
        $error = 'Passwords do not match.'; $step = 'new_password';
    } else {
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $conn->prepare("UPDATE tbl_resident SET password = ? WHERE id_resident = ?")
             ->execute([$hashed, $_SESSION['reset_id']]);
        unset($_SESSION['reset_id'], $_SESSION['reset_name'], $_SESSION['reset_question'],
              $_SESSION['reset_answer'], $_SESSION['reset_verified']);
        $success = 'Password changed successfully! You may now log in.';
        $step    = 'done';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_admin_reset'])) {
    bmis_verify_csrf();
    if (empty($_SESSION['reset_id'])) { header('Location: forgot_password.php'); exit; }
    $id = $_SESSION['reset_id'];
    $check = $conn->prepare("SELECT id FROM tbl_password_reset_requests WHERE id_resident = ? AND status = 'pending' LIMIT 1");
    $check->execute([$id]);
    if ($check->fetch()) {
        $success = 'You already have a pending reset request. Please wait for admin approval.';
    } else {
        $conn->prepare(
            "INSERT INTO tbl_password_reset_requests (id_resident, full_name, phone_number)
             SELECT ?, CONCAT(fname,' ',lname), phone_number FROM tbl_resident WHERE id_resident = ?"
        )->execute([$id, $id]);
        unset($_SESSION['reset_id'], $_SESSION['reset_name']);
        $success = 'Reset request submitted! Please visit the barangay hall or wait for admin to contact you.';
    }
    $step = 'done';
}

if (empty($step) || $step === 'identify') {
    $step = $_GET['step'] ?? 'identify';
}
        $this->view('auth/forgotPassword.php');

    }

    public function setupSecurityQuestion()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
/**
 * setup_security_question.php
 * Resident sets their security question after login.
 * Link to this from the resident dashboard / profile page.
 */
error_reporting(E_ALL ^ E_WARNING);
bmis_session_start();
$userdetails = bmis_require_resident();
date_default_timezone_set('Asia/Manila');

$error   = '';
$success = '';

$questions = [
    "What is the name of your elementary school?",
    "What is your mother's maiden name?",
    "What is the name of your childhood best friend?",
    "What was the name of your first pet?",
    "What street did you grow up on?",
    "What is your oldest sibling's middle name?",
    "What city were you born in?",
    "What was your childhood nickname?",
];

// Fetch existing
$stmt = $conn->prepare("SELECT security_question, security_answer FROM tbl_resident WHERE id_resident = ?");
$stmt->execute([$userdetails['id_resident']]);
$current = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_security_q'])) {
    bmis_verify_csrf();
    $question = $_POST['security_question'];
    $answer   = strtolower(trim($_POST['security_answer']));
    $confirm  = strtolower(trim($_POST['confirm_answer']));

    if (!in_array($question, $questions)) {
        $error = 'Invalid question selected.';
    } elseif (strlen($answer) < 2) {
        $error = 'Answer is too short.';
    } elseif ($answer !== $confirm) {
        $error = 'Answers do not match.';
    } else {
        $stmt = $conn->prepare("UPDATE tbl_resident SET security_question = ?, security_answer = ? WHERE id_resident = ?");
        $stmt->execute([$question, $answer, $userdetails['id_resident']]);
        $success = 'Security question saved successfully.';
        $current['security_question'] = $question;
        $current['security_answer']   = $answer;
    }
}
        $this->view('auth/setupSecurityQuestion.php');

    }

    public function addAdmin()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    $bmis->create_admin(); 
    $userdetails = $bmis->get_userdata();
?>

<?php 
    
        $this->view('auth/addAdmin.php');

    }

    public function adminChangepass()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
    
$bmis->validate_staff_or_admin();
    $bmis->admin_changepass();
    $userdetails = $bmis->get_userdata();
    
        $this->view('auth/adminChangepass.php');

    }

}
