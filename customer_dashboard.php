<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="logo">
                <img src="img/LMS logo.png" alt="Logo">
            </div>
            <nav>
                <ul>
                    <li><a href="customer_dashboard.php">Home</a></li>
                    <li><a href="customer_calculator.php">Calculator</a></li>
                    <li><a href="#">Apply Loan</a></li>
                    <li><a href="#">Loan History</a></li>
                    <li><a href="#">Settings</a></li>
                    <li><a href="homepage.php">Log Out</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <header class="profile-header">
                <div class="profile-pic">
                    <img src="img/homepage1.jpg" alt="Profile Picture">
                </div>
                <div class="profile-name">
                    <h2>Juan Dela Cruz</h2>
                </div>
            </header>

            <section class="content">
                <div class="inbox">
                    <h3>Inbox</h3>
                </div>
                <div class="loan-details">
                    <h3>Current Loan</h3>
                    <p>Type: <span style="text-align:right">SSS</span></p>
                    <p>Amount: <span>35,000</span></p>
                    <p>Term: <span>24 Months</span></p>
                    <p>Date Released: <span>08/13/2024</span></p>
                    <p>Amount to Pay: <span class="amount-to-pay">1,532</span></p>
                    <p>Next Payment: <span class="next-payment">10/14/2024</span></p>
                    <p>Remaining Balance: <span class="remaining-balance">35,236</span></p>
                </div>
            </section>
        </main>
    </div>
</body>
</html>


<!-- After submitting all the necessary information, the customer should see a 
pdf-file format filled with all the information he/she inputted in the application form. -->
