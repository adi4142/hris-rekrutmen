<?php

$host = '127.0.0.1';
$db   = 'pkl_hrd';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     echo "Connected to database.\n";

     echo "Updating job_applicants data...\n";
     $pdo->exec("UPDATE job_applicants SET gender = 'male' WHERE gender = 'Male'");
     $pdo->exec("UPDATE job_applicants SET gender = 'female' WHERE gender = 'Female'");
     
     echo "Updating employees data...\n";
     $pdo->exec("UPDATE employees SET gender = 'male' WHERE gender = 'Male'");
     $pdo->exec("UPDATE employees SET gender = 'female' WHERE gender = 'Female'");

     echo "Changing ENUM definition for job_applicants...\n";
     $pdo->exec("ALTER TABLE job_applicants MODIFY COLUMN gender ENUM('male', 'female')");
     
     echo "Changing ENUM definition for employees...\n";
     $pdo->exec("ALTER TABLE employees MODIFY COLUMN gender ENUM('male', 'female')");

     echo "Success! Database updated to allow 'male' and 'female' (lowercase).\n";
} catch (\PDOException $e) {
     echo "Database Error: " . $e->getMessage() . "\n";
}
