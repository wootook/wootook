<?php

include './bootstrap.php';

$username = uniqid();
$email = $username . '@wootook.org';
$password = substr(sha1(uniqid()), mt_rand(0, 32), 8);

Wootook_Empire_Model_User::register($username, $email, $password);