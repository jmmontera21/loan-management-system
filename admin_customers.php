<?php
include 'db_connect.php';
session_start();

$message = '';

// if (!isset($_SESSION['email'])) {
//     header("Location: index.php");
//     exit();
// }

if (isset($_POST['submit'])) {
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $contactNo = $_POST['contactNo'];
    $tinNo = $_POST['tinNo'];
    $city = $_POST['city'];
    $barangay = $_POST['barangay'];
    $address = $_POST['address'] ?? ''; // Handle optional address field
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $customerId = $_POST['customer_id'] ?? null;

    if ($customerId) {
        // Update existing customer
        $stmt = $conn->prepare("UPDATE customer SET firstname=?, middlename=?, lastname=?, `contact_no`=?, `TIN_no`=?, `city`=?, `barangay`=?, `specific_address`=?, email=?, `password`=? WHERE customer_ID=?");
        $stmt->bind_param("ssssssssssi", $firstname, $middlename, $lastname, $contactNo, $tinNo, $city, $barangay, $address, $email, $password, $customerId);
    } else {
        // Insert new customer
        $stmt = $conn->prepare("INSERT INTO customer (firstname, middlename, lastname, contact_no, TIN_no, city, barangay, specific_address, email, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssss", $firstname, $middlename, $lastname, $contactNo, $tinNo, $city, $barangay, $address, $email, $password);
    }

    if ($stmt->execute()) {
        $message = 'The information has been saved successfully';
    } else {
        $message = 'Error: ' . $conn->error;
    }
    $stmt->close();
}

if (isset($_POST['delete'])) {
    $customerId = $_POST['customer_id'];
    $stmt = $conn->prepare("DELETE FROM customer WHERE customer_ID=?");
    $stmt->bind_param("i", $customerId);

    if ($stmt->execute()) {
        $message = 'Customer has been deleted successfully';
    } else {
        $message = 'Error: ' . $conn->error;
    }
    $stmt->close();
}


$totalCustomersQuery = "SELECT COUNT(*) AS total FROM customer";
$totalCustomersResult = mysqli_query($conn, $totalCustomersQuery);
$totalCustomersRow = mysqli_fetch_assoc($totalCustomersResult);
$totalCustomers = $totalCustomersRow['total'];
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
        <span class="dropright-icon"><</span>
    </div>
    <ul class="nav-links">
        <li class="nav-item">
            <a href="admin_home.php" class="nav-link">
                <img src="img/dashboard.png" alt="Home">
                <span class="text">Home</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="admin_customers.php" class="nav-link active">
                <img src="img/customer.png" alt="Customers">
                <span class="text">Customers</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="admin_loans.php" class="nav-link">
                <img src="img/loans.png" alt="Loans">
                <span class="text">Loans</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="admin_payments.php" class="nav-link">
                <img src="img/credit-card.png" alt="Payments">
                <span class="text">Payments</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="admin_map.php" class="nav-link">
                <img src="img/map.png" alt="Map">
                <span class="text">Map</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="loan_plans.php" class="nav-link">
                <img src="img/plans.png" alt="Plans">
                <span class="text">Loan Plans</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="accounts.php" class="nav-link">
                <img src="img/accs.png" alt="Accounts">
                <span class="text">Accounts</span>
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
                <span class="username">Admin</span>
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
            <h2>Customers</h2>
            <button type="submit" onclick="showAddCustomerForm()">+ Add Customer</button>
            <div class="cards">
                <div class="table-container">
                        <table id="customersTable" class="display">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Contact No.</th>
                                    <th>Address</th>
                                    <th>Email</th>
                                    <th>TIN No.</th>
                                    <th>Password</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <?php
                            $sql = "SELECT * FROM customer";
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $full_name = htmlspecialchars($row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname']);
                                    $full_address = htmlspecialchars($row['specific_address'] . ' Brgy. ' . $row['barangay'] . ', ' . $row['city']);
                                    echo "<tr data-customer-id='" . $row['customer_id'] . "'>";
                                    echo "<td>" . $full_name . "</td>";
                                    echo "<td>" . htmlspecialchars($row['contact_no']) . "</td>";
                                    echo "<td>" . $full_address . "</td>";
                                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['TIN_no']) . "</td>";
                                    echo "<td> ******** </td>";
                                    echo '<td><div class="btns">
                                        <button class="edit-btn">Edit</button> 
                                        <button class="delete-btn">Delete</button>
                                        </td></div>';
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='9'>No customers found</td></tr>";
                            }
                            ?>
                        </table>
                </div>
                <!-- Placeholder for customers content -->
            </div>
        </main>
    </div>


<div id="addCustomerModal" class="modal">
    <div class="modal-content">
        <div style="color:green;">
            <span class="close" onclick="closeAddCustomerForm()">&times;</span>
        </div>
        <form method="post" action="">
            <input type="hidden" id="customer_id" name="customer_id">
            <div class="form-group">
                <label for="firstname">Firstname</label>
                <input type="text" id="firstname" name="firstname" required>
            </div>
            <div class="form-group">
                <label for="middlename">Middlename</label>
                <input type="text" id="middlename" name="middlename" required>
            </div>
            <div class="form-group">
                <label for="lastname">Lastname</label>
                <input type="text" id="lastname" name="lastname" required>
            </div>
            <div class="form-group">
                <label for="contactNo">Contact No.</label>
                <input type="number" id="contactNo" name="contactNo" placeholder="e.g. 0908 546 1234" required>
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <select id="city" name="city" required onchange="updateBarangays()">
                    <option value="">Select an option</option>
                    <option value="Agdangan">Agdangan</option>
                    <option value="Alabat">Alabat</option>
                    <option value="Atimonan">Atimonan</option>
                    <option value="Buenavista">Buenavista</option>
                    <option value="Burdeos">Burdeos</option>
                    <option value="Calauag">Calauag</option>
                    <option value="Candelaria">Candelaria</option>
                    <option value="Catanauan">Catanauan</option>
                    <option value="Dolores">Dolores</option>
                    <option value="General Luna">General Luna</option>
                    <option value="General Nakar">General Nakar</option>
                    <option value="Guinayangan">Guinayangan</option>
                    <option value="Gumaca">Gumaca</option>
                    <option value="Infanta">Infanta</option>
                    <option value="Jomalig">Jomalig</option>
                    <option value="Lopez">Lopez</option>
                    <option value="Lucban">Lucban</option>
                    <option value="Lucena City">Lucena City</option>
                    <option value="Macalelon">Macalelon</option>
                    <option value="Mauban">Mauban</option>
                    <option value="Mulanay">Mulanay</option>
                    <option value="Padre Burgos">Padre Burgos</option>
                    <option value="Pagbilao">Pagbilao</option>
                    <option value="Panukulan">Panukulan</option>
                    <option value="Patnanungan">Patnanungan</option>
                    <option value="Perez">Perez</option>
                    <option value="Pitogo">Pitogo</option>
                    <option value="Plaridel">Plaridel</option>
                    <option value="Polillo">Polillo</option>
                    <option value="Quezon">Quezon</option>
                    <option value="Real">Real</option>
                    <option value="Sampaloc">Sampaloc</option>
                    <option value="San Andres">San Andres</option>
                    <option value="San Antonio">San Antonio</option>
                    <option value="San Francisco">San Francisco</option>
                    <option value="San Narciso">San Narciso</option>
                    <option value="Sariaya">Sariaya</option>
                    <option value="Tagkawayan">Tagkawayan</option>
                    <option value="Tayabas City">Tayabas City</option>
                    <option value="Tiaong">Tiaong</option>
                    <option value="Unisan">Unisan</option>
                </select>
            </div>
            <div class="form-group">
                <label for="tinNo">TIN No.</label>
                <input type="number" id="tinNo" name="tinNo" placeholder="000-000-000" required>
            </div>
            <div class="form-group">
                <label for="barangay">Barangay</label>
                <select id="barangay" name="barangay" required>
                    <option value="">Select an option</option>
                </select>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="someone@mail.com" required>
            </div>
            <div class="form-group">
                <label for="address">Specific Address</label>
                <input type="text" id="address" name="address" placeholder="143 Rodriquez St." required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group image-upload">
                <div class="upload-wrapper">
                    <input type="file" id="image" name="image" onchange="previewImage(event)">
                    <label for="image">Choose File</label>
                </div>
                <div class="image-placeholder" id="imagePreview">Image</div>
            </div>
            <div class="form-group button-group">
                <button name="submit" class="save-changes">Save Changes</button>
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
// Edit button click event
$('.edit-btn').click(function() {
    var row = $(this).closest('tr');
    var customerId = row.data('customer-id');
    var firstname = row.find('td:nth-child(1)').text().split(' ')[0];
    var middlename = row.find('td:nth-child(1)').text().split(' ')[1];
    var lastname = row.find('td:nth-child(1)').text().split(' ')[2];
    var contactNo = row.find('td:nth-child(2)').text();
    var city = row.find('td:nth-child(3)').text().split(', ')[1];
    var barangay = row.find('td:nth-child(3)').text().split(' Brgy. ')[1].split(', ')[2];
    var specific_address = row.find('td:nth-child(3)').text().split(' Brgy. ')[0];
    var email = row.find('td:nth-child(4)').text();
    var tinNo = row.find('td:nth-child(5)').text();
    var password = row.find('td:nth-child(6)').text();

    $('#customer_id').val(customerId);
    $('#firstname').val(firstname);
    $('#middlename').val(middlename);
    $('#lastname').val(lastname);
    $('#contactNo').val(contactNo);
    $('#address').val(specific_address);
    $('#barangay').val(barangay);
    $('#city').val(city);
    $('#email').val(email);
    $('#tinNo').val(tinNo);
    $('#password').val(password);

    $('#addCustomerModal').show();
});

// Delete button click event
$('.delete-btn').click(function() {
    if (confirm('Are you sure you want to delete this customer?')) {
        var row = $(this).closest('tr');
        var customerId = row.data('customer-id');
        $('<form method="post" action=""><input type="hidden" name="customer_id" value="' + customerId + '"><input type="hidden" name="delete" value="1"></form>').appendTo('body').submit();
    }
});

// Function to clear form fields and hide modal
function clearFormAndHideModal() {
    $('form')[0].reset();
    $('#addCustomerModal').hide();
}

// Close modal button click event
$('.close').click(function() {
    clearFormAndHideModal();
});

// // Reset form on modal close
// $('#addCustomerModal').on('hidden.bs.modal', function() {
//     clearFormAndHideModal();
// });

$('form').on('submit', function() {
    setTimeout(function() {
        clearFormAndHideModal();
    }, 1000); // Delay to ensure the form submission happens first
});

<?php if (!empty($message)) : ?>
    window.onload = function() {
    showAlert('<?php echo $message; ?>');
    };
<?php endif; ?>
</script>
</body>
</html>
