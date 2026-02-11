<?php
$conn = new mysqli("localhost", "root", "", "student_db");

$result = $conn->query("SELECT * FROM students");

echo "<table border='1'>
<tr>
<th>Name</th>
<th>Email</th>
<th>DOB</th>
<th>Department</th>
<th>Phone</th>
</tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
    <td>{$row['name']}</td>
    <td>{$row['email']}</td>
    <td>{$row['dob']}</td>
    <td>{$row['department']}</td>
    <td>{$row['phone']}</td>
    </tr>";
}

echo "</table>";

$conn->close();
?>
