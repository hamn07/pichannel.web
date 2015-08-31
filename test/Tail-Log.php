<?php
if (isset($_GET['ajax'])) {
  session_start();
  $handle = fopen('Tail-Log.log', 'r');
  if (isset($_SESSION['offset'])) {
    $data = stream_get_contents($handle, -1, $_SESSION['offset']);
    echo nl2br($data);
  } else {
    fseek($handle, 0, SEEK_END);
    $_SESSION['offset'] = ftell($handle);
  }
  exit();
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <script src="jquery-1.8.2.min.js"></script>
  <script src="jquery-timing.min.js"></script>
  <script>
  $(function() {
    $.repeat(1000, function() {
      $.get('Tail-Log.php?ajax', function(data) {
        $('#tail').html(data);
      });
    });
  });
  </script>
</head>
<body>
  <div id="tail">Starting up...</div>
  <div id="fourth1"></div>
</body>
</html>
