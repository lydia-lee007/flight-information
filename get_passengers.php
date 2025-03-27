<?php
include 'db.php';

$date = $_GET['date'] ?? '2025-03-01';
$flight_name = $_GET['flight_name'] ?? 'RAC861';

$sql = "SELECT customers.id AS customer_id, customers.customer_name, reservations.seat_class 
        FROM reservations 
        JOIN customers ON reservations.customer_id = customers.id 
        JOIN flights ON reservations.flight_id = flights.id 
        WHERE flights.flight_name = '$flight_name'
        AND reservations.year = YEAR('$date')
        AND reservations.month = MONTH('$date')
        AND reservations.day = DAY('$date')";

$result = $conn->query($sql);
$data = [];
while ($row = $result->fetch_assoc()) {
	$row['seat_class']=($row['seat_class']==0)?"ビジネス":"エコノミー";
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
?>
