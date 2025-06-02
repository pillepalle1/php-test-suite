// Fix the code to prevent SQL injection.

<?php

$conn = new mysqli("localhost", "user", "pass", "mydb");
$id = $_GET['id'];
$sql = "SELECT * FROM users WHERE id = $id";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    echo $row['name'] . "<br>";
}
