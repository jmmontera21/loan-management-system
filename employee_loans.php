<?php
date_default_timezone_set("Etc/GMT+8");
include 'db_connect.php';

$message = '';

function generateRandomString($length = 8) {
    $characters = '0123456789';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

// Handle loan deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_loan_id'])) {
    $loanIdToDelete = $_POST['delete_loan_id'];
    
    $sql = "DELETE FROM loan WHERE loan_id = '$loanIdToDelete'";
    
    if (mysqli_query($conn, $sql)) {
        $message = "Loan deleted successfully.";
    } else {
        $message = "Error deleting loan: " . mysqli_error($conn);
    }
}



// Handle form submission (both add and edit)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $borrower = $_POST['borrower'];
    $loanType = $_POST['loanType'];
    $loanPlan = $_POST['loanPlan'];
    $amount = $_POST['amount'];
    $purpose = $_POST['purpose'];
    $total = $_POST['total'];
    $monthlyPayment = $_POST['monthlyPayment'];
    $overduePenalty = $_POST['overduePenalty'];

    // Check if status is set in $_POST
    $status = isset($_POST['status']) ? $_POST['status'] : '';

    if (!empty($_POST['loan_id'])) {
        // Edit existing loan
        $loanId = $_POST['loan_id'];
        $sql = "UPDATE loan SET
                    loan_type='$loanType',
                    customer_id='$borrower',
                    purpose='$purpose',
                    amount='$amount',
                    lplan_id='$loanPlan',
                    total_plan='$total',
                    monthly_payment='$monthlyPayment',
                    overdue_penalty='$overduePenalty',
                    status='$status'
                WHERE loan_id='$loanId'";

        if (mysqli_query($conn, $sql)) {
            $message = "Loan application updated successfully.";
        } else {
            $message = "Error updating loan: " . mysqli_error($conn);
        }
    } else {
        // Add new loan
        $ref_no = generateRandomString(8);

        $sql = "INSERT INTO loan (ref_no, loan_type, customer_id, purpose, amount, lplan_id, status, total_plan, monthly_payment, overdue_penalty, date_released, date_created) 
                VALUES ('$ref_no', '$loanType', '$borrower', '$purpose', '$amount', '$loanPlan', '$status', '$total', '$monthlyPayment', '$overduePenalty', '', NOW())";

        if (mysqli_query($conn, $sql)) {
            $message = "Loan application created successfully.";
        } else {
            $message = "Error creating loan: " . mysqli_error($conn);
        }
    }
}

// Fetch customers data
$customers = [];
$sql = "SELECT customer_id, firstname, middlename, lastname FROM customer";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $customers[] = $row;
    }
}

$loanPlans = [];
$sql = "SELECT * FROM loan_plan";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $loanPlans[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Management System Dashboard</title>
    <link rel="stylesheet" href="css/employee.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
</head>
<body>
<div class="sidebar">
    <div class="sidebar-header" onclick="toggleSidebar()">
        <span class="role">PANEL</span>
        <span class="dropright-icon">&#60;</span>
    </div>
    <ul class="nav-links">
        <li class="nav-item">
            <a href="employee_home.php" class="nav-link">
                <img src="img/dashboard.png" alt="Home">
                <span class="text">Home</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="employee_customers.php" class="nav-link">
                <img src="img/customer.png" alt="Customers">
                <span class="text">Customers</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="employee_loans.php" class="nav-link active">
                <img src="img/loans.png" alt="Loans">
                <span class="text">Loans</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="employee_payments.php" class="nav-link">
                <img src="img/credit-card.png" alt="Payments">
                <span class="text">Payments</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="employee_map.php" class="nav-link">
                <img src="img/map.png" alt="Map">
                <span class="text">Map</span>
            </a>
        </li>
    </ul>
</div>

<div class="main-content">
    <header>
        <h1>Loan Management System</h1>
        <div class="user-wrapper">
            <div class="notification">
                <span class="icon" onclick="showNotifications()" style="cursor: pointer;">🔔</span>
            </div>
            <div class="user" onclick="toggleDropdown()">
                <span class="dropdown-icon">▼</span>
                <span class="username">Employee</span>
                <div class="dropdown-content">
                    <a href="index.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a
                </div>
            </div>
        </div>
    </header>

    <main>
        <h2>Loans</h2>
        <!-- <button type="button" onclick="showAddCustomerForm()">+ New Loan Application</button> -->
        <div class="cards">
            <div class="table-container">
                <div class="table-wrapper">
                    <table id="customersTable" class="display">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Borrower</th>
                                <th>Loan Detail</th>
                                <th>Payment Detail</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
                               $sql = "SELECT loan.loan_id, loan.ref_no, loan.loan_type, loan.amount, loan.total_plan, loan.monthly_payment, loan.overdue_penalty, loan.status, 
                                            customer.firstname, customer.middlename, customer.lastname, customer.contact_no, customer.specific_address, customer.barangay, customer.city, 
                                            loan_plan.lplan_month, loan_plan.lplan_interest, loan_plan.lplan_penalty
                                        FROM loan
                                        JOIN customer ON loan.customer_id = customer.customer_id
                                        JOIN loan_plan ON loan.lplan_id = loan_plan.lplan_id";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                        $full_address = $row["specific_address"] . ", Brgy." . $row["barangay"] . ", " . $row["city"];
                                        echo "<tr data-loan='" . json_encode($row) . "'>";
                                        echo "<td>" . $row["loan_id"] . "</td>";
                                        echo "<td>Name: " . $row["firstname"] . " " . $row["middlename"] . " " . $row["lastname"] . "<br>Contact: " . $row["contact_no"] . "<br>Address: " . $full_address . "</td>";
                                        echo "<td>Reference no: " . $row["ref_no"] . "<br>Loan Plan: " . "(" . $row["lplan_month"] . " months, " . $row["lplan_interest"] . "%, " . $row["lplan_penalty"] . "%)" ."<br>Loan Type: " . $row["loan_type"] . "<br>Amount: ₱" . $row["amount"] . "<br>Total Payable Amount: ₱" . $row["total_plan"] . "<br>Monthly Payable Amount: ₱" . $row["monthly_payment"] . "<br>Overdue Payable Amount: ₱" . $row["overdue_penalty"] . "</td>";
                                        echo "<td></td>"; // Placeholder for Payment Detail
                                        echo "<td>" . (isset($row["status"]) ? $row["status"] : "") . "</td>";
                                        echo '<td><div class="btns">
                                                <button class="edit-btn" data-loan-id="' . $row["loan_id"]. '" >Edit</button> 
                                                <button class="delete-btn" data-loan-id="' . $row["loan_id"] . '">Delete</button>
                                            </div></td>';
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6'>No loans found</td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Placeholder for customers content -->
        </div>
    </main>
</div>

<div id="addCustomerModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeAddCustomerForm()">&times;</span>
        <form id="loanForm" method="post" action="employee_loans.php">
            <input type="hidden" id="loan_id" name="loan_id">
            <div class="form-group">
                <label for="borrower">Borrower</label>
                <select id="borrower" name="borrower" required>
                    <option value="">Select an option</option>
                    <?php foreach ($customers as $customer): ?>
                        <option value="<?php echo htmlspecialchars($customer['customer_id']); ?>">
                            <?php echo htmlspecialchars($customer['firstname'] . ' ' . $customer['middlename'] . ' ' . $customer['lastname']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="total">Total (+Loan Plan)</label>
                <input type="text" id="total" name="total" readonly>
            </div>
            <div class="form-group">
                <label for="loanType">Loan Type</label>
                <select id="loanType" name="loanType" required>
                    <option value="">Select an option</option>
                    <option value="PVAO">PVAO</option>
                    <option value="SSS">SSS</option>
                    <option value="GSIS">GSIS</option>
                </select>
            </div>
            <div class="form-group">
                <label for="monthlyPayment">Monthly Payment</label>
                <input type="text" id="monthlyPayment" name="monthlyPayment" readonly>
            </div>
            <div class="form-group">
                <label for="loanPlan">Loan Plan</label>
                <select id="loanPlan" name="loanPlan" required onchange="updateTotalAndMonthlyPayment()">
                    <option value="">Select an option</option>
                    <?php 
                    $sql = "SELECT * FROM loan_plan";
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . $row['lplan_id'] . "' data-interest='" . $row['lplan_interest'] . "' data-penalty='" . $row['lplan_penalty'] . "' data-months='" . $row['lplan_month'] . "'>" . $row['lplan_month'] . " months - Interest: " . $row['lplan_interest'] . "%, Penalty: " . $row['lplan_penalty'] . "%</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="overduePenalty">Overdue Penalty</label>
                <input type="text" id="overduePenalty" name="overduePenalty" readonly>
            </div>
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" id="amount" name="amount" required oninput="updateTotalAndMonthlyPayment()">
            </div>
            <div class="form-group">
                <label for="purpose">Purpose</label>
                <textarea id="purpose" name="purpose" required></textarea>
            </div>
            <div class="form-group button-group" style="justify-content: center;">
                <button type="submit" class="save-changes">Save Changes</button>
            </div>
        </form>
    </div>
</div>


<!-- DataTables JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<!-- Your custom script -->
<script src="scripts.js"></script>
<script>
    $(document).ready(function() {
        $('#customersTable').DataTable();
    });

    function showAddCustomerForm() {
        document.getElementById('addCustomerModal').style.display = 'block';
    }

    function closeAddCustomerForm() {
        document.getElementById('addCustomerModal').style.display = 'none';
        document.getElementById('loanForm').reset();
    }

    function updateTotalAndMonthlyPayment() {
        const amount = parseFloat(document.getElementById('amount').value);
        const loanPlanSelect = document.getElementById('loanPlan');
        const selectedOption = loanPlanSelect.options[loanPlanSelect.selectedIndex];

        const interestRate = parseFloat(selectedOption.getAttribute('data-interest')) || 0;
        const penaltyRate = parseFloat(selectedOption.getAttribute('data-penalty')) || 0;
        const months = parseInt(selectedOption.getAttribute('data-months')) || 0;

        if (amount > 0 && interestRate > 0 && months > 0) {
            const total = amount + (amount * (interestRate / 100));
            const monthlyPayment = total / months;
            const overduePenalty = total * (penaltyRate / 100);

            document.getElementById('total').value = total.toFixed(2);
            document.getElementById('monthlyPayment').value = monthlyPayment.toFixed(2);
            document.getElementById('overduePenalty').value = overduePenalty.toFixed(2);
        } else {
            document.getElementById('total').value = '';
            document.getElementById('monthlyPayment').value = '';
            document.getElementById('overduePenalty').value = '';
        }
    }

    // Edit button click handler
    $(document).on('click', '.edit-btn', function() {
        var row = $(this).closest('tr');
        var loan = JSON.parse(row.attr('data-loan'));

        $('#loan_id').val(loan.loan_id);
        $('#borrower').val(loan.customer_id);
        $('#total').val(loan.total_plan);
        $('#loanType').val(loan.loan_type);
        $('#monthlyPayment').val(loan.monthly_payment);
        $('#loanPlan').val(loan.lplan_id);
        $('#overduePenalty').val(loan.overdue_penalty);
        $('#amount').val(loan.amount);
        $('#purpose').val(loan.purpose);

        showAddCustomerForm();
        reset();
        location.reload();
    });

    document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function() {
        const loanId = this.getAttribute('data-loan-id');
        
        if (confirm('Are you sure you want to delete this loan?')) {
            $.ajax({
                url: 'employee_loans.php',
                type: 'POST',
                data: { delete_loan_id: loanId },
                success: function(response) {
                    alert('Loan deleted successfully.');
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    alert('Error deleting loan: ' + error);
                }
            });
        }
    });
});

    function showNotifications() {
        alert("You have notifications!");
    }


    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const loanId = this.getAttribute('data-loan-id');
            handleDelete(loanId);
        });
    });

    <?php if (!empty($message)) : ?>
        window.onload = function() {
        showAlert('<?php echo $message; ?>');
        };
    <?php endif; ?>

</script>
</body>
</html>

