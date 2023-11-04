<?php

function connectDB()
{
    $dbHost = getenv('MYSQL_HOST');
    $dbUser = getenv('MYSQL_USER');
    $dbPassword = getenv('MYSQL_PASSWORD');
    $dbName = getenv('MYSQL_DATABASE');

    try {
        $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
    return $pdo;
}

function parseDotEnv($filePath = '')
{
    $envFile = $filePath ? $filePath : $_SERVER['DOCUMENT_ROOT'] . '/.env';

    if (file_exists($envFile)) {
        $envVars = parse_ini_file($envFile);

        if ($envVars) {
            foreach ($envVars as $key => $value) {
                putenv("$key=$value");
                $_ENV[$key] = $value;
            }
        } else {
            echo "Error parsing the .env file.";
        }
    }
}