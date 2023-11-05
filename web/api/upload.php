<?php

declare(strict_types=1);

include('../lib/common.php');

function error(string $message): void
{
    header("Location: /?message=" . urlencode($message));
    exit;
}

function processUpload(): string
{
// Define the target directory where uploaded files will be saved.
    $targetDirectory = $_SERVER["DOCUMENT_ROOT"] . "/uploads/";

// Ensure the "uploads" directory exists and is writable.
    if (!file_exists($targetDirectory) || !is_writable($targetDirectory)) {
        error("The 'uploads' directory is missing or not writable.");
    }

// Check if a file was uploaded via a POST request.
    if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_FILES["fileToUpload"])) {
        error("Invalid method or file not specified.");
    }

    $file = $_FILES["fileToUpload"];

    if ($file["error"] !== UPLOAD_ERR_OK) {
        error("File upload failed with error code: " . $file["error"]);
    }

    $originalFileName = basename($file["name"]);
    $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);

    if (strtolower($fileExtension) != "csv") {
        error("File type not allowed.");
    }

// Generate a unique file name to prevent overwriting.
    $newFileName = uniqid() . '_' . bin2hex(random_bytes(4)) . '.' . $fileExtension;
    $targetPath = $targetDirectory . $newFileName;

    if (!move_uploaded_file($file["tmp_name"], $targetPath)) {
        error("Error moving the uploaded file.");
    }

    return $targetPath;
}

function createTableIfNotExists(PDO $pdo, string $tableName = 'locations'): void
{
    $tableExists = 0;
    try {
        $query = "SELECT COUNT(*) FROM information_schema.tables WHERE table_name = '{$tableName}'";
        $stmt = $pdo->query($query);
        if ($stmt) {
            $tableExists = $stmt->fetchColumn();
        }
    } catch (PDOException $e) {
        error("Can not check if {$tableName} exists");
    }

    if ($tableExists === 0) {
        try {
            $query = "
                CREATE TABLE IF NOT EXISTS {$tableName} (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    address VARCHAR(128) NOT NULL DEFAULT '',
                    city VARCHAR(128) NOT NULL DEFAULT '',
                    state VARCHAR(128) NOT NULL DEFAULT '',
                    zip VARCHAR(8) NOT NULL DEFAULT '',
                    latitude DOUBLE NULL,
                    longitude DOUBLE NULL
                )";
            $pdo->exec($query);
        } catch (PDOException $e) {
            error("Unable to create table {$tableName}: " . $e->getMessage());
        }
    }
}

function importCSV(PDO $pdo, string $csvFile, string $tableName = 'locations'): void
{
    try {
        $handle = fopen($csvFile, "r");
        if ($handle === false) {
            return;
        }

        $requiredColumns = ['Address', 'City', 'State', 'Zip', 'Latitude', 'Longitude'];
        $header = fgetcsv($handle, 1000, ",");
        if (!$header) {
            return;
        }
        $missingColumns = array_diff($requiredColumns, $header);
        if (!empty($missingColumns)) {
            error("The following required columns are missing: " . implode(', ', $missingColumns));
        }

        $pdo->exec("TRUNCATE TABLE {$tableName}");

        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO {$tableName} (address, city, state, zip, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?)");
        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            $stmt->execute($data);
        }

        $pdo->commit();

        fclose($handle);
        unlink($csvFile);
    } catch (PDOException $e) {
        $pdo->rollBack();
        error("Error: " . $e->getMessage());
    }
}

parseDotEnv();

$newFileName = processUpload();
$pdo = connectDB();
createTableIfNotExists($pdo);
importCSV($pdo, $newFileName);
header("Location: /table.html");
