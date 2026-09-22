<?php
    define('BMIS_ROLE_REQUIRED', 'admin');
    require(ROOT_PATH . '/secure_header.php');
    require(ROOT_PATH . '/classes/main.class.php');

    // ==========================================
    // BACKEND: Handle Bulk Deletion Action
    // ==========================================
    $delete_message = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bulk_delete'])) {
        $ids_to_delete = isset($_POST['selected_ids']) ? array_map('intval', $_POST['selected_ids']) : [];
        
        if (!empty($ids_to_delete)) {
            if (method_exists($bmis, 'delete_login_history')) {
                $success = $bmis->delete_login_history($ids_to_delete);
            } else {
                $success = false; 
            }

            if ($success) {
                $delete_message = '<div class="alert alert-success" style="background:#e3f3e9; color:#1e6e3e; padding:12px 20px; border-radius:8px; margin-bottom:20px; font-size:14px; font-weight:500;"><i class="fas fa-check-circle mr-2"></i> Successfully deleted ' . count($ids_to_delete) . ' record(s).</div>';
            }
        }
    }

    // ==========================================
    // DATA FETCHING & FILTERS
    // ==========================================
    $page     = max(1, (int)($_GET['page']     ?? 1));
    $per_page = 10;
    $filters  = [
        'search'    => trim($_GET['search']    ?? ''),
        'event'     => trim($_GET['event']     ?? ''),
        'date_from' => trim($_GET['date_from'] ?? ''),
        'date_to'   => trim($_GET['date_to']   ?? ''),
    ];

    // Re-run fetching after a successful post statement to update the table immediately
    $result      = $bmis->get_login_history($filters, $page, $per_page);
    $logs        = $result['rows'];
    $total       = $result['total'];
    $total_pages = max(1, (int)ceil($total / $per_page));

    include(ROOT_PATH . '/dashboard_sidebar_start.php');
?>
<?php require ROOT_PATH . '/app/Views/admn_login_history_view.php'; ?>
