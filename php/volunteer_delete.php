<?php

require 'config.php';

$currentVolunteerId = $_GET['id'];
echo $currentVolunteerId;

header("Location: collection_list.php?success=1");
exit();
?>

