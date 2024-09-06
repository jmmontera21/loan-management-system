<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chart.js Example</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <canvas id="myChart2" width="400" height="200"></canvas>

    <script>
        // Fetch data from the PHP script
        fetch('path_to_your_php_script.php')
            .then(response => response.json())
            .then(data => {
                // Process the data
                const loanStatuses = Object.keys(data);
                const statusCounts = Object.values(data);

                // Create the Chart.js chart
                let myChart2 = document.getElementById('myChart2').getContext('2d');
                let massPopChart2 = new Chart(myChart2, {
                    type: 'bar',
                    data: {
                        labels: loanStatuses,
                        datasets: [{
                            label: 'Loan Applications',
                            data: statusCounts,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.6)',
                                'rgba(54, 162, 235, 0.6)',
                                'rgba(255, 206, 86, 0.6)',
                                'rgba(75, 192, 192, 0.6)',
                                'rgba(153, 102, 255, 0.6)',
                                'rgba(255, 159, 64, 0.6)'
                            ]
                        }]
                    },
                    options: {
                        // Add your options here, if needed
                    }
                });
            });
    </script>
</body>
</html>
