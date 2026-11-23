<?php
$pdo = new PDO('mysql:host=127.0.0.1', 'root', '');
$pdo->exec('CREATE DATABASE IF NOT EXISTS db_notification');
echo "Database db_notification created successfully.\n";
