<?php

declare(strict_types=1);

include('../lib/common.php');

function getAllLocations(PDO $pdo): void
{
    // Use paging to read db in chunks instead of single iterator to conserve memory
    // As a result we echo array of rows, where each row is a JSON

    $page = 0;
    $pageSize = 1000;

    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM locations");
        $stmt->execute();
        $rowCount = $stmt->fetchColumn();

        // Send the JSON array start
        echo '[';

        while ($page * $pageSize < $rowCount) {
            // Read and send the contents of array
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
                    $json = json_encode($row);
                    if ($json) {
                        echo $json;
                    }
                    $i++;

                    // Add a comma after JSON when more records are coming
                    if ($page * $pageSize + $i < $rowCount) {
                        echo ',' . PHP_EOL;
                    }
                }
            }
            $page++;
        }

        // Send the JSON array end
        echo ']';

    } catch (Exception $e) {
        echo json_encode(array('error' => $e->getMessage()));
    }
}

// ****** MAIN ******* //

parseDotEnv();

$pdo = connectDB();
getAllLocations($pdo);