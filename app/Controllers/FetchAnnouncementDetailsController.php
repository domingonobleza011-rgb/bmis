<?php

    require(ROOT_PATH . '/classes/resident.class.php');
    // Ensure $bmis is initialized if not done automatically in the require
    // $bmis = new BMISClass(); 

    $id = (int)$_GET['id'];
    $details = $bmis->get_single_announcement($id);
    $reactions = $bmis->get_reactions($id);
    $comments = $bmis->get_comments($id);

    // Calculate total reactions
    $total_r = 0;
    if(is_array($reactions)) {
        foreach($reactions as $r) $total_r += $r['count'];
    }
?>
<?php require ROOT_PATH . '/app/Views/fetch_announcement_details_view.php'; ?>
