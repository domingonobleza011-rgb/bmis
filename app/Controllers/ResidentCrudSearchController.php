<?php 
    require(ROOT_PATH . '/classes/resident.class.php');

    $view = $residentbmis->view_household_list();
    $residentbmis->create_resident();
    $residentbmis->update_resident();
    $residentbmis->delete_resident();

    //$lname = $_GET['lname'];

    $connection = $residentbmis->openConn();

    //$stmt = $connection->prepare("SELECT * from tbl_resident 
    //WHERE lname LIKE '%$lname%'");
    //$stmt->execute();
    //$view = $stmt->fetch();
   
?>
<?php require ROOT_PATH . '/app/Views/resident_crud_search_view.php'; ?>
