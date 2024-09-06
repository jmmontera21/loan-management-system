<?php date_default_timezone_set("Etc/GMT+8");?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Management System Dashboard</title>
    <link rel="stylesheet" href="css/employee.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
</head>
<body>
<div class="sidebar">
        <div class="sidebar-header" onclick="toggleSidebar()">
            <span class="role">PANEL</span>
            <span class="dropright-icon"><</span>
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
                <a href="employee_loans.php" class="nav-link">
                    <img src="img/loans.png" alt="Loans">
                    <span class="text">Loans</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="employee_payments.php" class="nav-link active">
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
            <h2>Payments</h2>
            <button type="submit" onclick="showAddCustomerForm()">+ New Payment</button>
            <div class="cards">
                <div class="table-container">
                    <div class="table-wrapper">
                        <table id="customersTable" class="display">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Loan Reference No.</th>
                                    <th>Payee</th>
                                    <th>Amount</th>
                                    <th>Penalty</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- Placeholder for customers content -->
            </div>
        </main>
    </div>
    
    <div id="addCustomerModal" class="modal">
        <div class="modal-content2">
            <span class="close" onclick="closeAddCustomerForm()">&times;</span>
            <form method="post" action="">
                <div class="form-group2">
                    <label for="borrower">Borrower</label>
                    <select id="borrower" name="borrower" required>
                        <option value="">Select an option</option>
                        <?php foreach ($data as $row): ?>
                            <option value="<?= htmlspecialchars($row['id'])?>"
                                <?= htmlspecialchars($row['username'])?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="form-group2">
                    <label for="payee">Payee</label>
                    <input type="text" id="payee" name="payee" required>
                </div>
                <div class="form-group2">
                    <label for="payableAmount">Payable Amount</label>
                    <input type="number" id="payableAmount" name="payableAmount" required>
                </div>
                <div class="form-group2">
                    <label for="monthlyPayment">Monthly Payment</label>
                    <input type="number" id="monthlyPayment" name="monthlyPayment" required>
                </div>
                <div class="form-group2">
                    <label for="amountPaid">Amount Paid</label>
                    <input type="number" id="amountPaid" name="amountPaid" required>
                </div>
                <div class="form-group2">
                    <label for="overduePenalty">Overdue Penalty</label>
                    <input type="number" id="overduePenalty" name="overduePenalty" required>
                </div>
                <div class="form-group2 button-group" style="justify-content: flex-end; align-items: flex-end;">
                    <button name="submit" class="save-changes2">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    <div id="alert" class="alert"></div>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="scripts.js"></script>
    <script>
        <?php if (!empty($message)) : ?>
            window.onload = function() {
            showAlert('<?php echo $message; ?>');
            };
        <?php endif; ?>
    </script>
</body>
</html>
