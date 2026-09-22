<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require_once(ROOT_PATH . '/classes/conn.php');
    require(ROOT_PATH . '/classes/resident.class.php');
    require_once(ROOT_PATH . '/pagination_helper.php');
    $userdetails = $bmis->get_userdata();
    $bmis->validate_staff_or_admin();

    $type_meta = [
        'resident'        => ['label' => 'Resident',              'icon' => 'fa-users',          'color' => 'blue'],
        'certofres'       => ['label' => 'Cert. of Residency',    'icon' => 'fa-file-alt',        'color' => 'teal'],
        'certofindigency' => ['label' => 'Cert. of Indigency',    'icon' => 'fa-file-alt',        'color' => 'teal'],
        'clearance'       => ['label' => 'Brgy. Clearance',       'icon' => 'fa-file',            'color' => 'gold'],
        'bspermit'        => ['label' => 'Business Permit',       'icon' => 'fa-file-contract',   'color' => 'gold'],
        'blotter'         => ['label' => 'Peace & Order Report',  'icon' => 'fa-user-shield',     'color' => 'red'],
        'youth'           => ['label' => 'Youth Profile',         'icon' => 'fa-users',           'color' => 'teal'],
        'brgyid'          => ['label' => 'Barangay ID',           'icon' => 'fa-id-card',         'color' => 'blue'],
        'staff'           => ['label' => 'Staff',                 'icon' => 'fa-user-tie',        'color' => 'blue'],
        'message'         => ['label' => 'Resident Message',      'icon' => 'fa-comments',         'color' => 'blue'],
    ];

    // ── Handle permanent delete (single) ────────────────────
    if (isset($_POST['permanent_delete']) && !empty($_POST['id_archive'])) {
        $id = (int)$_POST['id_archive'];
        $stmt = $conn->prepare("DELETE FROM tbl_archive WHERE id_archive = ?");
        $stmt->execute([$id]);
        echo "<script>sessionStorage.setItem('archiveToast', JSON.stringify({type:'delete',msg:'Record permanently deleted.'})); window.location.href='admn_archive.php';</script>";
        exit;
    }

    // ── Handle bulk permanent delete ─────────────────────────
    if (isset($_POST['bulk_permanent_delete']) && !empty($_POST['selected_archives'])) {
        $ids = array_filter($_POST['selected_archives'], 'is_numeric');
        if (!empty($ids)) {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $conn->prepare("DELETE FROM tbl_archive WHERE id_archive IN ($placeholders)");
            $stmt->execute(array_values($ids));
            $count = $stmt->rowCount();
            $msg = "$count record(s) permanently deleted.";
            echo "<script>sessionStorage.setItem('archiveToast', JSON.stringify({type:'delete',msg:" . json_encode($msg) . "})); window.location.href='admn_archive.php';</script>";
            exit;
        }
    }

    // ── Helper: restore one archive row by its data ─────────
    function restore_single_row($conn, $arc) {
        $data = json_decode($arc['record_data'], true);
        $type = $arc['record_type'];
        switch ($type) {

            case 'staff':
                $s = $conn->prepare("
                    INSERT INTO tbl_user
                        (id_user, login_identity, email, phone_number, password,
                         lname, fname, mi, age, sex, address, contact,
                         position, role, addedby, photo)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
                    ON DUPLICATE KEY UPDATE
                        login_identity = VALUES(login_identity),
                        email          = VALUES(email),
                        phone_number   = VALUES(phone_number),
                        password       = VALUES(password),
                        lname          = VALUES(lname),
                        fname          = VALUES(fname),
                        mi             = VALUES(mi),
                        age            = VALUES(age),
                        sex            = VALUES(sex),
                        address        = VALUES(address),
                        contact        = VALUES(contact),
                        position       = VALUES(position),
                        role           = VALUES(role),
                        addedby        = VALUES(addedby),
                        photo          = VALUES(photo)
                ");
                $s->execute([
                    $data['id_user']        ?? null,
                    $data['login_identity'] ?? '',
                    $data['email']          ?? '',
                    $data['phone_number']   ?? '',
                    $data['password']       ?? '',
                    $data['lname']          ?? '',
                    $data['fname']          ?? '',
                    $data['mi']             ?? '',
                    $data['age']            ?? 0,
                    $data['sex']            ?? '',
                    $data['address']        ?? '',
                    $data['contact']        ?? '',
                    $data['position']       ?? '',
                    $data['role']           ?? '',
                    $data['addedby']        ?? '',
                    $data['photo']          ?? '',
                ]);
                return true;

            case 'resident':
                $s = $conn->prepare("
                    INSERT INTO tbl_resident
                        (id_resident, email, phone_number, password,
                         lname, fname, mi, age, sex, status,
                         houseno, street, brgy, municipal, address, contact,
                         bdate, bplace, nationality, voter, family_role, role,
                         is_verified, verified_at, verified_by, addedby)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
                    ON DUPLICATE KEY UPDATE
                        email        = VALUES(email),
                        phone_number = VALUES(phone_number),
                        password     = VALUES(password),
                        lname        = VALUES(lname),
                        fname        = VALUES(fname),
                        mi           = VALUES(mi),
                        age          = VALUES(age),
                        sex          = VALUES(sex),
                        status       = VALUES(status),
                        houseno      = VALUES(houseno),
                        street       = VALUES(street),
                        brgy         = VALUES(brgy),
                        municipal    = VALUES(municipal),
                        address      = VALUES(address),
                        contact      = VALUES(contact),
                        bdate        = VALUES(bdate),
                        bplace       = VALUES(bplace),
                        nationality  = VALUES(nationality),
                        voter        = VALUES(voter),
                        family_role  = VALUES(family_role),
                        role         = VALUES(role),
                        is_verified  = VALUES(is_verified),
                        verified_at  = VALUES(verified_at),
                        verified_by  = VALUES(verified_by),
                        addedby      = VALUES(addedby)
                ");
                $s->execute([
                    $data['id_resident']    ?? null,
                    $data['email']          ?? null,
                    $data['phone_number']   ?? null,
                    $data['password']       ?? '',
                    $data['lname']          ?? '',
                    $data['fname']          ?? '',
                    $data['mi']             ?? '',
                    $data['age']            ?? 0,
                    $data['sex']            ?? '',
                    $data['status']         ?? '',
                    $data['houseno']        ?? null,
                    $data['street']         ?? null,
                    $data['brgy']           ?? null,
                    $data['municipal']      ?? null,
                    $data['address']        ?? null,
                    $data['contact']        ?? null,
                    $data['bdate']          ?? null,
                    $data['bplace']         ?? '',
                    $data['nationality']    ?? '',
                    $data['voter']          ?? '',
                    $data['family_role']    ?? '',
                    $data['role']           ?? 'resident',
                    $data['is_verified']    ?? 0,
                    $data['verified_at']    ?? null,
                    $data['verified_by']    ?? null,
                    $data['addedby']        ?? '',
                ]);
                return true;

            case 'certofres':
                $s = $conn->prepare("INSERT IGNORE INTO tbl_rescert
                    (id_rescert,id_resident,lname,fname,mi,age,nationality,houseno,street,brgy,municipal,date,purpose)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
                $s->execute([$data['id_rescert'],$data['id_resident'],$data['lname'],$data['fname'],$data['mi'],$data['age'],$data['nationality'],$data['houseno'],$data['street'],$data['brgy'],$data['municipal'],$data['date'],$data['purpose']]);
                return true;

            case 'certofindigency':
                $s = $conn->prepare("INSERT IGNORE INTO tbl_indigency
                    (id_indigency,id_resident,lname,fname,mi,nationality,houseno,street,brgy,municipal,purpose,date)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
                $s->execute([$data['id_indigency'],$data['id_resident'],$data['lname'],$data['fname'],$data['mi'],$data['nationality'],$data['houseno'],$data['street'],$data['brgy'],$data['municipal'],$data['purpose'],$data['date']]);
                return true;

            case 'clearance':
                $s = $conn->prepare("INSERT IGNORE INTO tbl_clearance
                    (id_clearance,id_resident,lname,fname,mi,purpose,houseno,street,brgy,municipal,status,age)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
                $s->execute([$data['id_clearance'],$data['id_resident'],$data['lname'],$data['fname'],$data['mi'],$data['purpose'],$data['houseno'],$data['street'],$data['brgy'],$data['municipal'],$data['status'],$data['age']]);
                return true;

            case 'bspermit':
                $s = $conn->prepare("INSERT IGNORE INTO tbl_bspermit
                    (id_bspermit,id_resident,lname,fname,mi,bsname,houseno,street,brgy,municipal,bsindustry,aoe)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
                $s->execute([$data['id_bspermit'],$data['id_resident'],$data['lname'],$data['fname'],$data['mi'],$data['bsname'],$data['houseno'],$data['street'],$data['brgy'],$data['municipal'],$data['bsindustry'],$data['aoe']]);
                return true;

            case 'blotter':
                $s = $conn->prepare("INSERT IGNORE INTO tbl_blotter
                    (id_blotter,id_resident,lname,fname,mi,houseno,street,brgy,municipal,contact,narrative)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?)");
                $s->execute([$data['id_blotter'],$data['id_resident'],$data['lname'],$data['fname'],$data['mi'],$data['houseno'],$data['street'],$data['brgy'],$data['municipal'],$data['contact'],$data['narrative']]);
                return true;

            case 'youth':
                $s = $conn->prepare("INSERT IGNORE INTO tbl_youth
                    (id_youth,lname,fname,mi,age,sex,civil_status,contact_number,email_address,educ_attain,emp_status,skill_name)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
                $s->execute([$data['id_youth'],$data['lname'],$data['fname'],$data['mi'],$data['age'],$data['sex'],$data['civil_status'],$data['contact_number'],$data['email_address'],$data['educ_attain'],$data['emp_status'],$data['skill_name']]);
                return true;

            case 'brgyid':
                $s = $conn->prepare("INSERT IGNORE INTO tbl_brgyid
                    (id_brgyid,id_resident,lname,fname,mi,houseno,street,brgy,municipal,bplace,bdate,contact,relation,inc_lname,inc_fname,inc_contact)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                $s->execute([
                    $data['id_brgyid']    ?? null, $data['id_resident']  ?? null,
                    $data['lname']        ?? '',   $data['fname']         ?? '',
                    $data['mi']           ?? '',   $data['houseno']       ?? '',
                    $data['street']       ?? '',   $data['brgy']          ?? '',
                    $data['municipal']    ?? '',   $data['bplace']        ?? '',
                    $data['bdate']        ?? '',   $data['contact']       ?? '',
                    $data['relation']     ?? '',   $data['inc_lname']     ?? '',
                    $data['inc_fname']    ?? '',   $data['inc_contact']   ?? '',
                ]);
                return true;

            case 'message':
                $ok = true;
                if (!empty($data['admin_messages'])) {
                    $s1 = $conn->prepare("INSERT IGNORE INTO admin_messages
                        (id_admin_msg, id_resident, message_text, date_sent, status, reply_text, reply_date, replied_by)
                        VALUES (?,?,?,?,?,?,?,?)");
                    foreach ($data['admin_messages'] as $m) {
                        $s1->execute([
                            $m['id_admin_msg']  ?? null,
                            $m['id_resident']   ?? null,
                            $m['message_text']  ?? '',
                            $m['date_sent']     ?? null,
                            $m['status']        ?? 'unread',
                            $m['reply_text']    ?? null,
                            $m['reply_date']    ?? null,
                            $m['replied_by']    ?? null,
                        ]);
                    }
                }
                if (!empty($data['resident_messages'])) {
                    $s2 = $conn->prepare("INSERT IGNORE INTO resident_messages
                        (id_message, id_resident, message_text, date_sent)
                        VALUES (?,?,?,?)");
                    foreach ($data['resident_messages'] as $m) {
                        $s2->execute([
                            $m['id_message']   ?? null,
                            $m['id_resident']  ?? null,
                            $m['message_text'] ?? '',
                            $m['date_sent']    ?? null,
                        ]);
                    }
                }
                return $ok;
        }
        return false;
    }

    // ── Handle bulk restore ──────────────────────────────────
    if (isset($_POST['bulk_restore']) && !empty($_POST['selected_archives'])) {
        $ids = array_filter($_POST['selected_archives'], 'is_numeric');
        if (!empty($ids)) {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $rows_stmt = $conn->prepare("SELECT * FROM tbl_archive WHERE id_archive IN ($placeholders)");
            $rows_stmt->execute(array_values($ids));
            $rows = $rows_stmt->fetchAll(PDO::FETCH_ASSOC);

            $success = 0;
            $failed  = 0;
            $restored_ids = [];

            foreach ($rows as $arc) {
                try {
                    if (restore_single_row($conn, $arc)) {
                        $restored_ids[] = $arc['id_archive'];
                        $success++;
                    }
                } catch (Exception $e) {
                    $failed++;
                }
            }

            if (!empty($restored_ids)) {
                $del_placeholders = implode(',', array_fill(0, count($restored_ids), '?'));
                $conn->prepare("DELETE FROM tbl_archive WHERE id_archive IN ($del_placeholders)")
                     ->execute($restored_ids);
            }

            $msg = "$success record(s) restored successfully.";
            if ($failed > 0) $msg .= " $failed failed.";
            echo "<script>sessionStorage.setItem('archiveToast', JSON.stringify({type:'restore',msg:" . json_encode($msg) . "})); window.location.href='admn_archive.php';</script>";
            exit;
        }
    }

    // ── Handle restore (re-insert) ───────────────────────────
    if (isset($_POST['restore_record']) && !empty($_POST['id_archive'])) {
        $id  = (int)$_POST['id_archive'];
        $row = $conn->prepare("SELECT * FROM tbl_archive WHERE id_archive = ?");
        $row->execute([$id]);
        $arc = $row->fetch(PDO::FETCH_ASSOC);

        $restored = false;
        if ($arc) {
            try {
                $restored = restore_single_row($conn, $arc);

                if ($restored) {
                    $del = $conn->prepare("DELETE FROM tbl_archive WHERE id_archive = ?");
                    $del->execute([$id]);
                    echo "<script>sessionStorage.setItem('archiveToast', JSON.stringify({type:'restore',msg:'Record restored successfully!'})); window.location.href='admn_archive.php';</script>";
                    exit;
                }
            } catch (Exception $e) {
                $msg = addslashes($e->getMessage());
                echo "<script>alert('SQL Error: $msg');</script>";
            }
        }
    }

    // ── Filter parameters ────────────────────────────────────
    $filter_type    = isset($_GET['type'])    ? $_GET['type']    : '';
    $filter_keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
    $filter_status  = isset($_GET['status'])  ? $_GET['status']  : 'all';

    // ── Build query ──────────────────────────────────────────
    $where  = [];
    $params = [];

    if ($filter_type !== '') {
        $where[]  = "record_type = ?";
        $params[] = $filter_type;
    }
    if ($filter_keyword !== '') {
        $where[]  = "(full_name LIKE ? OR summary LIKE ?)";
        $params[] = "%$filter_keyword%";
        $params[] = "%$filter_keyword%";
    }
    $sql = "SELECT * FROM tbl_archive";
    if ($where) $sql .= " WHERE " . implode(" AND ", $where);
    $sql .= " ORDER BY deleted_at DESC";

    // ── Pagination (10 per page) ─────────────────────────────
    $page    = max(1, (int)($_GET['page'] ?? 1));
    $perPage = 10;

    $count_sql = "SELECT COUNT(*) FROM tbl_archive";
    if ($where) $count_sql .= " WHERE " . implode(" AND ", $where);
    $count_stmt = $conn->prepare($count_sql);
    $count_stmt->execute($params);
    $total_records = (int)$count_stmt->fetchColumn();
    $last_page = max(1, (int)ceil($total_records / $perPage));
    $page      = min($page, $last_page);
    $offset    = ($page - 1) * $perPage;

    $stmt = $conn->prepare($sql . " LIMIT $perPage OFFSET $offset");
    $stmt->execute($params);
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $paged = [
        'rows'      => $records,
        'total'     => $total_records,
        'page'      => $page,
        'per_page'  => $perPage,
        'last_page' => $last_page,
    ];

    // ── Counts per type ──────────────────────────────────────
    $counts_stmt = $conn->query("SELECT record_type, COUNT(*) as cnt FROM tbl_archive GROUP BY record_type");
    $counts = [];
    foreach ($counts_stmt->fetchAll(PDO::FETCH_ASSOC) as $c) {
        $counts[$c['record_type']] = $c['cnt'];
    }
    $total_archived = array_sum($counts);
?>
<?php require ROOT_PATH . '/app/Views/admn_archive_view.php'; ?>
