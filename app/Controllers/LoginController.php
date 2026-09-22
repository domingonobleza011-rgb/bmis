<?php
error_reporting(E_ALL ^ E_WARNING);
ini_set('display_errors',1);
require_once ROOT_PATH . '/classes/security.php';
bmis_session_start();

date_default_timezone_set('Asia/Manila');
$_SESSION['storedate'] = date("Y-m-d");
$_SESSION['storetime'] = date("h:i:a");

include(ROOT_PATH . '/autoloader.php');
require(ROOT_PATH . '/classes/main.class.php');
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
require(ROOT_PATH . '/classes/staff.class.php');
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
?>
<?php require ROOT_PATH . '/app/Views/login_view.php'; ?>
