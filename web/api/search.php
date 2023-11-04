<?php

declare(strict_types=1);

include('../lib/common.php');

function getLocationById($pdo, $id) {

    try {
        $sql = "SELECT * FROM locations WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($results === false) {
            // If no record was found, you can throw a custom exception.
            throw new Exception("Location with ID $id not found");
        }

        return $results;
    } catch (PDOException $e) {
        error_log('Database Error: ' . $e->getMessage());
        return null;
    } catch (Exception $e) {
        error_log('Custom Error: ' . $e->getMessage());
        return null;
    }
}

function searchForNeighbours($pdo, $id) {

    $locationData = getLocationById($pdo, $id);

    if (!$locationData) {
        $message = 'Given location ID not found';
        error_log($message);
        return array('error' => $message);
    }

    try {
        // use Haversine formula to calculate the distances
        $sql = '
            SELECT
                id,
                address, city, state, zip,
                latitude,
                longitude,
                (
                    6371 * 
                    acos(
                        cos(radians(:given_lat)) * cos(radians(latitude)) *
                        cos(radians(longitude) - radians(:given_lon)) +
                        sin(radians(:given_lat)) * sin(radians(latitude))
                    )
                ) AS distance
            FROM
                locations
            WHERE
                id != :given_id
            ORDER BY
                distance
            LIMIT 5;
        ';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':given_id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':given_lat', $locationData['latitude']);
        $stmt->bindParam(':given_lon', $locationData['longitude']);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (\Exception $e) {
        return (array('error' => $e->getMessage()));
    }
}

// ****** MAIN ******* //

$result = array();

parseDotEnv();

if (isset($_GET['id'])) {
    $id = (int)($_GET['id']);
    if ($id > 0) {
        $pdo = connectDB();
        $result = searchForNeighbours($pdo, $id);
    } else {
        $message = 'Given location ID must be positive';
        error_log($message);
        $result = array('error' => $message);
    }
}
header('Content-Type: application/json');
echo json_encode($result);