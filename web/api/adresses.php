<?php

declare(strict_types=1);

include('../lib/common.php');

function getAllLocations($pdo)
{
    $batchSize = 1000;

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM locations");
    $stmt->execute();
    $rowCount = $stmt->fetchColumn();

    $page = 0;
    $pageSize = $batchSize;

    // Send the JSON array start
    echo '[';

    while ($page * $pageSize < $rowCount) {
        $query = "SELECT * FROM locations LIMIT :offset, :pageSize";
        $offset = $page * $pageSize;

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':pageSize', $pageSize, PDO::PARAM_INT);
        $stmt->execute();

        $batch = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($batch)) {
            $i = 0;
            foreach ($batch as $row) {
                echo json_encode($row);
                $i++;
                // Add a comma if there are more records coming
                if ($page * $pageSize + $i < $rowCount) {
                    echo ','.  PHP_EOL;
                }
            }
        }
        $page++;
    }

    // Send the JSON array end
    echo ']';
}

// ****** MAIN ******* //

parseDotEnv();

$pdo = connectDB();
getAllLocations($pdo);