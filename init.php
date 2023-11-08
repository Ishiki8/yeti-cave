<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

session_start();

CONST HOST = 'localhost';
CONST USER = 'nmypgpsb';
CONST PASSWORD = 'dR5FC1';
CONST DATABASE = 'nmypgpsb_m3';

$con = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

if (!$con) {
    print('Ошибка подключения: '.mysqli_connect_error());
}

mysqli_set_charset($con, 'utf8');