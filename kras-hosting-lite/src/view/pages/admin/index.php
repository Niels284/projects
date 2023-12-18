<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$_SESSION['current_page'] = 'admin';

if (!isset($_SESSION['user']) || !array_key_exists('id', $_SESSION['user'])) {
  header('location: ../login');
} else {
  if (!isset($_GET['admin_page'])) {
    header('location: ./pakketten');
  } else {
    header('location: ./' . $_GET['admin_page']);
  }
}
