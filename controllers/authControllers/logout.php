<?php
session_start();
session_unset();
session_destroy();
header('Location: /UAS_PBW_RPL/index.php');
exit;
