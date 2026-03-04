<?php
session_start();
session_destroy();
header("Location: business_owner_login.php");
exit();
