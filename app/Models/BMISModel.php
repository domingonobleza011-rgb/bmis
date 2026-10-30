<?php
    // Composer's vendor/ is no longer required for FCM (see getFCMAccessToken()
    // below, which now signs its own JWT with openssl instead of google/auth).
    // Loaded conditionally so a missing vendor/ folder on InfinityFree can't
    // fatal-crash every page on the site.
    if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
        require_once __DIR__ . '/../../vendor/autoload.php';
    }
    require_once __DIR__ . '/../Core/Security.php';
    require_once __DIR__ . '/../Core/Mailer.php';
function notif($message, $type = 'success') {
    $msg = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

    $palette = [
        'success' => [
            'border' => '#1D9E75', 'iconbg' => '#E1F5EE', 'title' => '#085041', 'text' => '#0F6E56',
            'label'  => 'Success!',
            'icon'   => "<circle cx='12' cy='12' r='10' fill='#1D9E75'/><path d='M7.5 12.5l3 3 6-6' stroke='#fff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>",
        ],
        'error' => [
            'border' => '#E24B4A', 'iconbg' => '#FCEBEB', 'title' => '#501313', 'text' => '#A32D2D',
            'label'  => 'Error',
            'icon'   => "<circle cx='12' cy='12' r='10' fill='#E24B4A'/><path d='M15 9l-6 6M9 9l6 6' stroke='#fff' stroke-width='2' stroke-linecap='round'/>",
        ],
        'warning' => [
            'border' => '#D9A404', 'iconbg' => '#FDF3D8', 'title' => '#6B4E00', 'text' => '#8A6400',
            'label'  => 'Warning',
            'icon'   => "<circle cx='12' cy='12' r='10' fill='#D9A404'/><path d='M12 7v6' stroke='#fff' stroke-width='2' stroke-linecap='round'/><circle cx='12' cy='16.2' r='1.1' fill='#fff'/>",
        ],
        'info' => [
            'border' => '#3661D5', 'iconbg' => '#E8F0FE', 'title' => '#1B3A8A', 'text' => '#2C54C1',
            'label'  => 'Notice',
            'icon'   => "<circle cx='12' cy='12' r='10' fill='#3661D5'/><path d='M12 11v5.5' stroke='#fff' stroke-width='2' stroke-linecap='round'/><circle cx='12' cy='7.7' r='1.1' fill='#fff'/>",
        ],
    ];
    $c = $palette[$type] ?? $palette['success'];

    echo <<<HTML
    <div id="bmisToast" style="
        position:fixed; top:24px; right:24px; z-index:99999;
        background:#fff; border-left:4px solid {$c['border']};
        border-radius:10px; box-shadow:0 8px 32px rgba(0,0,0,0.13);
        padding:16px 20px 16px 18px; min-width:300px; max-width:380px;
        display:flex; align-items:flex-start; gap:14px;
        font-family:Georgia,serif;
        animation:bmisSlideIn .4s cubic-bezier(.22,1,.36,1) both;
    ">
        <div style="width:36px;height:36px;border-radius:50%;background:{$c['iconbg']};display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24">{$c['icon']}</svg>
        </div>
        <div>
            <div style="font-weight:700;color:{$c['title']};font-size:15px;margin-bottom:3px;">{$c['label']}</div>
            <div style="color:{$c['text']};font-size:13px;">{$msg}</div>
        </div>
        <button onclick="document.getElementById('bmisToast').remove()" style="margin-left:auto;background:none;border:none;cursor:pointer;color:{$c['border']};font-size:20px;line-height:1;padding:0;flex-shrink:0;">&times;</button>
    </div>
    <style>
        @keyframes bmisSlideIn { from{opacity:0;transform:translateX(60px)} to{opacity:1;transform:translateX(0)} }
        @keyframes bmisSlideOut { from{opacity:1;transform:translateX(0)} to{opacity:0;transform:translateX(60px)} }
        @media (max-width: 480px) {
            #bmisToast { left:16px; right:16px; top:16px; min-width:unset; max-width:unset; }
        }
    </style>
    <script>
        setTimeout(function () {
            var t = document.getElementById('bmisToast');
            if (t) {
                t.style.animation = 'bmisSlideOut .35s ease forwards';
                setTimeout(function () {
                    if (t && t.parentNode) { t.parentNode.removeChild(t); }
                }, 350);
            }
        }, 3000);
    </script>
    HTML;
}

class BMISClass {

//------------------------------------------ DATABASE CONNECTION ----------------------------------------------------
    

     protected $server = "mysql:host=sql106.infinityfree.com;dbname=if0_41514709_bmis";
    protected $user = "if0_41514709";
    protected $pass = "sanpedro011";
    protected $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
    protected $con;


   
   

   public function show_404()
    {
        http_response_code(403);
        echo "Access denied.";
        die;
    }

    /**
     * Redirect unauthenticated users to the login page.
     * Same behavior as bmis_require_login() in security.php.
     */
    public function require_login_or_deny()
    {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        if (empty($_SESSION['userdata'])) {
            $scheme   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $script   = $_SERVER['SCRIPT_NAME'] ?? '';
            $base_dir = rtrim(dirname($script), '/\\');
            $login    = $scheme . '://' . $host . $base_dir . '/index.php';
            header('Location: ' . $login);
            exit();
        }
    }
 
public function openConn() {
    if ($this->con === null) {
        try {
            $this->con = new PDO($this->server, $this->user, $this->pass, $this->options);
        } catch (PDOException $e) {
            // Log the real reason instead of only echoing a <script> tag (which
            // does nothing when this runs mid-redirect/mid-POST-handler).
            // Rethrow so callers' own catch (PDOException) blocks actually run,
            // instead of leaving $this->con as null and causing an uncaught
            // "call to a member function on null" fatal error further down
            // (which renders as a blank page when display_errors is off).
            error_log("Database Connection Error: " . $e->getMessage());
            throw $e;
        }
    }
    return $this->con;
}
 
    // ──────────────────────────────────────────────────────────────────────────
    // ARCHIVE HELPER — call before any DELETE to log to tbl_archive
    // ──────────────────────────────────────────────────────────────────────────
    protected function archive_record($table, $id_col, $id_val, $record_type) {
        try {
            $connection = $this->openConn();
 
            // 1. Fetch the row we are about to delete
            $stmt = $connection->prepare("SELECT * FROM `$table` WHERE `$id_col` = ?");
            $stmt->execute([$id_val]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) return; // nothing to archive
 
            // 2. Build display fields
            $lname = $row['lname'] ?? '';
            $fname = $row['fname'] ?? '';
            $mi    = (!empty($row['mi'])) ? ' ' . $row['mi'] . '.' : '';
            $full_name = trim("$lname, $fname$mi");
 
            // Build summary based on type
            switch ($record_type) {
                case 'resident':
                    $summary = implode(' ', array_filter([
                        $row['houseno'] ?? '', $row['street'] ?? '',
                        ($row['brgy'] ?? ''), ($row['municipal'] ?? ''),
                        '|', ($row['sex'] ?? ''), '| Age:', ($row['age'] ?? '')
                    ]));
                    break;
                case 'certofres':
                case 'certofindigency':
                    $summary = 'Purpose: ' . ($row['purpose'] ?? '') . ' | Date: ' . ($row['date'] ?? '');
                    break;
                case 'clearance':
                    $summary = 'Purpose: ' . ($row['purpose'] ?? '') . ' | Status: ' . ($row['status'] ?? '');
                    break;
                case 'bspermit':
                    $summary = 'Business: ' . ($row['bsname'] ?? '') . ' | Industry: ' . ($row['bsindustry'] ?? '');
                    break;
                case 'blotter':
                    $narrative = $row['narrative'] ?? '';
                    $summary = 'Narrative: ' . (strlen($narrative) > 80 ? substr($narrative, 0, 80) . '...' : $narrative);
                    break;
                case 'youth':
                    $summary = 'Age: ' . ($row['age'] ?? '') . ' | Educ: ' . ($row['educ_attain'] ?? '') . ' | Skill: ' . ($row['skill_name'] ?? '');
                    break;
                case 'brgyid':
                    $summary = ($row['houseno'] ?? '') . ' ' . ($row['street'] ?? '') . ', ' . ($row['brgy'] ?? '') . ' | Bdate: ' . ($row['bdate'] ?? '');
                    break;
                case 'staff':
                    $summary = 'Role: ' . ($row['role'] ?? '') . ' | Email: ' . ($row['email'] ?? '');
                    break;
                default:
                    $summary = '';
            }
 
            // 3. Get deleted_by from session if available
            $deleted_by = null;
            if (session_status() === PHP_SESSION_ACTIVE && !empty($_SESSION['userdata'])) {
                $ud  = $_SESSION['userdata'];
                $fn  = trim($ud['firstname'] ?? '');
                $ln  = trim($ud['surname'] ?? '');
                if ($fn !== '' || $ln !== '') {
                    $deleted_by = trim($fn . ' ' . $ln);
                }
            }
 
            // 4. Insert into archive
            $ins = $connection->prepare(
                "INSERT INTO tbl_archive (record_type, record_id, full_name, summary, record_data, deleted_by)
                 VALUES (?, ?, ?, ?, ?, ?)"
            );
            $ins->execute([
                $record_type,
                $id_val,
                $full_name,
                $summary,
                json_encode($row, JSON_UNESCAPED_UNICODE),
                $deleted_by
            ]);
 
        } catch (Exception $e) {
            // Never let archiving break a delete — fail silently
            error_log('archive_record error: ' . $e->getMessage());
        }
    }
 
 
    // Close DB connection
    public function closeConn() {
        $this->con = null;
    }
 
 
    // ─────────────────────────────────────────────────────────────────────────
    // AUTHENTICATION & SESSION HANDLING
    // ─────────────────────────────────────────────────────────────────────────
    public function log_activity(string $action, string $module, string $description): void {
        try {
            if (!isset($_SESSION)) { session_start(); }
 
            $userdata   = $_SESSION['userdata'] ?? [];
            $id_admin   = $userdata['id_admin']  ?? null;
            $fname      = $userdata['firstname'] ?? '';
            $lname      = $userdata['surname']   ?? '';
            $admin_name = trim("$fname $lname") ?: 'System';
            $role       = $userdata['role']      ?? '';
            $ip         = $_SERVER['REMOTE_ADDR']     ?? null;
            $ua         = $_SERVER['HTTP_USER_AGENT'] ?? null;
 
            $con  = $this->openConn();
            $stmt = $con->prepare("
                INSERT INTO tbl_activity_log
                    (id_admin, admin_name, role, action, module, description, ip_address, user_agent)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$id_admin, $admin_name, $role, $action, $module, $description, $ip, $ua]);
        } catch (\Throwable $e) {
            // Logging must never crash the main flow — fail silently
            error_log("log_activity error: " . $e->getMessage());
        }
    }
    public function get_activity_logs(array $filters = [], int $page = 1, int $per_page = 20): array {
        try {
            $con    = $this->openConn();
            $where  = ['1=1'];
            $params = [];
 
            if (!empty($filters['search'])) {
                $where[]  = '(admin_name LIKE ? OR description LIKE ? OR action LIKE ?)';
                $s = '%' . $filters['search'] . '%';
                $params = array_merge($params, [$s, $s, $s]);
            }
            if (!empty($filters['module'])) {
                $where[]  = 'module = ?';
                $params[] = $filters['module'];
            }
            if (!empty($filters['date_from'])) {
                $where[]  = 'DATE(created_at) >= ?';
                $params[] = $filters['date_from'];
            }
            if (!empty($filters['date_to'])) {
                $where[]  = 'DATE(created_at) <= ?';
                $params[] = $filters['date_to'];
            }
 
            $sql_where = implode(' AND ', $where);
 
            // Total count
            $count_stmt = $con->prepare("SELECT COUNT(*) FROM tbl_activity_log WHERE $sql_where");
            $count_stmt->execute($params);
            $total = (int) $count_stmt->fetchColumn();
 
            // Paginated rows
            $offset = ($page - 1) * $per_page;
            $data_stmt = $con->prepare("
                SELECT * FROM tbl_activity_log
                WHERE $sql_where
                ORDER BY created_at DESC
                LIMIT $per_page OFFSET $offset
            ");
            $data_stmt->execute($params);
            $rows = $data_stmt->fetchAll();
 
            return ['rows' => $rows, 'total' => $total];
        } catch (\Throwable $e) {
            error_log("get_activity_logs error: " . $e->getMessage());
            return ['rows' => [], 'total' => 0];
        }
    }
    /**
     * Delete log rows older than $days days (optional maintenance).
     */
    public function clear_old_logs(int $days = 1): void {
        try {
            $con = $this->openConn();
            $con->prepare("DELETE FROM tbl_activity_log  WHERE created_at < NOW() - INTERVAL ? DAY")->execute([$days]);
        } catch (\Throwable $e) {
            error_log("clear_old_logs error: " . $e->getMessage());
        }
    }
    public function login() {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    require_once __DIR__ . '/../Core/Security.php';
    if(isset($_POST['login'])) {
        $identity       = $_POST['login_identity'];
        $password_input = $_POST['password'];

        $connection = $this->openConn();

        if (bmis_is_locked_out($connection, $identity)) {
            $wait_min = (int) ceil(bmis_lockout_seconds_remaining($connection, $identity) / 60);
            $_SESSION['login_error'] = "Too many failed login attempts. Please try again in $wait_min minute(s).";
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }

        // 1. Check ADMIN table
        $stmt = $connection->prepare("SELECT * FROM tbl_admin WHERE email = ? OR phone_number = ?");
        $stmt->execute([$identity, $identity]);
        $user = $stmt->fetch();

        if($user && password_verify($password_input, $user['password'])) {
            if(in_array(strtolower($user['role']), ['admin', 'administrator'])) {
                bmis_reset_login_attempts($connection, $identity);
                $this->set_userdata($user);
                header('Location: admn_dashboard.php');
                exit();
            }
        }

        // 2. Check USER (Staff) table
        $stmt = $connection->prepare("SELECT * FROM tbl_user WHERE email = ? OR phone_number = ?");
        $stmt->execute([$identity, $identity]);
        $user = $stmt->fetch();

        if($user && password_verify($password_input, $user['password'])) {
            if($user['role'] == 'user') {
                bmis_reset_login_attempts($connection, $identity);
                $this->set_userdata($user);

                $position = $user['position'];

                $admin_dashboard_positions = [
                    'Punong Barangay',
                    'Secretary',
                    'Treasurer',
                    'Clerk',
                    'Book Keeper',
                    'Committee on Appropriation',
                    'Committee on Health',
                    'Committee on Women and Children',
                    'Committee on Education',
                    'Committee on Peace and Order',
                    'Committee on Infrastructure',
                    'Committee on Ways and Means',
                    'Committee on Agriculture',
                    'Committee on Tourism',
                    'IPMRR Representative',
                ];

                if ($position === 'Sk Chairperson') {
    echo "<script>window.location.href='sk_dashboard.php';</script>";
} else {
    echo "<script>window.location.href='admn_dashboard.php';</script>";
}
                exit();
            }
        }

        // 3. Check RESIDENT table
        $stmt = $connection->prepare("SELECT * FROM tbl_resident WHERE email = ? OR phone_number = ?");
        $stmt->execute([$identity, $identity]);
        $user = $stmt->fetch();

        if($user && password_verify($password_input, $user['password'])) {
            if($user['role'] == 'resident') {
                bmis_reset_login_attempts($connection, $identity);
                $this->set_userdata($user);
                // residents are not admins — skip admin log
                header('Location: resident_homepage.php');
                exit();
            }
        }

        // Invalid credentials
        bmis_record_failed_login($connection, $identity);
        $_SESSION['login_error'] = 'Invalid credentials. Please check your Email/Phone and Password.';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}
 
// ============================================================
//  PATCHED logout() — replaces the existing logout() method
// ============================================================
 
    //eto yung function na mag e end ng session tas i l logout ka 
    public function logout(){
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Wipe all session data, not just userdata, so nothing lingers.
        $_SESSION = array();

        // Kill the session cookie itself so the browser can't replay the old session id.
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();
    }
 
    public function get_userdata(){
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        return $_SESSION['userdata'] ?? null;
    }
 
    public function set_userdata($array) {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }

        $_SESSION['userdata'] = array(
            "id_admin"     => $array['id_admin']    ?? null,
            "id_resident"  => $array['id_resident'] ?? null,
            "id_user"      => $array['id_user']     ?? null,
            "emailadd"     => $array['email']       ?? '',
            "password"     => $array['password']    ?? '',
            "surname"      => $array['lname']       ?? '',
            "firstname"    => $array['fname']       ?? '',
            "mname"        => $array['mi']          ?? '',
            "age"          => $array['age']         ?? '',
            "sex"          => $array['sex']         ?? '',
            "status"       => $array['status']      ?? '',
            "address"      => $array['address']     ?? '',
            "contact"      => $array['contact']     ?? '',
            "bdate"        => $array['bdate']       ?? '',
            "bplace"       => $array['bplace']      ?? '',
            "nationality"  => $array['nationality'] ?? '',
            "family_role"  => $array['family_role'] ?? '',
            "role"         => $array['role']        ?? '',
            "position"     => $array['position']    ?? '',
            "houseno"      => $array['houseno']     ?? '',
            "street"       => $array['street']      ?? '',
            "brgy"         => $array['brgy']        ?? '',
            "relation"     => $array['relation']    ?? '',
            "municipal"    => $array['municipal']   ?? ''
        );
        return $_SESSION['userdata'];
    }
 
 
    // ─────────────────────────────────────────────────────────────────────────
    // ADMIN CRUD
    // ─────────────────────────────────────────────────────────────────────────
    public function create_admin() {
        if(isset($_POST['add_admin'])) {
            $email    = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $lname    = $_POST['lname'];
            $fname    = $_POST['fname'];
            $mi       = $_POST['mi'];
            $role     = $_POST['role'];
 
            if ($this->check_admin($email) == 0) {
                $connection = $this->openConn();
                $stmt = $connection->prepare("INSERT INTO tbl_admin (`email`,`password`,`lname`,`fname`,`mi`,`role`) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$email, $password, $lname, $fname, $mi, $role]);
                notif('Administrator account added successfully.', 'success');
            } else {
                notif('An account with that email already exists.', 'warning');
            }
        }
    }
 
    public function admin_changepass() {
        if(isset($_POST['admin_changepass'])) {
            $id_admin    = $_POST['id_admin'] ?? null;
            $oldpassword = $_POST['oldpassword'] ?? '';
            $newpassword = $_POST['newpassword'] ?? '';
            $checkpassword = $_POST['checkpassword'] ?? '';
 
            if (empty($id_admin)) {
                notif('Error: Admin ID is missing. Please re-login.', 'error');
                return;
            }
 
            $connection = $this->openConn();
            $stmt = $connection->prepare("SELECT `password` FROM tbl_admin WHERE id_admin = ?");
            $stmt->execute([$id_admin]);
            $result = $stmt->fetch();
 
            if (!$result) {
                notif('Admin user not found.', 'error');
                return;
            }
 
            if (!password_verify($oldpassword, $result['password'])) {
                notif('Old Password is Incorrect.', 'error');
            } elseif ($newpassword !== $checkpassword) {
                notif('New Passwords do not match.', 'warning');
            } elseif (empty($newpassword)) {
                notif('New password cannot be empty.', 'warning');
            } else {
                $hashed_new = password_hash($newpassword, PASSWORD_DEFAULT);
                $stmt = $connection->prepare("UPDATE tbl_admin SET password = ? WHERE id_admin = ?");
                $stmt->execute([$hashed_new, $id_admin]);
                echo "<script>showNotif('Password updated successfully!', 'success'); setTimeout(() => window.location.href='admn_dashboard.php', 2000);</script>";
            }
        }
    }
 
 
    // ─────────────────────────────────────────────────────────────────────────
    // ANNOUNCEMENT CRUD
    // ─────────────────────────────────────────────────────────────────────────

    public function create_announcement() {
        if(isset($_POST['create_announce'])) {
            $event      = $_POST['event'];
            $start_date = $_POST['start_date'];
            $addedby    = $_POST['addedby'];
            $uploaded_images = [];
 
            if(isset($_FILES['announcement_img']) && !empty($_FILES['announcement_img']['name'][0])) {
                $upload_dir = "uploads/";
                if (!is_dir($upload_dir)) { mkdir($upload_dir, 0755, true); }
 
                foreach($_FILES['announcement_img']['name'] as $key => $name) {
                    if($_FILES['announcement_img']['error'][$key] == 0) {
                        $single_file = [
                            'name'     => $name,
                            'type'     => $_FILES['announcement_img']['type'][$key],
                            'tmp_name' => $_FILES['announcement_img']['tmp_name'][$key],
                            'error'    => $_FILES['announcement_img']['error'][$key],
                            'size'     => $_FILES['announcement_img']['size'][$key],
                        ];
                        $validated = bmis_validate_image_upload($single_file);
                        if ($validated['ok']) {
                            $new_name    = time() . '_' . $validated['safe_name'];
                            $target_file = $upload_dir . $new_name;
                            if(move_uploaded_file($single_file['tmp_name'], $target_file)) {
                                $uploaded_images[] = $new_name;
                            }
                        }
                    }
                }
            }
 
            $image_string = !empty($uploaded_images) ? implode(',', $uploaded_images) : null;
            $connection   = $this->openConn();
            $stmt = $connection->prepare("INSERT INTO tbl_announcement (`event`,`start_date`,`addedby`,`image`,`status`) VALUES (?, ?, ?, ?, 'active')");
            $stmt->execute([$event, $start_date, $addedby, $image_string]);

            // ── Broadcast push notification to all residents ──────────────
            $this->broadcastFCMNotification(
                '📢 New Barangay Announcement',
                $event
            );
            // ─────────────────────────────────────────────────────────────

            $count = count($uploaded_images);
            notif("Announcement added with {$count} image(s).", 'success');
            header('refresh:0');
        }
    }
 
    public function get_single_announcement($id_announcement) {
        $connection = $this->openConn();
        $stmt = $connection->prepare("SELECT * FROM tbl_announcement WHERE id_announcement = ?");
        $stmt->execute([$id_announcement]);
        return $stmt->fetch();
    }
 
    public function view_announcement(){
        $connection = $this->openConn();
        $stmt = $connection->prepare("SELECT * from tbl_announcement ORDER BY id_announcement DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
 
public function admin_delete_announcement(){
    if(isset($_POST['delete_announcement'])) {
        $id_announcement = $_POST['id_announcement'];
        $this->archive_record('tbl_announcement', 'id_announcement', $id_announcement, 'announcement');
        $connection = $this->openConn();

        // Fetch image paths before deleting
        $stmt = $connection->prepare("SELECT image FROM tbl_announcement WHERE id_announcement = ?");
        $stmt->execute([$id_announcement]);
        $row = $stmt->fetch();

        $this->log_activity('DELETE_Announcement', 'Announcement', "Deleted Announcement Record #$id_announcement");
        // Delete associated image files
        if($row && !empty($row['image'])) {
            foreach(explode(',', $row['image']) as $img) {
                $file_path = "uploads/" . trim($img);
                if(file_exists($file_path)) {
                    unlink($file_path);
                }
            }
        }

        // Delete the record
        $stmt = $connection->prepare("DELETE FROM tbl_announcement WHERE id_announcement = ?");
        $stmt->execute([$id_announcement]);

        // Use session flash message instead of inline script
        session_start();
        $_SESSION['notif_message'] = 'Announcement and all images deleted.';
        $_SESSION['notif_type']    = 'success';

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}
 
    public function delete_announcement($user_id){
        if(isset($_POST['delete_announcement'])) {
            $id_announcement = $_POST['id_announcement'];
            $this->hide_announcement($user_id, $id_announcement);
            $current_page = basename($_SERVER['PHP_SELF']);
            echo "<script>showNotif('Announcement removed.', 'info'); setTimeout(() => window.location.href='$current_page', 2000);</script>";
            exit();
        }
    }
 
    public function view_active_announcements($user_id){
        $connection = $this->openConn();
        $sql = "SELECT * FROM tbl_announcement
                WHERE id_announcement NOT IN (
                    SELECT announcement_id FROM tbl_hidden_announcements WHERE user_id = ?
                ) AND status = 'active'
                ORDER BY start_date DESC";
        $stmt = $connection->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }
 
    public function count_announcement() {
        $connection = $this->openConn();
        $stmt = $connection->prepare("SELECT COUNT(*) from tbl_announcement");
        $stmt->execute();
        return $stmt->fetchColumn();
    }
 
    public function hide_announcement($user_id, $announcement_id) {
        $connection = $this->openConn();
        $stmt = $connection->prepare("INSERT INTO tbl_hidden_announcements (user_id, announcement_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $announcement_id]);
    }
 
    public function sendMessage($id_resident, $message_text) {
        try {
            $sql  = "INSERT INTO resident_messages (id_resident, message_text, date_sent) VALUES (:id, :msg, NOW())";
            $stmt = $this->openConn()->prepare($sql);
            $stmt->bindParam(':id',  $id_resident);
            $stmt->bindParam(':msg', $message_text);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Message Error: " . $e->getMessage());
            return false;
        } finally {
            $this->closeConn();
        }
    }
 
    public function getResidentMessages($id_resident) {
        try {
            $sql  = "SELECT * FROM resident_messages WHERE id_resident = ? ORDER BY date_sent DESC";
            $stmt = $this->openConn()->prepare($sql);
            $stmt->execute([$id_resident]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        } finally {
            $this->closeConn();
        }
    }
    
    public function getUnreadResidentMessageCount($id_resident) {
        try {
            $sql  = "SELECT COUNT(*) FROM resident_messages WHERE id_resident = ? AND is_read = 0";
            $stmt = $this->openConn()->prepare($sql);
            $stmt->execute([$id_resident]);
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        } finally {
            $this->closeConn();
        }
    }
 
    // Clears the unread badge — call this when the resident actually opens resident_messages.php.
    public function markResidentMessagesRead($id_resident) {
        try {
            $sql  = "UPDATE resident_messages SET is_read = 1 WHERE id_resident = ? AND is_read = 0";
            $stmt = $this->openConn()->prepare($sql);
            return $stmt->execute([$id_resident]);
        } catch (PDOException $e) {
            return false;
        } finally {
            $this->closeConn();
        }
    }
 
    public function deleteResidentMessage($id_msg) {
        try {
            $connection = $this->openConn();
            $sql  = "DELETE FROM resident_messages WHERE id_message = ?";
            $stmt = $connection->prepare($sql);
            return $stmt->execute([$id_msg]);
        } catch (PDOException $e) {
            notif("Database Error: " . $e->getMessage(), 'error');
            die();
        }
    }
 
    public function sendMessageToAdmin($id_resident, $message_text) {
        try {
            $connection = $this->openConn();
            $sql  = "INSERT INTO admin_messages (id_resident, message_text, date_sent, status) VALUES (?, ?, NOW(), 'unread')";
            $stmt = $connection->prepare($sql);
            return $stmt->execute([$id_resident, $message_text]);
        } catch (PDOException $e) {
            notif("Database Error: " . $e->getMessage(), 'error');
            die();
        }
    }
 
    public function viewMessages() {
        try {
            $connection = $this->openConn();
            $sql  = "SELECT m.*, r.fname, r.lname FROM admin_messages m JOIN tbl_resident r ON m.id_resident = r.id_resident ORDER BY m.date_sent DESC";
            $stmt = $connection->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            notif("Database Error: " . $e->getMessage(), 'error');
            die();
        }
    }

    // ---- Conversation-level views (one row per resident, not per message) ----

    /**
     * One row per resident who has ever messaged the admin (or been sent a
     * system/admin notice), with the most recent message text/date across
     * BOTH admin_messages (resident -> admin) and resident_messages (admin -> resident),
     * plus how many of their messages are still unread.
     */
    public function getConversations() {
        try {
            $connection = $this->openConn();
            $sql = "
                SELECT
                    r.id_resident,
                    r.fname,
                    r.lname,
                    (SELECT COUNT(*) FROM admin_messages a
                        WHERE a.id_resident = r.id_resident) AS total_count,
                    (SELECT COUNT(*) FROM admin_messages a
                        WHERE a.id_resident = r.id_resident AND a.status = 'unread') AS unread_count,
                    latest.message_text AS last_message,
                    latest.date_sent    AS last_date
                FROM tbl_resident r
                INNER JOIN (
                    SELECT id_resident, message_text, date_sent FROM (
                        SELECT id_resident, message_text, date_sent,
                               ROW_NUMBER() OVER (PARTITION BY id_resident ORDER BY date_sent DESC) AS rn
                        FROM (
                            SELECT id_resident, message_text, date_sent FROM admin_messages
                            UNION ALL
                            SELECT id_resident, message_text, date_sent FROM resident_messages
                        ) combined
                    ) ranked WHERE rn = 1
                ) latest ON latest.id_resident = r.id_resident
                ORDER BY latest.date_sent DESC
            ";
            $stmt = $connection->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            notif("Database Error: " . $e->getMessage(), 'error');
            die();
        }
    }

    /**
     * Full back-and-forth thread for one resident, oldest first, merged from:
     *  - admin_messages       (side = resident, what they sent in)
     *  - admin_messages.reply_text (side = admin, OLD single-reply-slot replies,
     *                                kept so existing conversation history isn't lost)
     *  - resident_messages    (side = admin, replies/notices sent going forward)
     */
    public function getConversationThread($id_resident) {
        try {
            $connection = $this->openConn();
            $sql = "
                SELECT message_text, date_sent, 'resident' AS side
                FROM admin_messages WHERE id_resident = ?

                UNION ALL

                SELECT reply_text AS message_text, reply_date AS date_sent, 'admin' AS side
                FROM admin_messages
                WHERE id_resident = ? AND reply_text IS NOT NULL AND reply_text <> ''

                UNION ALL

                SELECT message_text, date_sent, 'admin' AS side
                FROM resident_messages WHERE id_resident = ?

                ORDER BY date_sent ASC
            ";
            $stmt = $connection->prepare($sql);
            $stmt->execute([$id_resident, $id_resident, $id_resident]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            notif("Database Error: " . $e->getMessage(), 'error');
            die();
        }
    }

    /** Deletes an entire conversation (all messages + notices) for one resident. */
    public function deleteConversation($id_resident) {
        try {
            $connection = $this->openConn();
            $connection->beginTransaction();
            $stmt1 = $connection->prepare("DELETE FROM admin_messages WHERE id_resident = ?");
            $stmt1->execute([$id_resident]);
            $stmt2 = $connection->prepare("DELETE FROM resident_messages WHERE id_resident = ?");
            $stmt2->execute([$id_resident]);
            $connection->commit();
            return true;
        } catch (PDOException $e) {
            if ($connection->inTransaction()) $connection->rollBack();
            notif("Database Error: " . $e->getMessage(), 'error');
            die();
        }
    }

    /**
     * Soft-delete: snapshots a resident's whole conversation (both tables) into
     * tbl_archive — the same archive table/columns used elsewhere in the app —
     * then removes it from the live inbox. Restoring or permanently deleting it
     * afterward is handled by admn_archive.php, exactly like every other record type.
     */
    public function archiveConversation($id_resident, $deleted_by) {
        try {
            $connection = $this->openConn();

            $r = $connection->prepare("SELECT fname, lname FROM tbl_resident WHERE id_resident = ?");
            $r->execute([$id_resident]);
            $resident = $r->fetch(PDO::FETCH_ASSOC);
            if (!$resident) return false;
            $full_name = trim($resident['fname'] . ' ' . $resident['lname']);

            $a = $connection->prepare("SELECT * FROM admin_messages WHERE id_resident = ?");
            $a->execute([$id_resident]);
            $admin_msgs = $a->fetchAll(PDO::FETCH_ASSOC);

            $rm = $connection->prepare("SELECT * FROM resident_messages WHERE id_resident = ?");
            $rm->execute([$id_resident]);
            $resident_msgs = $rm->fetchAll(PDO::FETCH_ASSOC);

            if (empty($admin_msgs) && empty($resident_msgs)) {
                return false; // nothing to archive
            }

            // Preview text for the archive list = most recent message overall
            $latest = null;
            foreach (array_merge($admin_msgs, $resident_msgs) as $m) {
                if ($latest === null || strtotime($m['date_sent']) > strtotime($latest['date_sent'])) {
                    $latest = $m;
                }
            }
            $summary = $latest ? mb_substr($latest['message_text'], 0, 80) : '';

            $record_data = json_encode([
                'admin_messages'    => $admin_msgs,
                'resident_messages' => $resident_msgs,
            ], JSON_UNESCAPED_UNICODE);

            $ins = $connection->prepare(
                "INSERT INTO tbl_archive (record_type, record_id, full_name, summary, record_data, deleted_by)
                 VALUES ('message', ?, ?, ?, ?, ?)"
            );
            $ins->execute([$id_resident, $full_name, $summary, $record_data, $deleted_by]);

            // Now that it's safely archived, remove it from the live inbox
            return $this->deleteConversation($id_resident);
        } catch (PDOException $e) {
            notif("Database Error: " . $e->getMessage(), 'error');
            die();
        }
    }

    public function deleteMessage($id_admin_msg) {
        try {
            $connection = $this->openConn();
            $sql  = "DELETE FROM admin_messages WHERE id_admin_msg = ?";
            $stmt = $connection->prepare($sql);
            return $stmt->execute([$id_admin_msg]);
        } catch (PDOException $e) {
            notif("Database Error: " . $e->getMessage(), 'error');
            die();
        }
    }

    /**
     * Reply to a resident's whole conversation (not a single message row):
     * marks their pending messages as handled, then records one new reply
     * that shows up in the merged thread for everyone.
     */
    public function replyToMessage($id_resident, $reply_text, $admin_name) {
        try {
            $connection = $this->openConn();

            $stmt1 = $connection->prepare(
                "UPDATE admin_messages SET status = 'replied' WHERE id_resident = ? AND status = 'unread'"
            );
            $stmt1->execute([$id_resident]);

            $stmt2 = $connection->prepare(
                "INSERT INTO resident_messages (id_resident, message_text, date_sent) VALUES (?, ?, NOW())"
            );
            return $stmt2->execute([$id_resident, $reply_text]);
        } catch (PDOException $e) {
            notif("Database Error: " . $e->getMessage(), 'error');
            die();
        }
    }
 
public function uploadValidID($id_resident, $file_name, $original_name, $file_type, $message_note = '') {
        try {
            $connection = $this->openConn();
            $sql  = "INSERT INTO tbl_id_uploads (id_resident, file_name, original_name, file_type, message_note, upload_date, status) VALUES (?, ?, ?, ?, ?, NOW(), 'pending')";
            $stmt = $connection->prepare($sql);
            return $stmt->execute([$id_resident, $file_name, $original_name, $file_type, $message_note]);
        } catch (PDOException $e) {
            error_log("uploadValidID Error: " . $e->getMessage());
            return false;
        }
    }
 
    /**
     * Verifies an ID upload, updates its status, and deletes the physical file from storage.
     */
public function verifyIDUpload($id_upload) {
        try {
            $con = $this->openConn();
            
            // 1. Fetch the file name
            $stmt = $con->prepare("SELECT file_name FROM tbl_id_uploads WHERE id_upload = ?");
            $stmt->execute([$id_upload]);
            $file = $stmt->fetch();
 
            if ($file && !empty($file['file_name'])) {
                // Construct path using the root directory fallback
                $filename = $file['file_name'];
                
                // Try target paths (relative and absolute)
                $relative_path = "uploads/valid_ids/" . $filename;
                $absolute_path = $_SERVER['DOCUMENT_ROOT'] . "/uploads/valid_ids/" . $filename; 
 
                // Attempt deletion via relative path
                if (file_exists($relative_path)) {
                    unlink($relative_path);
                    error_log("SUCCESS: Deleted file via relative path: " . $relative_path);
                } 
                // Attempt deletion via absolute path alternative
                elseif (file_exists($absolute_path)) {
                    unlink($absolute_path);
                    error_log("SUCCESS: Deleted file via absolute path: " . $absolute_path);
                } 
                else {
                    // This log will reveal the exact directory PHP is checking
                    error_log("ERROR: File not found on server. Tried: " . realpath($relative_path) . " AND " . $absolute_path);
                }
            }
 
            // 2. Complete status update
            $stmt = $con->prepare("UPDATE tbl_id_uploads SET status = 'verified' WHERE id_upload = ?");
            return $stmt->execute([$id_upload]);
            
        } catch (PDOException $e) {
            error_log("verifyIDUpload Error: " . $e->getMessage());
            return false;
        }
    }
 
   public function delete_upload_record($id_upload) {
    try {
        $con  = $this->openConn();
        $stmt = $con->prepare("SELECT file_name FROM tbl_id_uploads WHERE id_upload = ?");
        $stmt->execute([$id_upload]);
        $file = $stmt->fetch();

        if ($file && !empty($file['file_name'])) {
            $filename = trim($file['file_name']);

            $paths = [
                __DIR__ . '/../../uploads/valid_ids/' . $filename,
                $_SERVER['DOCUMENT_ROOT'] . '/uploads/valid_ids/' . $filename,
                'uploads/valid_ids/' . $filename,
            ];

            $deleted = false;
            foreach ($paths as $path) {
                if (file_exists($path)) {
                    unlink($path);
                    error_log("Deleted valid ID file: $path");
                    $deleted = true;
                    break;
                }
            }

            if (!$deleted) {
                error_log("delete_upload_record: File not found in any path for: $filename");
            }
        }

        $stmt = $con->prepare("DELETE FROM tbl_id_uploads WHERE id_upload = ?");
        return $stmt->execute([$id_upload]);

    } catch (PDOException $e) {
        error_log($e->getMessage());
        return false;
    }
}
 
    public function getPendingIDUploads() {
        try {
            $connection = $this->openConn();
            // Filter added to show only 'pending' items so verified/deleted files don't crowd the admin dashboard
            $sql  = "SELECT u.*, r.fname, r.lname, r.email, r.phone_number FROM tbl_id_uploads u JOIN tbl_resident r ON u.id_resident = r.id_resident WHERE u.status = 'pending' ORDER BY u.upload_date DESC";
            $stmt = $connection->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("getPendingIDUploads Error: " . $e->getMessage());
            return [];
        }
    }
 
    public function getResidentIDUploads($id_resident) {
        try {
            $connection = $this->openConn();
            $sql  = "SELECT * FROM tbl_id_uploads WHERE id_resident = ? ORDER BY upload_date DESC";
            $stmt = $connection->prepare($sql);
            $stmt->execute([$id_resident]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
 
public function approveResidentVerification($id_resident, $id_upload, $admin_name) {
    try {
        $connection = $this->openConn();

        // 1. Mark resident as verified
        $stmt1 = $connection->prepare(
            "UPDATE tbl_resident 
             SET is_verified = 1, verified_at = NOW(), verified_by = ? 
             WHERE id_resident = ?"
        );
        $stmt1->execute([$admin_name, $id_resident]);

        // 2. Mark upload as approved
        $stmt2 = $connection->prepare(
            "UPDATE tbl_id_uploads SET status = 'approved' WHERE id_upload = ?"
        );
        $stmt2->execute([$id_upload]);

        // 3. Delete the physical file from storage
        $stmt3 = $connection->prepare("SELECT file_name FROM tbl_id_uploads WHERE id_upload = ?");
        $stmt3->execute([$id_upload]);
        $file = $stmt3->fetch(PDO::FETCH_ASSOC);

        if ($file && !empty($file['file_name'])) {
            $filename = trim($file['file_name']);
            $paths = [
                __DIR__ . '/../../uploads/valid_ids/' . $filename,
                $_SERVER['DOCUMENT_ROOT'] . '/uploads/valid_ids/' . $filename,
                'uploads/valid_ids/' . $filename,
            ];
            foreach ($paths as $path) {
                if (file_exists($path)) {
                    unlink($path);
                    error_log("approveResidentVerification: Deleted file -> $path");
                    break;
                }
            }
        }

        // 4. Send in-system notification
        $notice = "✅ Your account has been verified! You can now request barangay certificates and other services.";
        $stmt4  = $connection->prepare(
            "INSERT INTO resident_messages (id_resident, message_text, date_sent) 
             VALUES (?, ?, NOW())"
        );
        $stmt4->execute([$id_resident, $notice]);

        // 5. Send Firebase push notification
        $this->sendFCMNotification(
            $id_resident,
            '✅ Account Verified — Barangay San Pedro',
            'Your account has been verified! You can now request barangay certificates and other services.'
        );

        return true;

    } catch (PDOException $e) {
        error_log("approveResidentVerification Error: " . $e->getMessage());
        return false;
    }
}
 
/**
 * Sends a Firebase Cloud Messaging push notification to a resident.
 * Looks up their FCM token from tbl_fcm_tokens and calls the FCM HTTP v1 API.
 *
 * @param int    $id_resident
 * @param string $title
 * @param string $body
 */
    /**
     * Send a push notification to ALL residents who have an FCM token stored.
     * Called after a new announcement is created.
     */
    public function broadcastFCMNotification(string $title, string $body): void {
        try {
            $con  = $this->openConn();
            $stmt = $con->query(
                "SELECT DISTINCT fcm_token FROM tbl_fcm_tokens
                 WHERE fcm_token IS NOT NULL AND fcm_token <> ''
                 ORDER BY updated_at DESC"
            );
            $tokens = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if (empty($tokens)) {
                error_log("broadcastFCMNotification: No FCM tokens found.");
                return;
            }

            $access_token = $this->getFCMAccessToken();
            if (!$access_token) {
                error_log("broadcastFCMNotification: Could not obtain FCM access token.");
                return;
            }

            $project_id = 'barangay-management-syst-3512b';
            $endpoint   = "https://fcm.googleapis.com/v1/projects/{$project_id}/messages:send";
            $headers    = [
                'Authorization: Bearer ' . $access_token,
                'Content-Type: application/json',
            ];

            foreach ($tokens as $token) {
                $payload = json_encode([
                    'message' => [
                        'token'        => $token,
                        'notification' => [
                            'title' => $title,
                            'body'  => $body,
                        ],
                        'webpush' => [
                            'notification' => [
                                'icon' => '/icons/pwa/icon-192x192.png',
                            ],
                            'fcm_options' => [
                                'link' => 'resident_homepage.php',
                            ],
                        ],
                        'data' => [
                            'url' => 'resident_homepage.php',
                        ],
                    ],
                ]);

                $ch = curl_init($endpoint);
                curl_setopt_array($ch, [
                    CURLOPT_POST           => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HTTPHEADER     => $headers,
                    CURLOPT_POSTFIELDS     => $payload,
                    CURLOPT_TIMEOUT        => 10,
                ]);
                $response = curl_exec($ch);
                $err      = curl_error($ch);
                curl_close($ch);

                if ($err) {
                    error_log("broadcastFCMNotification cURL error: {$err}");
                } else {
                    $result = json_decode($response, true);
                    if (!empty($result['error'])) {
                        // Token may be stale — remove it from the DB
                        $code = $result['error']['details'][0]['errorCode'] ?? '';
                        if (in_array($code, ['UNREGISTERED', 'INVALID_ARGUMENT'])) {
                            $del = $con->prepare("DELETE FROM tbl_fcm_tokens WHERE fcm_token = ?");
                            $del->execute([$token]);
                        }
                        error_log("broadcastFCMNotification FCM error for token: {$response}");
                    }
                }
            }

        } catch (Exception $e) {
            error_log("broadcastFCMNotification Exception: " . $e->getMessage());
        }
    }

private function sendFCMNotification(int $id_resident, string $title, string $body): void {
        try {
            // ── 1. Look up the resident's FCM device token ─────────────────
            $con  = $this->openConn();
            $stmt = $con->prepare(
                "SELECT fcm_token FROM tbl_fcm_tokens
                 WHERE resident_id = ? ORDER BY updated_at DESC LIMIT 1"
            );
            $stmt->execute([$id_resident]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
            if (!$row || empty($row['fcm_token'])) {
                error_log("sendFCMNotification: No FCM token for resident {$id_resident}");
                return;
            }
 
            // ── 2. Get OAuth2 access token from service account ────────────
            $access_token = $this->getFCMAccessToken();
            if (!$access_token) {
                error_log("sendFCMNotification: Could not obtain FCM access token.");
                return;
            }
 
            // ── 3. Call FCM HTTP v1 API ────────────────────────────────────
            $project_id = 'barangay-management-syst-3512b';
            $endpoint   = "https://fcm.googleapis.com/v1/projects/{$project_id}/messages:send";
 
            $payload = json_encode([
                'message' => [
                    'token'        => $row['fcm_token'],
                    'notification' => [
                        'title' => $title,
                        'body'  => $body,
                    ],
                    'webpush' => [
                        'notification' => [
                            'icon'  => '/icons/logo.png',
                        ],
                        'fcm_options' => [
                            'link' => 'resident_homepage.php',
                        ],
                    ],
                    'data' => [
                        'url' => 'resident_homepage.php',
                    ],
                ],
            ]);
 
            $ch = curl_init($endpoint);
            curl_setopt_array($ch, [
                CURLOPT_POST           => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER     => [
                    'Authorization: Bearer ' . $access_token,
                    'Content-Type: application/json',
                ],
                CURLOPT_POSTFIELDS => $payload,
                CURLOPT_TIMEOUT    => 10,
            ]);
 $response = curl_exec($ch);
$err      = curl_error($ch);
curl_close($ch);

if ($err) {
    error_log("sendFCMNotification cURL error: {$err}");
} else {
    $result = json_decode($response, true);
    if (!empty($result['error'])) {
        error_log("sendFCMNotification FCM error: {$response}");
    }
}
 
        } catch (Exception $e) {
            error_log("sendFCMNotification Exception: " . $e->getMessage());
        }
    }
/**
 * Returns a short-lived OAuth2 access token for the FCM HTTP v1 API,
 * signed and exchanged by hand (openssl + curl) so no Composer / vendor/
 * folder is required. InfinityFree-friendly.
 *
 * Caches the token in a local file for its lifetime (~55 min) so we don't
 * do a full JWT sign + network round trip on every single notification.
 */
private function getFCMAccessToken(): ?string {
    try {
        $cacheFile = sys_get_temp_dir() . '/bmis_fcm_token.json';

        // ── 1. Serve from cache if still valid ──────────────────────────
        if (is_readable($cacheFile)) {
            $cached = json_decode(file_get_contents($cacheFile), true);
            if (!empty($cached['access_token']) && !empty($cached['expires_at']) && $cached['expires_at'] > time() + 60) {
                return $cached['access_token'];
            }
        }

        // ── 2. Load the service account key ─────────────────────────────
        $serviceAccountPath = __DIR__ . '/../../firebase-service-account.json';
        if (!file_exists($serviceAccountPath)) {
            error_log("FCM: service account file not found at {$serviceAccountPath}");
            return null;
        }

        $sa = json_decode(file_get_contents($serviceAccountPath), true);
        if (!$sa || empty($sa['private_key']) || empty($sa['client_email'])) {
            error_log("FCM: service account JSON is invalid or missing private_key/client_email");
            return null;
        }

        // ── 3. Build and sign the JWT (RS256) ────────────────────────────
        $now = time();
        $header = ['alg' => 'RS256', 'typ' => 'JWT'];
        $claims = [
            'iss'   => $sa['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud'   => 'https://oauth2.googleapis.com/token',
            'iat'   => $now,
            'exp'   => $now + 3600,
        ];

        $b64 = function (string $data): string {
            return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
        };

        $segments  = $b64(json_encode($header)) . '.' . $b64(json_encode($claims));
        $signature = '';
        $ok = openssl_sign($segments, $signature, $sa['private_key'], OPENSSL_ALGO_SHA256);
        if (!$ok) {
            error_log("FCM: openssl_sign failed — check that the openssl PHP extension is enabled.");
            return null;
        }
        $jwt = $segments . '.' . $b64($signature);

        // ── 4. Exchange the JWT for an access token ──────────────────────
        $ch = curl_init('https://oauth2.googleapis.com/token');
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS     => http_build_query([
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'  => $jwt,
            ]),
            CURLOPT_TIMEOUT => 10,
        ]);
        $response = curl_exec($ch);
        $err      = curl_error($ch);
        curl_close($ch);

        if ($err) {
            error_log("FCM: token exchange cURL error: {$err}");
            return null;
        }

        $result = json_decode($response, true);
        if (empty($result['access_token'])) {
            error_log("FCM: token exchange failed: {$response}");
            return null;
        }

        // ── 5. Cache it and return ────────────────────────────────────────
        @file_put_contents($cacheFile, json_encode([
            'access_token' => $result['access_token'],
            'expires_at'   => $now + (int)($result['expires_in'] ?? 3300),
        ]));

        return $result['access_token'];

    } catch (\Throwable $e) {
        error_log("getFCMAccessToken Error: " . $e->getMessage());
        return null;
    }
}
    public function rejectResidentVerification($id_resident, $id_upload, $admin_name, $reason = '') {
        try {
            $connection = $this->openConn();
 
            $stmt1 = $connection->prepare(
                "UPDATE tbl_id_uploads SET status = 'rejected' WHERE id_upload = ?"
            );
            $stmt1->execute([$id_upload]);
 
            $notice = "❌ Your valid ID submission was rejected."
                    . ($reason ? " Reason: " . $reason : "")
                    . " Please upload a clearer or valid government-issued ID.";
            $stmt2  = $connection->prepare(
                "INSERT INTO resident_messages (id_resident, message_text, date_sent) VALUES (?, ?, NOW())"
            );
            $stmt2->execute([$id_resident, $notice]);
 
            $res = $connection->prepare(
                "SELECT fname, lname, email FROM tbl_resident WHERE id_resident = ?"
            );
            $res->execute([$id_resident]);
            $resident = $res->fetch(PDO::FETCH_ASSOC);
 
            if ($resident && !empty($resident['email'])) {
                require_once __DIR__ . '/../Core/Mailer.php';

                $reason_line = $reason
                    ? "<p><strong>Reason:</strong> " . htmlspecialchars($reason) . "</p>"
                    : "";

                $applicant_name = trim($resident['fname'] . ' ' . $resident['lname']);
                $body_html = "
                    <p>Hello <strong>{$applicant_name}</strong>,</p>
                    <p>Unfortunately, your valid ID submission was <strong style='color:#a32d2d;'>not approved</strong>.</p>
                    {$reason_line}
                    <p>Please log in to your account and re-upload a valid government-issued ID. Make sure it is:</p>
                    <ul style=\"margin:0 0 12px; padding-left:20px;\">
                        <li>Clear and readable</li>
                        <li>Not expired</li>
                        <li>A government-issued ID (e.g. PhilSys, Driver's License, Passport)</li>
                    </ul>
                    <p>If you have questions, please contact the barangay office.</p>
                ";

                $body = bmis_mail_template('ID Verification Rejected', $body_html, 'error');
                send_gmail_mail($resident['email'], 'Your BMIS ID Submission Was Rejected', $body, $applicant_name);
            }
 
            return true;
 
        } catch (PDOException $e) {
            error_log("rejectResidentVerification Error: " . $e->getMessage());
            return false;
        }
    }
 
    public function isResidentVerified($id_resident) {
        try {
            $connection = $this->openConn();
            $sql  = "SELECT is_verified FROM tbl_resident WHERE id_resident = ?";
            $stmt = $connection->prepare($sql);
            $stmt->execute([$id_resident]);
            $row  = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row && $row['is_verified'] == 1;
        } catch (PDOException $e) {
            return false;
        }
    }
 
    // ─────────────────────────────────────────────────────────────────────────
    // CERTIFICATE OF RESIDENCY CRUD
    // ─────────────────────────────────────────────────────────────────────────
    public function get_single_certofres($id_resident){
        $id_resident = $_GET['id_resident'];
        $connection  = $this->openConn();
        $stmt = $connection->prepare("SELECT * FROM tbl_rescert where id_resident = ?");
        $stmt->execute([$id_resident]);
        $resident = $stmt->fetch();
        return ($stmt->rowCount() > 0) ? $resident : false;
    }
 
    public function create_certofres() {
        if(isset($_POST['create_certofres'])) {
            $id_rescert  = $_POST['id_rescert'];
            $id_resident = $_POST['id_resident'];
            $lname       = $_POST['lname'];
            $fname       = $_POST['fname'];
            $mi          = $_POST['mi'];
            $age         = $_POST['age'];
            $nationality = $_POST['nationality'];
            $houseno     = $_POST['houseno'];
            $street      = $_POST['street'];
            $brgy        = $_POST['brgy'];
            $municipal   = $_POST['municipal'];
            $date        = $_POST['date'];
            $purpose     = $_POST['purpose'];
 
            $connection = $this->openConn();
            $stmt = $connection->prepare("INSERT INTO tbl_rescert (`id_rescert`,`id_resident`,`lname`,`fname`,`mi`,`age`,`nationality`,`houseno`,`street`,`brgy`,`municipal`,`date`,`purpose`, `remarks`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
           $stmt->execute([$id_rescert, $id_resident, $lname, $fname, $mi, $age, $nationality, $houseno, $street, $brgy, $municipal, $date, $purpose, ""]);

            if (function_exists('bmis_notify_document_ready')) {
                bmis_notify_document_ready($connection, $id_resident, $fname, $lname, 'Certificate of Residency', $purpose);
            }

            notif('Certificate of Residency submitted! You will receive a text message for further details.', 'success');
        }
    }
 
    public function view_certofres(){
        $connection = $this->openConn();
        $stmt = $connection->prepare("SELECT * from tbl_rescert");
        $stmt->execute();
        return $stmt->fetchAll();
    }
 
public function delete_certofres(){
    if(isset($_POST['delete_certofres'])) {
        $id_rescert = $_POST['id_rescert'];
        $this->archive_record('tbl_rescert', 'id_rescert', $id_rescert, 'certofres');

        $connection = $this->openConn();
        $stmt = $connection->prepare("DELETE FROM tbl_rescert WHERE id_rescert = ?");
        $stmt->execute([$id_rescert]);

        $this->log_activity('DELETE_DOCUMENT', 'Document', "Deleted Certificate of Residency #$id_rescert");

        header("Refresh:0");
        exit();
    }
}
     public function create_soloparent() {
        if(isset($_POST['create_soloparent'])) {
            $id_soloparent = $_POST['id_soloparent'];
            $id_resident   = $_POST['id_resident'];
            $lname         = $_POST['lname'];
            $fname         = $_POST['fname'];
            $mi            = $_POST['mi'];
            $civil_status  = $_POST['civil_status'];
            $houseno       = $_POST['houseno'];
            $street        = $_POST['street'];
            $brgy          = $_POST['brgy'];
            $municipal     = $_POST['municipal'];
            $children      = $_POST['children']; // one child per line, format: "Name - Age"
            $purpose       = $_POST['purpose'];
            $date          = $_POST['date'];
 
            $connection = $this->openConn();
            $stmt = $connection->prepare("INSERT INTO tbl_soloparent (`id_soloparent`,`id_resident`,`lname`,`fname`,`mi`,`civil_status`,`houseno`,`street`,`brgy`,`municipal`,`children`,`purpose`,`date`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$id_soloparent, $id_resident, $lname, $fname, $mi, $civil_status, $houseno, $street, $brgy, $municipal, $children, $purpose, $date]);

            if (function_exists('bmis_notify_document_ready')) {
                bmis_notify_document_ready($connection, $id_resident, $fname, $lname, 'Solo Parent Certification', $purpose);
            }

            notif('Solo Parent Certification submitted! You will receive a text message for further details.', 'success');
        }
    }
 
    public function view_soloparent(){
        $connection = $this->openConn();
        $stmt = $connection->prepare("SELECT * from tbl_soloparent");
        $stmt->execute();
        return $stmt->fetchAll();
    }
 
    public function delete_soloparent(){
        if(isset($_POST['delete_soloparent'])) {
            $id_soloparent = $_POST['id_soloparent'];
            $this->archive_record('tbl_soloparent', 'id_soloparent', $id_soloparent, 'soloparent');
 
            $connection = $this->openConn();
            $stmt = $connection->prepare("DELETE FROM tbl_soloparent where id_soloparent = ?");
            $stmt->execute([$id_soloparent]);
 
            $this->log_activity('DELETE_DOCUMENT', 'Document', "Deleted Solo Parent Certification #$id_soloparent");
 
            header("Refresh:0");
            exit();
        }
    }
 
    public function get_single_soloparent($id_resident){
        $id_resident = $_GET['id_resident'];
        $connection  = $this->openConn();
        $stmt = $connection->prepare("SELECT * FROM tbl_soloparent where id_resident = ?");
        $stmt->execute([$id_resident]);
        $resident = $stmt->fetch();
        return ($stmt->rowCount() > 0) ? $resident : false;
    }
    // ─────────────────────────────────────────────────────────────────────────
    // CERTIFICATE OF GOOD MORAL CHARACTER CRUD
    // ─────────────────────────────────────────────────────────────────────────
    public function get_single_certofgoodmoral($id_resident){
        $id_resident = $_GET['id_resident'];
        $connection  = $this->openConn();
        $stmt = $connection->prepare("SELECT * FROM tbl_goodmoral where id_resident = ?");
        $stmt->execute([$id_resident]);
        $resident = $stmt->fetch();
        return ($stmt->rowCount() > 0) ? $resident : false;
    }

    public function create_certofgoodmoral() {
        if(isset($_POST['create_certofgoodmoral'])) {
            $id_goodmoral = $_POST['id_goodmoral'];
            $id_resident  = $_POST['id_resident'];
            $lname        = $_POST['lname'];
            $fname        = $_POST['fname'];
            $mi           = $_POST['mi'];
            $civil_status = $_POST['civil_status'];
            $houseno      = $_POST['houseno'];
            $street       = $_POST['street'];
            $brgy         = $_POST['brgy'];
            $municipal    = $_POST['municipal'];
            $date         = $_POST['date'];
            $purpose      = $_POST['purpose'];

            $connection = $this->openConn();
            $stmt = $connection->prepare("INSERT INTO tbl_goodmoral (`id_goodmoral`,`id_resident`,`lname`,`fname`,`mi`,`civil_status`,`houseno`,`street`,`brgy`,`municipal`,`date`,`purpose`, `remarks`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
           $stmt->execute([$id_goodmoral, $id_resident, $lname, $fname, $mi, $civil_status, $houseno, $street, $brgy, $municipal, $date, $purpose, ""]);

            if (function_exists('bmis_notify_document_ready')) {
                bmis_notify_document_ready($connection, $id_resident, $fname, $lname, 'Certificate of Good Moral Character', $purpose);
            }

            notif('Certificate of Good Moral Character submitted! You will receive a text message for further details.', 'success');
        }
    }

    public function view_certofgoodmoral(){
        $connection = $this->openConn();
        $stmt = $connection->prepare("SELECT * from tbl_goodmoral");
        $stmt->execute();
        return $stmt->fetchAll();
    }

public function delete_certofgoodmoral(){
    if(isset($_POST['delete_certofgoodmoral'])) {
        $id_goodmoral = $_POST['id_goodmoral'];
        $this->archive_record('tbl_goodmoral', 'id_goodmoral', $id_goodmoral, 'goodmoral');

        $connection = $this->openConn();
        $stmt = $connection->prepare("DELETE FROM tbl_goodmoral WHERE id_goodmoral = ?");
        $stmt->execute([$id_goodmoral]);

        $this->log_activity('DELETE_DOCUMENT', 'Document', "Deleted Certificate of Good Moral Character #$id_goodmoral");

        header("Refresh:0");
        exit();
    }
}
 
    public function get_single_certoflivestock($id_resident){
        $id_resident = $_GET['id_resident'];
        $connection  = $this->openConn();
        $stmt = $connection->prepare("SELECT * FROM tbl_livestock where id_resident = ?");
        $stmt->execute([$id_resident]);
        $resident = $stmt->fetch();
        return ($stmt->rowCount() > 0) ? $resident : false;
    }
 
    public function create_certoflivestock() {
        if(isset($_POST['create_certoflivestock'])) {
            $id_livestock = $_POST['id_livestock'];
            $id_resident  = $_POST['id_resident'];
            $lname        = $_POST['lname'];
            $fname        = $_POST['fname'];
            $mi           = $_POST['mi'];
            $civil_status = $_POST['civil_status'];
            $houseno      = $_POST['houseno'];
            $street       = $_POST['street'];
            $brgy         = $_POST['brgy'];
            $municipal    = $_POST['municipal'];
            $animal_type  = $_POST['animal_type'];
            $animal_sex   = $_POST['animal_sex'];
            $age_color    = $_POST['age_color'];
            $brand_marks  = $_POST['brand_marks'];
            $quantity     = $_POST['quantity'];
            $date         = $_POST['date'];
            $purpose      = $_POST['purpose'];
 
            $connection = $this->openConn();
            $stmt = $connection->prepare("INSERT INTO tbl_livestock (`id_livestock`,`id_resident`,`lname`,`fname`,`mi`,`civil_status`,`houseno`,`street`,`brgy`,`municipal`,`animal_type`,`animal_sex`,`age_color`,`brand_marks`,`quantity`,`date`,`purpose`, `remarks`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
           $stmt->execute([$id_livestock, $id_resident, $lname, $fname, $mi, $civil_status, $houseno, $street, $brgy, $municipal, $animal_type, $animal_sex, $age_color, $brand_marks, $quantity, $date, $purpose, ""]);

            if (function_exists('bmis_notify_document_ready')) {
                bmis_notify_document_ready($connection, $id_resident, $fname, $lname, 'Certificate of Livestock Ownership', $purpose);
            }

            notif('Certificate of Livestock Ownership submitted! You will receive a text message for further details.', 'success');
        }
    }
 
    public function view_certoflivestock(){
        $connection = $this->openConn();
        $stmt = $connection->prepare("SELECT * from tbl_livestock");
        $stmt->execute();
        return $stmt->fetchAll();
    }
 
public function delete_certoflivestock(){
    if(isset($_POST['delete_certoflivestock'])) {
        $id_livestock = $_POST['id_livestock'];
        $this->archive_record('tbl_livestock', 'id_livestock', $id_livestock, 'livestock');
 
        $connection = $this->openConn();
        $stmt = $connection->prepare("DELETE FROM tbl_livestock WHERE id_livestock = ?");
        $stmt->execute([$id_livestock]);
 
        $this->log_activity('DELETE_DOCUMENT', 'Document', "Deleted Certificate of Livestock Ownership #$id_livestock");
 
        header("Refresh:0");
        exit();
    }
}
    
     public function create_certofguardianship() {
        if(isset($_POST['create_certofguardianship'])) {
            $id_guardianship = $_POST['id_guardianship'];
            $id_resident     = $_POST['id_resident'];
            $lname           = $_POST['lname'];
            $fname           = $_POST['fname'];
            $mi              = $_POST['mi'];
            $civil_status    = $_POST['civil_status'];
            $houseno         = $_POST['houseno'];
            $street          = $_POST['street'];
            $brgy            = $_POST['brgy'];
            $municipal       = $_POST['municipal'];
            $minor_name      = $_POST['minor_name'];
            $minor_bdate     = $_POST['minor_bdate'];
            $relationship    = $_POST['relationship'];
            $reason          = $_POST['reason'];
            $purpose         = $_POST['purpose'];
            $date            = $_POST['date'];
 
            $connection = $this->openConn();
            $stmt = $connection->prepare("INSERT INTO tbl_guardianship (`id_guardianship`,`id_resident`,`lname`,`fname`,`mi`,`civil_status`,`houseno`,`street`,`brgy`,`municipal`,`minor_name`,`minor_bdate`,`relationship`,`reason`,`purpose`,`date`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$id_guardianship, $id_resident, $lname, $fname, $mi, $civil_status, $houseno, $street, $brgy, $municipal, $minor_name, $minor_bdate, $relationship, $reason, $purpose, $date]);

            if (function_exists('bmis_notify_document_ready')) {
                bmis_notify_document_ready($connection, $id_resident, $fname, $lname, 'Certificate of Guardianship', $purpose);
            }

            notif('Certificate of Guardianship submitted! You will receive a text message for further details.', 'success');
        }
    }
 
    public function view_certofguardianship(){
        $connection = $this->openConn();
        $stmt = $connection->prepare("SELECT * from tbl_guardianship");
        $stmt->execute();
        return $stmt->fetchAll();
    }
 
    public function delete_certofguardianship(){
        if(isset($_POST['delete_certofguardianship'])) {
            $id_guardianship = $_POST['id_guardianship'];
            $this->archive_record('tbl_guardianship', 'id_guardianship', $id_guardianship, 'certofguardianship');
 
            $connection = $this->openConn();
            $stmt = $connection->prepare("DELETE FROM tbl_guardianship where id_guardianship = ?");
            $stmt->execute([$id_guardianship]);
 
            $this->log_activity('DELETE_DOCUMENT', 'Document', "Deleted Certificate of Guardianship #$id_guardianship");
 
            header("Refresh:0");
            exit();
        }
    }
 
    public function get_single_certofguardianship($id_resident){
        $id_resident = $_GET['id_resident'];
        $connection  = $this->openConn();
        $stmt = $connection->prepare("SELECT * FROM tbl_guardianship where id_resident = ?");
        $stmt->execute([$id_resident]);
        $resident = $stmt->fetch();
        return ($stmt->rowCount() > 0) ? $resident : false;
    }
 
    // ─────────────────────────────────────────────────────────────────────────
    // CERTIFICATE OF INDIGENCY CRUD
    // ─────────────────────────────────────────────────────────────────────────
    public function create_certofindigency() {
        if(isset($_POST['create_certofindigency'])) {
            $id_indigency = $_POST['id_indigency'];
            $id_resident  = $_POST['id_resident'];
            $lname        = $_POST['lname'];
            $fname        = $_POST['fname'];
            $mi           = $_POST['mi'];
            $nationality  = $_POST['nationality'];
            $houseno      = $_POST['houseno'];
            $street       = $_POST['street'];
            $brgy         = $_POST['brgy'];
            $municipal    = $_POST['municipal'];
            $purpose      = $_POST['purpose'];
            $date         = $_POST['date'];
 
            $connection = $this->openConn();
            $stmt = $connection->prepare("INSERT INTO tbl_indigency (`id_indigency`,`id_resident`,`lname`,`fname`,`mi`,`nationality`,`houseno`,`street`,`brgy`,`municipal`,`purpose`,`date`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$id_indigency, $id_resident, $lname, $fname, $mi, $nationality, $houseno, $street, $brgy, $municipal, $purpose, $date]);

            if (function_exists('bmis_notify_document_ready')) {
                bmis_notify_document_ready($connection, $id_resident, $fname, $lname, 'Certificate of Indigency', $purpose);
            }

            notif('Certificate of Indigency submitted! You will receive a text message for further details.', 'success');
        }
    }
 
    public function view_certofindigency(){
        $connection = $this->openConn();
        $stmt = $connection->prepare("SELECT * from tbl_indigency");
        $stmt->execute();
        return $stmt->fetchAll();
    }
 
    public function delete_certofindigency(){
        if(isset($_POST['delete_certofindigency'])) {
            $id_indigency = $_POST['id_indigency'];
            $this->archive_record('tbl_indigency', 'id_indigency', $id_indigency, 'certofindigency');

            $connection = $this->openConn();
            $stmt = $connection->prepare("DELETE FROM tbl_indigency where id_indigency = ?");
            $stmt->execute([$id_indigency]);

            $this->log_activity('DELETE_DOCUMENT', 'Document', "Deleted Certificate of Indigency #$id_indigency");

            header("Refresh:0");
            exit();
        }
    }
 
    public function get_single_certofindigency($id_resident){
        $id_resident = $_GET['id_resident'];
        $connection  = $this->openConn();
        $stmt = $connection->prepare("SELECT * FROM tbl_indigency where id_resident = ?");
        $stmt->execute([$id_resident]);
        $resident = $stmt->fetch();
        return ($stmt->rowCount() > 0) ? $resident : false;
    }
 
 
    // ─────────────────────────────────────────────────────────────────────────
    // BARANGAY CLEARANCE CRUD
    // ─────────────────────────────────────────────────────────────────────────
    public function create_brgyclearance() {
        if(isset($_POST['create_brgyclearance'])) {
            $id_clearance = $_POST['id_clearance'];
            $id_resident  = $_POST['id_resident'];
            $lname        = $_POST['lname'];
            $fname        = $_POST['fname'];
            $mi           = $_POST['mi'];
            $purpose      = $_POST['purpose'];
            $houseno      = $_POST['houseno'];
            $street       = $_POST['street'];
            $brgy         = $_POST['brgy'];
            $municipal    = $_POST['municipal'];
            $status       = $_POST['status'];
            $age          = $_POST['age'];
 
            $connection = $this->openConn();
            $stmt = $connection->prepare("INSERT INTO tbl_clearance (`id_clearance`,`id_resident`,`lname`,`fname`,`mi`,`purpose`,`houseno`,`street`,`brgy`,`municipal`,`status`,`age`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$id_clearance, $id_resident, $lname, $fname, $mi, $purpose, $houseno, $street, $brgy, $municipal, $status, $age]);

            if (function_exists('bmis_notify_document_ready')) {
                bmis_notify_document_ready($connection, $id_resident, $fname, $lname, 'Barangay Clearance', $purpose);
            }

            notif('Barangay Clearance request submitted! You will receive a text message for further details.', 'success');
        }
    }
 
    public function get_single_clearance($id_resident){
        $id_resident = $_GET['id_resident'];
        $connection  = $this->openConn();
        $stmt = $connection->prepare("SELECT * FROM tbl_clearance where id_resident = ?");
        $stmt->execute([$id_resident]);
        $resident = $stmt->fetch();
        return ($stmt->rowCount() > 0) ? $resident : false;
    }
 
    public function view_clearance(){
        $connection = $this->openConn();
        $stmt = $connection->prepare("SELECT * from tbl_clearance");
        $stmt->execute();
        return $stmt->fetchAll();
    }
 
    public function delete_clearance(){
        if(isset($_POST['delete_clearance'])) {
            $id_clearance = $_POST['id_clearance'];
            $this->archive_record('tbl_clearance', 'id_clearance', $id_clearance, 'clearance');

            $connection = $this->openConn();
            $stmt = $connection->prepare("DELETE FROM tbl_clearance where id_clearance = ?");
            $stmt->execute([$id_clearance]);

            $this->log_activity('DELETE_DOCUMENT', 'Document', "Deleted Barangay Clearance #$id_clearance");

            header("Refresh:0");
            exit();
        }
    }
 
 
    // ─────────────────────────────────────────────────────────────────────────
    // EXTRA / UTILITY FUNCTIONS
    // ─────────────────────────────────────────────────────────────────────────
    public function check_admin($email) {
        $connection = $this->openConn();
        $stmt = $connection->prepare("SELECT * FROM tbl_admin WHERE email = ?");
        $stmt->Execute([$email]);
        return $stmt->rowCount();
    }
 
        public function validate_admin(){
        // Always block guests first (previous version skipped this and left the page open)
        $this->require_login_or_deny();
        $userdetails = $this->get_userdata();
        $role = $userdetails['role'] ?? '';
        if ($role !== 'administrator' && $role !== 'Admin') {
            $this->show_404();
        }
        return $userdetails;
    }
 
    public function validate_staff() {
        $this->require_login_or_deny();
        $userdetails = $this->get_userdata();
        $role = $userdetails['role'] ?? '';
        if ($role !== 'administrator' && $role !== 'Admin' && $role !== 'user') {
            $this->show_404();
        }
        return $userdetails;
    }

    public function validate_staff_or_admin() {
        $this->require_login_or_deny();
        $userdetails = $this->get_userdata();
        $allowed = ['administrator', 'Admin', 'user'];
        // SK Chairperson is staff (role=user) but must NOT access admin pages
        if (!in_array($userdetails['role'] ?? '', $allowed, true)
            || ($userdetails['position'] ?? '') === 'Sk Chairperson') {
            $this->show_404();
        }
        return $userdetails;
    }
 
 
    // ─────────────────────────────────────────────────────────────────────────
    // DOCUMENT PROCESSING FUNCTIONS
    // ─────────────────────────────────────────────────────────────────────────
    public function create_bspermit() {
        if(isset($_POST['create_bspermit'])) {
            $id_bspermit = $_POST['id_bspermit'];
            $id_resident = $_POST['id_resident'];
            $lname       = $_POST['lname'];
            $fname       = $_POST['fname'];
            $mi          = $_POST['mi'];
            $bsname      = $_POST['bsname'];
            $houseno     = $_POST['houseno'];
            $street      = $_POST['street'];
            $brgy        = $_POST['brgy'];
            $municipal   = $_POST['municipal'];
            $bsindustry  = $_POST['bsindustry'];
            $aoe         = $_POST['aoe'] ?? '';
 
            $connection = $this->openConn();
            $stmt = $connection->prepare("INSERT INTO tbl_bspermit (`id_bspermit`,`id_resident`,`lname`,`fname`,`mi`,`bsname`,`houseno`,`street`,`brgy`,`municipal`,`bsindustry`,`aoe`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$id_bspermit, $id_resident, $lname, $fname, $mi, $bsname, $houseno, $street, $brgy, $municipal, $bsindustry, $aoe]);

            if (function_exists('bmis_notify_document_ready')) {
                bmis_notify_document_ready($connection, $id_resident, $fname, $lname, 'Business Permit');
            }

            notif('Business Permit application submitted! You will receive a text message for further details.', 'success');
        }
    }
 
    public function get_single_bspermit($id_resident){
        $id_resident = $_GET['id_resident'];
        $connection  = $this->openConn();
        $stmt = $connection->prepare("SELECT * FROM tbl_bspermit where id_resident = ?");
        $stmt->execute([$id_resident]);
        $resident = $stmt->fetch();
        return ($stmt->rowCount() > 0) ? $resident : false;
    }
 
    public function view_bspermit(){
        $connection = $this->openConn();
        $stmt = $connection->prepare("SELECT * from tbl_bspermit");
        $stmt->execute();
        return $stmt->fetchAll();
    }
 
    public function delete_bspermit(){
        if(isset($_POST['delete_bspermit'])) {
            $id_bspermit = $_POST['id_bspermit'];
            $this->archive_record('tbl_bspermit', 'id_bspermit', $id_bspermit, 'bspermit');

            $connection = $this->openConn();
            $stmt = $connection->prepare("DELETE FROM tbl_bspermit WHERE id_bspermit = ?");
            $stmt->execute([$id_bspermit]);

            $this->log_activity('DELETE_DOCUMENT', 'Document', "Deleted Barangay Business Permit #$id_bspermit");

            header("Refresh:0");
            exit();
        }
    }
 
public function get_single_youth($id_youth) {
    $connection = $this->openConn();
    $stmt = $connection->prepare("SELECT * FROM tbl_youth WHERE id_youth = ?");
    $stmt->execute([$id_youth]);
    $youth = $stmt->fetch();
    return ($stmt->rowCount() > 0) ? $youth : false;
}
public function create_youth() {
    if(isset($_POST['create_youth'])) {
        // Always the logged-in resident's own id — never a client-supplied one.
        $_ud = $this->get_userdata();
        $id_youth       = $_ud['id_resident'] ?? null;
        if (empty($id_youth)) { return; }
        // Registration happens once. After that the profile is view-only
        // (only the photo can change, via update_youth_photo()).
        if ($this->is_youth_registered($id_youth)) { return; }
        $lname          = $_POST['lname'];
        $fname          = $_POST['fname'];
        $mi             = $_POST['mi'];
        $sex            = $_POST['sex'];
        $civil_status   = $_POST['civil_status'];
        $email_address  = $_POST['email_address'];
        $educ_attain    = $_POST['educ_attain'];
        $emp_status     = $_POST['emp_status'];
        $skill_name     = $_POST['skill_name'];

        $connection = $this->openConn();

        // Age always comes from the resident's own birthdate, never from the
        // form — an editable "age" input can drift out of sync or be tampered
        // with client-side.
        $age = null;
        $bstmt = $connection->prepare("SELECT bdate FROM tbl_resident WHERE id_resident = ?");
        $bstmt->execute([$id_youth]);
        $bdate = $bstmt->fetchColumn();
        if ($bdate) {
            $age = (new DateTime())->diff(new DateTime($bdate))->y;
        }

        // Photo is optional — a resident re-submitting the form without
        // choosing a new file keeps whatever photo is already on record.
        $photo_stmt = $connection->prepare("SELECT photo FROM tbl_youth WHERE id_youth = ?");
        $photo_stmt->execute([$id_youth]);
        $photo_filename = $photo_stmt->fetchColumn() ?: null;

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $validated = bmis_validate_image_upload($_FILES['photo'], /* allow_pdf */ false);

            if ($validated['ok']) {
                $upload_dir = 'uploads/youth_photos/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                $new_filename = 'youth_' . $id_youth . '_' . time() . '_' . $validated['safe_name'];

                if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $new_filename)) {
                    // Replacing an existing photo — remove the old file so
                    // uploads/youth_photos/ doesn't accumulate orphans.
                    if ($photo_filename && is_file($upload_dir . $photo_filename)) {
                        unlink($upload_dir . $photo_filename);
                    }
                    $photo_filename = $new_filename;
                }
            }
            // An invalid/oversized photo is silently skipped rather than
            // blocking the whole profile save — the rest of the form is
            // still valid and worth keeping.
        }

        $stmt = $connection->prepare("
            INSERT INTO tbl_youth 
                (id_youth, lname, fname, mi, age, sex, civil_status, email_address, educ_attain, emp_status, skill_name, photo) 
            VALUES 
                (?,?,?,?,?,?,?,?,?,?,?,?)
            ON DUPLICATE KEY UPDATE
                lname=VALUES(lname), fname=VALUES(fname), mi=VALUES(mi),
                age=VALUES(age), sex=VALUES(sex), civil_status=VALUES(civil_status),
                email_address=VALUES(email_address),
                educ_attain=VALUES(educ_attain), emp_status=VALUES(emp_status),
                skill_name=VALUES(skill_name), photo=VALUES(photo)
        ");
        $stmt->execute([
            $id_youth, $lname, $fname, $mi, $age, $sex,
            $civil_status, $email_address,
            $educ_attain, $emp_status, $skill_name, $photo_filename
        ]);

        if (!session_id()) session_start();
        $_SESSION['flash_message'] = 'Application submitted successfully!';
        $_SESSION['flash_type']    = 'success';

        header("refresh: 0");
    }
}

    // Returns true when the logged-in resident already has a registered youth
    // profile (civil_status, a required registration field, is filled in).
    // After registration the profile is view-only: only the photo may change.
    public function is_youth_registered($id_youth) {
        $connection = $this->openConn();
        $stmt = $connection->prepare("SELECT civil_status FROM tbl_youth WHERE id_youth = ? LIMIT 1");
        $stmt->execute([$id_youth]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($row && !empty($row['civil_status']));
    }

    // Photo-only edit for an already-registered youth profile. Always acts on
    // the logged-in resident's own row (never a client-supplied id), and never
    // touches any other column.
    public function update_youth_photo() {
        if (!isset($_POST['update_youth_photo'])) { return; }

        $userdata   = $this->get_userdata();
        $id_youth   = $userdata['id_resident'] ?? null;
        if (empty($id_youth) || !$this->is_youth_registered($id_youth)) {
            header('Location: resident_youth_profile.php');
            exit();
        }

        $result = 'invalid';
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $validated = bmis_validate_image_upload($_FILES['photo'], /* allow_pdf */ false);
            if ($validated['ok']) {
                $connection = $this->openConn();
                $upload_dir = 'uploads/youth_photos/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                $old_stmt = $connection->prepare("SELECT photo FROM tbl_youth WHERE id_youth = ?");
                $old_stmt->execute([$id_youth]);
                $old_photo = $old_stmt->fetchColumn();

                $new_filename = 'youth_' . $id_youth . '_' . time() . '_' . $validated['safe_name'];
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $new_filename)) {
                    $upd = $connection->prepare("UPDATE tbl_youth SET photo = ? WHERE id_youth = ?");
                    $upd->execute([$new_filename, $id_youth]);
                    if ($old_photo && is_file($upload_dir . $old_photo)) {
                        unlink($upload_dir . $old_photo);
                    }
                    $result = 'updated';
                }
            }
        }
        header('Location: resident_youth_profile.php?photo=' . $result);
        exit();
    }

    public function view_youth(){
        $connection = $this->openConn();
        $stmt = $connection->prepare("SELECT * from tbl_youth");
        $stmt->execute();
        return $stmt->fetchAll();
    }
 
public function delete_youth(){
    if(isset($_POST['delete_youth'])) {
        $id_youth = $_POST['id_youth'];
        $this->archive_record('tbl_youth', 'id_youth', $id_youth, 'youth');

        $connection = $this->openConn();

        // Remove the uploaded photo file along with the record, so
        // uploads/youth_photos/ doesn't accumulate orphaned images.
        $pstmt = $connection->prepare("SELECT photo FROM tbl_youth WHERE id_youth = ?");
        $pstmt->execute([$id_youth]);
        $photo = $pstmt->fetchColumn();
        if ($photo && is_file('uploads/youth_photos/' . $photo)) {
            unlink('uploads/youth_photos/' . $photo);
        }

        // Remove their enrollment in every SK youth program along with the profile.
        $del_enroll = $connection->prepare("DELETE FROM tbl_youth_enrollment WHERE id_youth = ?");
        $del_enroll->execute([$id_youth]);

        $stmt = $connection->prepare("DELETE FROM tbl_youth WHERE id_youth = ?");
        $stmt->execute([$id_youth]);

        $this->log_activity('DELETE_DOCUMENT', 'Document', "Deleted Youth Profile #$id_youth");

        header("Refresh:0");
        exit();
    }
}
 
    public function create_brgyid() {
        if(isset($_POST['create_brgyid'])) {
            $id_resident  = $_POST['id_resident'];
            $lname        = $_POST['lname'];
            $fname        = $_POST['fname'];
            $mi           = $_POST['mi'];
            $houseno      = $_POST['houseno'];
            $street       = $_POST['street'];
            $brgy         = $_POST['brgy'];
            $municipal    = $_POST['municipal'];
            $bplace       = $_POST['bplace'];
            $bdate        = $_POST['bdate'];
            $contact      = $_POST['contact'];
            $inc_lname    = $_POST['inc_lname'];
            $inc_fname    = $_POST['inc_fname'];
            $inc_mi       = $_POST['inc_mi'];
            $inc_contact  = $_POST['inc_contact'];
            $inc_houseno  = $_POST['inc_houseno'];
            $inc_street   = $_POST['inc_street'];
            $inc_brgy     = $_POST['inc_brgy'];
            $relation     = $_POST['relation'];
            $inc_municipal = $_POST['inc_municipal'];
 
            $connection = $this->openConn();
            $stmt = $connection->prepare("INSERT INTO tbl_brgyid (`id_resident`,`lname`,`fname`,`mi`,`houseno`,`street`,`brgy`,`municipal`,`bplace`,`bdate`,`contact`,`relation`,`inc_lname`,`inc_fname`,`inc_mi`,`inc_contact`,`inc_houseno`,`inc_street`,`inc_brgy`,`inc_municipal`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$id_resident, $lname, $fname, $mi, $houseno, $street, $brgy, $municipal, $bplace, $bdate, $contact, $relation, $inc_lname, $inc_fname, $inc_mi, $inc_contact, $inc_houseno, $inc_street, $inc_brgy, $inc_municipal]);

            if (function_exists('bmis_notify_document_ready')) {
                bmis_notify_document_ready($connection, $id_resident, $fname, $lname, 'Barangay ID');
            }

            notif('Barangay ID application submitted! You will receive a text message for further details.', 'success');
        }
    }
 
    public function get_single_brgyid($id_brgyid){
        $id_brgyid  = isset($_GET['id_brgyid']) ? $_GET['id_brgyid'] : $id_brgyid;
        $connection = $this->openConn();
        $stmt = $connection->prepare("SELECT * FROM tbl_brgyid WHERE id_brgyid = ?");
        $stmt->execute([$id_brgyid]);
        $resident = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resident ?: false;
    }
 
    public function view_brgyid(){
        $connection = $this->openConn();
        $stmt = $connection->prepare("SELECT * from tbl_brgyid");
        $stmt->execute();
        return $stmt->fetchAll();
    }
 
    public function delete_brgyid(){
        $id_brgyid = $_POST['id_brgyid'];
        if(isset($_POST['delete_brgyid'])) {
            $this->archive_record('tbl_brgyid', 'id_brgyid', $id_brgyid, 'brgyid');

            $connection = $this->openConn();
            $stmt = $connection->prepare("DELETE FROM tbl_brgyid WHERE id_brgyid = ?");
            $stmt->execute([$id_brgyid]);

            $this->log_activity('DELETE_DOCUMENT', 'Document', "Deleted Barangay ID #$id_brgyid");

            header("Refresh:0");
            exit();
        }
    }
 
    public function create_blotter() {
        if(isset($_POST['create_blotter'])) {
            $id_resident = $_POST['id_resident'];
            $lname       = $_POST['lname'];
            $fname       = $_POST['fname'];
            $mi          = $_POST['mi'];
            $houseno     = $_POST['houseno'];
            $street      = $_POST['street'];
            $brgy        = $_POST['brgy'];
            $municipal   = $_POST['municipal'];
            $contact     = $_POST['contact'];
            $narrative   = $_POST['narrative'];
 
            $connection = $this->openConn();
            $stmt = $connection->prepare("INSERT INTO tbl_blotter (`id_resident`,`lname`,`fname`,`mi`,`houseno`,`street`,`brgy`,`municipal`,`contact`,`narrative`) VALUES (?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$id_resident, $lname, $fname, $mi, $houseno, $street, $brgy, $municipal, $contact, $narrative]);
 
            notif('Blotter/Complaint submitted successfully.', 'success');
        }
    }
 
    public function get_single_blotter($id_resident){
        $id_resident = $_GET['id_resident'];
        $connection  = $this->openConn();
        $stmt = $connection->prepare("SELECT * FROM tbl_blotter where id_resident = ?");
        $stmt->execute([$id_resident]);
        $resident = $stmt->fetch();
        return ($stmt->rowCount() > 0) ? $resident : false;
    }
 
    public function view_blotter(){
        $connection = $this->openConn();
        $stmt = $connection->prepare("SELECT * from tbl_blotter");
        $stmt->execute();
        return $stmt->fetchAll();
    }
 
    public function delete_blotter(){
        if(isset($_POST['delete_blotter'])) {
            $id_blotter = $_POST['id_blotter'];
            $this->archive_record('tbl_blotter', 'id_blotter', $id_blotter, 'blotter');

            $connection = $this->openConn();
            $stmt = $connection->prepare("DELETE FROM tbl_blotter WHERE id_blotter = ?");
            $stmt->execute([$id_blotter]);

            $this->log_activity('DELETE_DOCUMENT', 'Document', "Deleted Blotter Record #$id_blotter");

            header("Refresh:0");
            exit();
        }
    }
 
    public function update_blotter() {
        if (isset($_POST['update_blotter'])) {
            $id_blotter = $_GET['id_blotter'];
            $lname      = $_POST['lname'];
            $fname      = $_POST['fname'];
            $mi         = $_POST['mi'];
            $houseno    = $_POST['houseno'];
            $street     = $_POST['street'];
            $brgy       = $_POST['brgy'];
            $municipal  = $_POST['municipal'];
            $contact    = $_POST['contact'];
            $narrative  = $_POST['narrative'];
 
            $connection = $this->openConn();
            $stmt = $connection->prepare("UPDATE tbl_blotter SET lname=?, fname=?, mi=?, houseno=?, street=?, brgy=?, municipal=?, contact=?, narrative=? WHERE id_blotter=?");
            $stmt->execute([$lname, $fname, $mi, $houseno, $street, $brgy, $municipal, $contact, $narrative, $id_blotter]);
 
            notif('Complaint/Blotter record updated successfully.', 'success');
            header("refresh: 0");
        }
    }
 
    public function add_comment($announcement_id, $user_id, $comment_text) {
        $connection = $this->openConn();
        $stmt = $connection->prepare("INSERT INTO tbl_announcement_comments (announcement_id, user_id, comment_text, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$announcement_id, $user_id, $comment_text]);
        return $connection->lastInsertId();
    }
 
    public function get_comments($announcement_id) {
        $connection = $this->openConn();
        $stmt = $connection->prepare("SELECT c.*, CONCAT(r.fname, ' ', r.lname) AS full_name FROM tbl_announcement_comments c JOIN tbl_resident r ON c.user_id = r.id_resident WHERE c.announcement_id = ? ORDER BY c.created_at ASC");
        $stmt->execute([$announcement_id]);
        return $stmt->fetchAll();
    }
 
public function delete_comment($comment_id, $user_id) {
    $connection = $this->openConn();
    if (!$connection) return;  // ← guard
    $stmt = $connection->prepare("DELETE FROM tbl_announcement_comments WHERE id_comment = ? AND user_id = ?");
    $stmt->execute([$comment_id, $user_id]);
}
 
    public function toggle_reaction($announcement_id, $user_id, $reaction_type) {
        $connection = $this->openConn();
        $check = $connection->prepare("SELECT id_reaction FROM tbl_announcement_reactions WHERE announcement_id = ? AND user_id = ? AND reaction_type = ?");
        $check->execute([$announcement_id, $user_id, $reaction_type]);
        $existing = $check->fetch();
 
        if ($existing) {
            $del = $connection->prepare("DELETE FROM tbl_announcement_reactions WHERE id_reaction = ?");
            $del->execute([$existing['id_reaction']]);
            return 'removed';
        } else {
            $delOld = $connection->prepare("DELETE FROM tbl_announcement_reactions WHERE announcement_id = ? AND user_id = ?");
            $delOld->execute([$announcement_id, $user_id]);
            $ins = $connection->prepare("INSERT INTO tbl_announcement_reactions (announcement_id, user_id, reaction_type, created_at) VALUES (?, ?, ?, NOW())");
            $ins->execute([$announcement_id, $user_id, $reaction_type]);
            return 'added';
        }
    }
 
    public function get_reactions($announcement_id) {
        $connection = $this->openConn();
        $stmt = $connection->prepare("SELECT reaction_type, COUNT(*) as count FROM tbl_announcement_reactions WHERE announcement_id = ? GROUP BY reaction_type");
        $stmt->execute([$announcement_id]);
        return $stmt->fetchAll();
    }
 
    public function get_user_reaction($announcement_id, $user_id) {
        $connection = $this->openConn();
        $stmt = $connection->prepare("SELECT reaction_type FROM tbl_announcement_reactions WHERE announcement_id = ? AND user_id = ?");
        $stmt->execute([$announcement_id, $user_id]);
        $row = $stmt->fetch();
        return $row ? $row['reaction_type'] : null;
    }
    
// ========== BUDGET TRANSPARENCY (with photo) ==========

public function create_budget() {
    $connection = $this->openConn();
    $fiscal_year = $_POST['fiscal_year'] ?? '';
    $category    = $_POST['category'] ?? '';
    $description = $_POST['description'] ?? '';
    $amount      = $_POST['amount'] ?? 0;
    $status      = $_POST['status'] ?? 'Proposed';
    $added_by    = ($_SESSION['lname'] ?? '') . ', ' . ($_SESSION['fname'] ?? '');
    $image_name  = null;

    // Handle image upload
    if (isset($_FILES['budget_img']) && $_FILES['budget_img']['error'] === 0) {
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Reuse existing image validation if available
        if (function_exists('bmis_validate_image_upload')) {
            $validated = bmis_validate_image_upload($_FILES['budget_img']);
            if ($validated['ok']) {
                $image_name  = time() . '_' . $validated['safe_name'];
                $target_file = $upload_dir . $image_name;
                move_uploaded_file($_FILES['budget_img']['tmp_name'], $target_file);
            }
        } else {
            // Simple fallback
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $ext = strtolower(pathinfo($_FILES['budget_img']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $allowed) && $_FILES['budget_img']['size'] <= 5 * 1024 * 1024) {
                $image_name  = time() . '_budget.' . $ext;
                move_uploaded_file($_FILES['budget_img']['tmp_name'], $upload_dir . $image_name);
            }
        }
    }

    $stmt = $connection->prepare(
        "INSERT INTO tbl_budget (fiscal_year, category, description, amount, status, added_by, image)
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    return $stmt->execute([$fiscal_year, $category, $description, $amount, $status, $added_by, $image_name]);
}

public function update_budget() {
    $connection = $this->openConn();
    $id          = $_POST['id_budget'] ?? 0;
    $fiscal_year = $_POST['fiscal_year'] ?? '';
    $category    = $_POST['category'] ?? '';
    $description = $_POST['description'] ?? '';
    $amount      = $_POST['amount'] ?? 0;
    $status      = $_POST['status'] ?? 'Proposed';

    // Get current image
    $cur = $connection->prepare("SELECT image FROM tbl_budget WHERE id_budget = ?");
    $cur->execute([$id]);
    $current_image = $cur->fetchColumn();
    $image_name = $current_image;

    // New image uploaded?
    if (isset($_FILES['budget_img']) && $_FILES['budget_img']['error'] === 0) {
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        if (function_exists('bmis_validate_image_upload')) {
            $validated = bmis_validate_image_upload($_FILES['budget_img']);
            if ($validated['ok']) {
                // Delete old file
                if ($current_image && file_exists($upload_dir . $current_image)) {
                    unlink($upload_dir . $current_image);
                }
                $image_name  = time() . '_' . $validated['safe_name'];
                move_uploaded_file($_FILES['budget_img']['tmp_name'], $upload_dir . $image_name);
            }
        } else {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $ext = strtolower(pathinfo($_FILES['budget_img']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $allowed) && $_FILES['budget_img']['size'] <= 5 * 1024 * 1024) {
                if ($current_image && file_exists($upload_dir . $current_image)) {
                    unlink($upload_dir . $current_image);
                }
                $image_name = time() . '_budget.' . $ext;
                move_uploaded_file($_FILES['budget_img']['tmp_name'], $upload_dir . $image_name);
            }
        }
    }

    $stmt = $connection->prepare(
        "UPDATE tbl_budget
         SET fiscal_year=?, category=?, description=?, amount=?, status=?, image=?
         WHERE id_budget=?"
    );
    return $stmt->execute([$fiscal_year, $category, $description, $amount, $status, $image_name, $id]);
}

public function delete_budget() {
    $connection = $this->openConn();
    $id = $_POST['id_budget'] ?? 0;

    // Delete image file first
    $stmt = $connection->prepare("SELECT image FROM tbl_budget WHERE id_budget = ?");
    $stmt->execute([$id]);
    $img = $stmt->fetchColumn();
    if ($img && file_exists("uploads/" . $img)) {
        unlink("uploads/" . $img);
    }

    $stmt = $connection->prepare("DELETE FROM tbl_budget WHERE id_budget = ?");
    return $stmt->execute([$id]);
}

public function view_budget($year = null) {
    $connection = $this->openConn();
    if ($year) {
        $stmt = $connection->prepare("SELECT * FROM tbl_budget WHERE fiscal_year=? ORDER BY date_added DESC");
        $stmt->execute([$year]);
    } else {
        $stmt = $connection->prepare("SELECT * FROM tbl_budget ORDER BY fiscal_year DESC, date_added DESC");
        $stmt->execute();
    }
    return $stmt->fetchAll();
}

public function get_budget_years() {
    $connection = $this->openConn();
    $stmt = $connection->prepare("SELECT DISTINCT fiscal_year FROM tbl_budget ORDER BY fiscal_year DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

public function get_budget_total($year = null) {
    $connection = $this->openConn();
    if ($year) {
        $stmt = $connection->prepare("SELECT COALESCE(SUM(amount),0) FROM tbl_budget WHERE fiscal_year=?");
        $stmt->execute([$year]);
    } else {
        $stmt = $connection->prepare("SELECT COALESCE(SUM(amount),0) FROM tbl_budget");
        $stmt->execute();
    }
    return $stmt->fetchColumn();
}
 
}
 
$bmis = new BMISClass();
?>