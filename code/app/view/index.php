<?php

echo "<br/>";
echo "<br/>";
    var_dump($_SERVER['REQUEST_URI']);
echo "<br/>";
echo "<hr/>";

if($_SERVER['REQUEST_URI'] === '/index.php'){
    header("Location: /");
    exit();
}

$title = 'User list';
require_once ROOT_DIR . '/app/models/User.php';
ob_start();

?>
<h1>Home Page</h1>

<?php
$content = ob_get_clean();
include ROOT_DIR . '/app/view/layout.php';


echo '<div class="container border-1 bg-black border-white font-monospace">';
echo '</div>';
?>


