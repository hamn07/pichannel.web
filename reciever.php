<?php
$s_file_contents = $_POST['s_file_contents'];

$s_file_contents_sha1 = sha1($s_file_contents);

file_put_contents("./test.jpg", $s_file_contents);


