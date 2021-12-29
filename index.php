<?php
include('control/c_index.php');
$c_index= new C_index();
$page = 1;
if(!empty($_GET['page'])){
    $page= $_GET['page'];
}
$reeadall=$c_index->Read_index($page);

?>