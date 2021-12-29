<?php
include('control/c_index.php');
$c_index = new C_index();
$id = $_GET['id'];
$reeadall = $c_index->deleteCategory($id);
