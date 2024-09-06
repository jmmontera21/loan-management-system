<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission
    $loanId = $_POST['loan_id'];
    $borrower = $_POST['borrower'];
    $loanType = $_POST['loanType'];
    $loanPlan = $_POST['loanPlan'];
    $amount = $_POST['amount'];
    $purpose = $_POST['purpose'];
    $total = $_POST['total'];
    $monthlyPayment = $_POST['monthlyPayment'];
    $overduePenalty = $_POST['overduePenalty'];

    $sql = "UPDATE loan SET
                loan_type='$loanType',
                customer_id='$borrower',
                purpose='$purpose',
                amount='$amount',
                lplan_id='$loanPlan',
                total_plan='$total',
                monthly_payment='$monthlyPayment',
                overdue_penalty='$overduePenalty'
            WHERE loan_id='$loanId'";

    if (mysqli_query($conn, $sql)) {
        header('Location: employee_loans.php');
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // Fetch loan details for editing
    $loanId = $_GET['id'];
    $sql = "SELECT * FROM loan WHERE loan_id='$loanId'";
    $result = mysqli_query($conn, $sql);
    $loan = mysqli_fetch_assoc($result);

    // Fetch customers and loan plans
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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/employee.css">
    <title>Edit Loan</title>
    <style>
        body {
            align-items: center;
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>
    <form method="post" action="">
        <input type="hidden" name="loan_id" value="<?php echo htmlspecialchars($loan['loan_id']); ?>">
        <!-- Include other form fields similar to the add loan form, pre-filled with current loan details -->
        <div class="form-group">
            <label for="borrower">Borrower</label>
            <select id="borrower" name="borrower" required>
                <option value="">Select an option</option>
                <?php foreach ($customers as $customer): ?>
                    <option value="<?php echo htmlspecialchars($customer['customer_id']); ?>" <?php if ($customer['customer_id'] == $loan['customer_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($customer['firstname'] . ' ' . $customer['middlename'] . ' ' . $customer['lastname']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="total">Total (+Loan Plan)</label>
            <input type="text" id="total" name="total" readonly>    </div>
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
                        echo "<option value='" . $row['lplan_id'] . "' data-interest='" . $row['lplan_interest'] . "' data-penalty='" . $row['lplan_penalty'] . "' data-months='" . $row['lplan_month'] . "'>" . $row['lplan_month'] . " months</option>";
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
        <div class="form-group button-group2" style="justify-content: flex-end; align-items: flex-end;">
            <button type="submit" class="save-changes">Save Changes</button>
        </div>
</form>
<script>
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
</script>
</body>
</html>
