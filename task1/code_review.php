// Refactor this into maintainable, reusable code (avoid duplication, improve readability, use best practices).

<?php

function getUserData($id) {
    $conn = new mysqli("localhost", "user", "pass", "mydb");
    if ($conn->connect_error) {
        die("Connection failed");
    }
    $sql = "SELECT * FROM users WHERE id = " . $id;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "Name: " . $row["name"] . " - Email: " . $row["email"] . "<br>";
        }
    }
    $conn->close();
}

function printUser($id) {
    $conn = new mysqli("localhost", "user", "pass", "mydb");
    if ($conn->connect_error) {
        die("Connection failed");
    }
    $sql = "SELECT * FROM users WHERE id = " . $id;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "User: " . $row["name"] . " - Role: " . $row["role"] . "<br>";
        }
    }
    $conn->close();
}

getUserData(1);
printUser(1);
