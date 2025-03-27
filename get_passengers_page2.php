<?php
// 数据库连接信息
$host = "localhost";
$user = "root";  // 数据库用户
$password = "KICKICKIC";  // 数据库密码
$dbname = "flight_db";

// 连接数据库
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("数据库连接失败：" . $conn->connect_error);
}

// 获取前端输入参数（日期 & 便名）
$date = $_GET['date'] ?? '2025-03-01';
$flight_name = $_GET['flight_name'] ?? 'RAC861';

// 查询数据库
$sql = "SELECT customers.id AS customer_id, customers.customer_name AS name, reservations.seat_class 
        FROM reservations 
        JOIN customers ON reservations.customer_id = customers.id
        JOIN flights ON reservations.flight_id = flights.id
        WHERE flights.flight_name = ? 
        AND reservations.year = YEAR(?) 
        AND reservations.month = MONTH(?) 
        AND reservations.day = DAY(?)";

// 预处理 SQL 语句
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $flight_name, $date, $date, $date);
$stmt->execute();
$result = $stmt->get_result();

// 处理查询结果
$data = [];
while ($row = $result->fetch_assoc()) {
    $row['seat_class'] = ($row['seat_class'] == 0) ? "ビジネス" : "エコノミー";
    $data[] = $row;
}

// 以 JSON 格式返回数据
header('Content-Type: application/json');
echo json_encode($data);

$stmt->close();
$conn->close();
?>

