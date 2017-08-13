<?php
require_once 'Database.php';
require_once 'Creatures.php';
require_once 'Crimes.php';
require_once 'Notes.php';


$db = new Database();
$cn = null;

if (is_null($cn)) {
    $cn = $db->connect();
}

$creatures_obj = new Creatures($cn);
$crimes_obj = new Crimes($cn);
$notes_obj = new Notes($cn);