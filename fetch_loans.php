<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lms";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT loan.loan_id, loan.ref_no, loan.loan_type, loan.amount, loan.total_plan, loan.monthly_payment, loan.overdue_penalty, customer.firstname, customer.middlename, customer.lastname, customer.contact_no, customer.city
        FROM loan
        JOIN customer ON loan.customer_id = customer.customer_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["loan_id"] . "</td>";
        echo "<td>Name: " . $row["firstname"] . " " . $row["middlename"] . " " . $row["lastname"] . "<br>Contact: " . $row["contact_no"] . "<br>Address: " . $row["city"] . "</td>";
        echo "<td>Reference no: " . $row["ref_no"] . "<br>Loan Type: " . $row["loan_type"] . "<br>Amount: " . $row["amount"] . "<br>Total Payable Amount: " . $row["total_plan"] . "<br>Monthly Payable Amount: " . $row["monthly_payment"] . "<br>Overdue Payable Amount: " . $row["overdue_penalty"] . "</td>";
        echo "<td></td>"; // Placeholder for Payment Detail
        echo "<td><span class='status'>For Approval</span></td>";
        echo "<td><button class='action-btn'>Action</button></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No loans found</td></tr>";
}

$conn->close();
?>
