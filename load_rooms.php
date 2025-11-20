<?php
include('databaseconnect.php');

// POST variables
$hotelId   = $_POST['hotel_id'] ?? null;
$check_in  = $_POST['check_in'] ?? date('Y-m-d');
$check_out = $_POST['check_out'] ?? date('Y-m-d', strtotime('+7 days'));

if (!$hotelId) 
{
    echo "<p class='text-danger'>Hotel ID missing.</p>";
    exit;
}

// return room images array
function getRoomImages($room) 
{
    return array_filter([$room['Image1'], $room['Image2'], $room['Image3']]);
}

try 
{
    $stmtHotel = $conn->prepare("SELECT Hotel_Name FROM hotel_details WHERE Hotel_Id = :hotelId");
    $stmtHotel->execute([':hotelId' => $hotelId]);
    $hotelData = $stmtHotel->fetch(PDO::FETCH_ASSOC);
    $hotelName = $hotelData['Hotel_Name'] ?? 'Unknown Hotel';

    $stmt = $conn->prepare("
        SELECT Room_Type, Image1, Image2, Image3, Bed_Type, Price, Room_Square_Meter
        FROM hotels_rooms
        WHERE Hotel_Id = :hotelId AND Is_Active = 1
        ORDER BY Room_Type
    ");
    $stmt->execute([':hotelId' => $hotelId]);
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$rooms) 
    {
        echo "<p class='text-center'>No rooms found.</p>";
        exit;
    }

    echo "<div class='container mt-4'><div class='row g-4'>";
    $types = array_unique(array_column($rooms, 'Room_Type'));

    foreach ($types as $type) 
    {
        $typeRooms = array_filter($rooms, fn($r) => $r['Room_Type'] === $type);
        $allImages = [];

        foreach ($typeRooms as $room) 
        {
            $allImages = array_merge($allImages, getRoomImages($room));
        }

        $allImages = array_unique($allImages);
        $imgPath = !empty($allImages) ? $allImages[array_rand($allImages)] : 'images/rooms/default.jpg';
        $room = $typeRooms[array_rand($typeRooms)];

        echo "<div class='col-md-4'>";
        echo "<div class='card h-100'>";
        echo "<img src='{$imgPath}' class='card-img-top' style='height:200px; object-fit:cover;' alt='{$type} Room'>";
        echo "<div class='card-body text-center'>";
        echo "<h5 class='card-title'>{$type} Room</h5>";
        echo "<p class='card-text'>Beds: {$room['Bed_Type']}</p>";
        echo "<p class='card-text'>Size: {$room['Room_Square_Meter']} m²</p>";
        echo "<p class='card-text'>Price: € {$room['Price']}</p>";

        // Book Room form sends hotel ID, room type, and selected dates
        echo "
            <form action='set_booking.php' method='POST'>
                <input type='hidden' name='hotel_id' value='" . htmlspecialchars($hotelId) . "'>
                <input type='hidden' name='room_type' value='" . htmlspecialchars($type) . "'>
                <input type='hidden' name='check_in' value='" . htmlspecialchars($check_in) . "'>
                <input type='hidden' name='check_out' value='" . htmlspecialchars($check_out) . "'>
                <button class='btn btn-primary w-100'>Book Room</button>
            </form>
        ";

        echo "</div></div></div>";
    }

    echo "</div></div>";

} 
catch (PDOException $e) 
{
    echo "<p class='text-danger'>Error fetching rooms: " . $e->getMessage() . "</p>";
}

$conn = null;
?>
