
<?php
$target_path = "uploads/";
$target_path = $target_path . basename( $_FILES['picture']['name']); 
if(move_uploaded_file($_FILES['picture']['tmp_name'], $target_path)) {
    echo "1";
}
print_r($_FILES);
?>