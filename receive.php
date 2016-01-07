<?php
$post = $_POST;
file_put_contents(json_encode($_POST), __DIR__.'/msg.log');
echo 'hello';