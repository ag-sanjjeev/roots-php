<?php
// header("Content-Type: image/png");
echo "Index Page <br>";

$src ??= 'test'; 
echo $src . PHP_EOL . "File Size: $fileSize";
?>

<img src="<?= $src ?>">