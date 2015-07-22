<?php
// $exif_data = exif_read_data('DSCF0306.JPG');


$exif_data = @exif_read_data('Stephen-Curry-Wallpaper-IPad-1.png');
if ($exif_data){
  print_r($exif_data['DateTimeOriginal']);
  echo'<br>';
  print_r(strtotime($exif_data['DateTimeOriginal']));
  echo'<br>';
  print_r($exif_data['DateTimeDigitized']);
}

//var_dump($exif_data);
