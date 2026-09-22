<?php
/**
 * resident_complaint.php
 */
error_reporting(E_ALL ^ E_WARNING);
define('BMIS_ROLE_REQUIRED', 'resident');
require(ROOT_PATH . '/secure_header.php'); 
header('Permissions-Policy: camera=(self)');
ini_set('display_errors', 0);
include(ROOT_PATH . '/classes/resident.class.php');
require_once(ROOT_PATH . '/classes/conn.php');

$userdetails = $bmis->get_userdata();
$pdo = $conn; 

$success_msg = '';
$error_msg   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_complaint'])) {
    $full_name      = trim($_POST['full_name']      ?? '');
    $contact_number = trim($_POST['contact_number'] ?? '');
    $address        = trim($_POST['address']        ?? '');
    $category       = trim($_POST['category']       ?? '');
    $custom_category= trim($_POST['custom_category']?? '');
    $description    = trim($_POST['description']    ?? '');
    $location       = trim($_POST['location']       ?? '');

    if ($category === 'Other' && $custom_category !== '') {
        $category = 'Other: ' . $custom_category;
    }

    if ($full_name === '' || $category === '' || $description === '' || $location === '') {
        $error_msg = 'Please fill in all required fields.';
    } elseif ($pdo === null) {
        $error_msg = 'Database connection failed.';
    } else {
        $photo_path = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = ROOT_PATH . '/uploads/complaints/';
            if (!is_dir($upload_dir)) { mkdir($upload_dir, 0755, true); }
            $validated = bmis_validate_image_upload($_FILES['photo']);
            if ($validated['ok']) {
                $filename = 'complaint_' . time() . '_' . $validated['safe_name'];
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $filename)) {
                    $photo_path = 'uploads/complaints/' . $filename;
                }
            }
        }

        $stmt = $pdo->prepare("INSERT INTO tbl_complaints (id_resident, full_name, contact_number, address, category, description, location, photo_path, status, date_submitted) VALUES (:id_resident, :full_name, :contact_number, :address, :category, :description, :location, :photo_path, 'pending', NOW())");
        $stmt->execute([
            ':id_resident'    => $userdetails['id_resident'] ?? null,
            ':full_name'      => $full_name,
            ':contact_number' => $contact_number,
            ':address'        => $address,
            ':category'       => $category,
            ':description'    => $description,
            ':location'       => $location,
            ':photo_path'     => $photo_path,
        ]);
        $success_msg = 'Your complaint has been submitted successfully!';
    }
}
?>
<?php require ROOT_PATH . '/app/Views/resident_complaint_view.php'; ?>
