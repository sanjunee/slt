<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SLT Feedback Analysis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            font-family: 'Arial', sans-serif;
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
        }
        h1 {
            color: #007bff;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .filter-section {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .analysis-card {
            background: #ffffff;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .analysis-card:hover {
            transform: translateY(-5px);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .chart-container {
            margin-top: 30px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .btn-download {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .btn-download:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <!-- SLT Logo in the Upper Right Corner -->
    <div class="container-fluid position-absolute top-0 end-0 p-3">
        <img src="slt.png" alt="SLT Logo" class="img-fluid" style="max-width: 100px;">
    </div>

    <!-- Analysis Container -->
    <div class="container glass-effect mt-5">
        <h1>SLT Feedback Analysis</h1>

        <!-- Filter Section -->
        <div class="filter-section">
            <h3>Filters</h3>
            <div class="row">
                <div class="col-md-3">
                    <label for="filter-name" class="form-label">Name</label>
                    <input type="text" id="filter-name" class="form-control" placeholder="Search by name">
                </div>
                <div class="col-md-3">
                    <label for="filter-usage" class="form-label">Uses SLT Products</label>
                    <select id="filter-usage" class="form-select">
                        <option value="">All</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filter-connection" class="form-label">Interested in SLT Connection</label>
                    <select id="filter-connection" class="form-select">
                        <option value="">All</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary mt-4" onclick="applyFilters()">Apply Filters</button>
                    <button class="btn btn-download mt-4" onclick="downloadPDF()">Download PDF</button>
                    <button class="btn btn-download mt-4" onclick="downloadExcel()">Download Excel</button>
                </div>
            </div>
        </div>

        <!-- Feedback Table -->
        <div id="feedback-table">
            <?php
            // Database configuration
            $host = 'localhost';
            $dbname = 'slt_feedback_db';
            $username = 'root';
            $password = '';

            try {
                // Connect to the database
                $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Fetch all feedback entries
                $stmt = $conn->query("SELECT * FROM feedback ORDER BY submission_datetime DESC");
                $feedbackEntries = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($feedbackEntries) {
                    // Display all feedback entries in a table
                    echo '<table>';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Name</th>';
                    echo '<th>Address</th>';
                    echo '<th>Contact</th>';
                    echo '<th>Uses SLT Products</th>';
                    echo '<th>Products Used</th>';
                    echo '<th>Other Products</th>';
                    echo '<th>Interested in SLT Connection</th>';
                    echo '<th>Products Interested In</th>';
                    echo '<th>Submission Date</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';

                    foreach ($feedbackEntries as $feedback) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($feedback['name']) . '</td>';
                        echo '<td>' . htmlspecialchars($feedback['address']) . '</td>';
                        echo '<td>' . htmlspecialchars($feedback['contact']) . '</td>';
                        echo '<td>' . htmlspecialchars($feedback['slt_usage']) . '</td>';
                        echo '<td>' . htmlspecialchars($feedback['products']) . '</td>';
                        echo '<td>' . htmlspecialchars($feedback['other_products']) . '</td>';
                        echo '<td>' . htmlspecialchars($feedback['connection']) . '</td>';
                        echo '<td>' . htmlspecialchars($feedback['connection_products']) . '</td>';
                        echo '<td>' . htmlspecialchars($feedback['submission_datetime']) . '</td>';
                        echo '</tr>';
                    }

                    echo '</tbody>';
                    echo '</table>';
                } else {
                    echo '<div class="alert alert-warning">No feedback data found in the database.</div>';
                }
            } catch (PDOException $e) {
                echo '<div class="alert alert-danger">Database error: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
            ?>
        </div>

        <!-- Chart Section -->
        <div class="chart-container">
            <h3>Feedback Analysis Chart</h3>
            <canvas id="feedbackChart"></canvas>
        </div>
    </div>

    <script>
        // Sample Chart Data (Replace with actual data from PHP)
        const ctx = document.getElementById('feedbackChart').getContext('2d');
        const feedbackChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Uses SLT Products', 'Interested in SLT Connection'],
                datasets: [{
                    label: 'Feedback Count',
                    data: [12, 19], // Replace with actual data
                    backgroundColor: ['#007bff', '#28a745'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Feedback Analysis' }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Count' }
                    },
                    x: {
                        title: { display: true, text: 'Categories' }
                    }
                }
            }
        });

        // Filter Functionality
        function applyFilters() {
            const nameFilter = document.getElementById('filter-name').value.toLowerCase();
            const usageFilter = document.getElementById('filter-usage').value;
            const connectionFilter = document.getElementById('filter-connection').value;

            const rows = document.querySelectorAll('#feedback-table tbody tr');
            rows.forEach(row => {
                const name = row.cells[0].textContent.toLowerCase();
                const usage = row.cells[3].textContent;
                const connection = row.cells[6].textContent;

                const nameMatch = name.includes(nameFilter);
                const usageMatch = usageFilter === '' || usage === usageFilter;
                const connectionMatch = connectionFilter === '' || connection === connectionFilter;

                if (nameMatch && usageMatch && connectionMatch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Download as PDF
        function downloadPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            const table = document.getElementById('feedback-table');
            doc.text('SLT Feedback Data', 10, 10);
            doc.autoTable({ html: table });
            doc.save('slt_feedback_data.pdf');
        }

        // Download as Excel
        function downloadExcel() {
            const table = document.getElementById('feedback-table');
            const workbook = XLSX.utils.table_to_book(table);
            XLSX.writeFile(workbook, 'slt_feedback_data.xlsx');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>