<?php require __DIR__ . '/Partials/nav.partial.view.php' ?>
<h1>Home page</h1>

<?php
foreach ($users as $user) {
    echo "<pre>";
    print_r($user);
    echo "</pre>";
}
?>