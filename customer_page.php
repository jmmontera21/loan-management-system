<?php
include 'db_connect.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Page</title>
    <link rel="stylesheet" href="css/customer_page.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .container::-webkit-scrollbar {
            display: none; /* Hide scrollbar for Chrome, Safari and Opera */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="profile">
                <div class="pic"></div>
                <div class="profile-info">
                    <h2>Juan Dela Cruz</h2>
                    <p>juandelacruz@email.com</p>
                </div>
            </div>
            <div class="active-loan">
                <div class="active-container">
                    <h2 class="loan-title">Active Loan</h2>
                    <div class="loan">
                        <p>SSS Pension Loan</p>
                        <p class="loan-amount">₱4,000 /Month <br> (24 Months)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-wrapper" style="margin-top:18%">
        <div class="container">
            <div class="content">
                <div class="customer-loan">
                    <div class="header2">
                        <h3>My Loans</h3>
                    </div>
                    <table>
                        <tr>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                        <tr>
                            <td>SSS Pension Loan</td>
                            <td>April 2024</td>
                            <td class="ongoing">Ongoing</td>
                        </tr>
                        <tr >
                            <td>SSS Pension Loan</td>
                            <td>January 2021</td>
                            <td class="paid">Paid</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="content">
                <div class="customer-loan">
                    <div class="header2">
                        <h3>Transactions</h3>
                    </div>
                    <table>
                        <tr>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Amount</th>
                        </tr>
                        <tr>
                            <td>SSS Pension Loan</td>
                            <td>May 2024</td>
                            <td>₱4,000</td>
                        </tr>
                        <tr >
                            <td>SSS Pension Loan</td>
                            <td>April 2024</td>
                            <td>₱4,000</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="content">
                <div class="customer-loan">
                    <div class="header2">
                        <h3>Notifications</h3>
                    </div>
                    <table>
                        <tr>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Details</th>
                        </tr>
                        <tr class="notification" onclick="showNotification('Payment', 'Your due payment for the date May 2024 has been paid.')">
                            <td class="notification-type">Payment</td>
                            <td>05/15/2024</td>
                            <td>Your due payment for the date May 2024 has been paid.</td>
                        </tr>
                        <tr class="notification" onclick="showNotification('Payment', 'Your due payment for the date April 2024 has been paid.')">
                            <td class="notification-type">Payment</td>
                            <td>04/15/2024</td>
                            <td>Your due payment for the date April 2024 has been paid.</td>
                        </tr>
                        <tr class="notification" onclick="showNotification('Update', 'Your SSS Pension Loan has been approved.')">
                            <td class="notification-type">Update</td>
                            <td>04/15/2024</td>
                            <td>Your SSS Pension Loan has been approved</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="scripts.js"></script>
</body>
</html>
