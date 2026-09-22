<?php
/**
 * setup_security_question.php
 * Resident sets their security question after login.
 * Link to this from the resident dashboard / profile page.
 */
error_reporting(E_ALL ^ E_WARNING);
require_once ROOT_PATH . '/classes/security.php';
bmis_session_start();
$userdetails = bmis_require_resident();
date_default_timezone_set('Asia/Manila');
include(ROOT_PATH . '/autoloader.php');
require(ROOT_PATH . '/classes/conn.php');

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
?>
<?php require ROOT_PATH . '/app/Views/setup_security_question_view.php'; ?>
