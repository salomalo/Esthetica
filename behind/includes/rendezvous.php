<?php

$salt = utf8_decode(Hash::salt(32));
echo '<p>' . $salt . '<br>';
echo Hash::make(Input::get('password'), $salt);
echo '</p>';