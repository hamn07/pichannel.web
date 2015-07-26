<?php
$s_file_contents = $_POST['s_file_contents'];

$s_file_contents_sha1 = sha1($s_file_contents);

file_put_contents("./test1.jpg", $s_file_contents);

var_dump($s_file_contents);
