<?php
session_start();

if (!isset($_SESSION['investUser'])) {
    header('Location: ../index.php');
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logik</title>
    <link rel="stylesheet" href="../style/main.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            margin-top: 2cm; /* отступ сверху */
            margin-bottom: 2cm; /* отступ снизу */
        }
        h2 {
            margin-top: 0;
        }
        .form-container {
            margin-top: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select {
            width: calc(100% - 22px);
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group .currency, .form-group .unit {
            display: inline-block;
            width: 20px;
            text-align: right;
        }
        .form-group .inline-input {
            display: inline-block;
            width: calc(50% - 10px);
        }
        .form-group .inline-input + .inline-input {
            margin-left: 20px;
        }
        .submit-btn {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background-color: #0056b3;
        }
        .logout {
            display: block;
            margin-top: 20px;
            text-decoration: none;
            color: #000;
        }
        .result-container {
            margin-top: 20px;
        }
        .result-container p {
            margin: 5px 0;
        }
        .chart-container {
            margin-top: 20px;
        }
        .chart {
            width: 100%;
            height: 400px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Welcome in investment calculator <?= htmlspecialchars($_SESSION['investUser']['full_name']) ?></h2>
    <p>Füllen Sie das Formular aus, um den endgültigen Investitionsbetrag zu berechnen.</p>

    <div class="form-container">
        <?php
        // Funktion zur Berechnung des Zinseszinses
        function calculateCompoundInterest($principal, $annualInterestRate, $years, $compoundFrequency, $additionalInvestment, $additionalFrequency) {
            $totalPeriods = $years * $compoundFrequency;
            $totalAdditionalPeriods = $years * $additionalFrequency;
            $ratePerPeriod = $annualInterestRate / $compoundFrequency;
            $futureValue = $principal * pow((1 + $ratePerPeriod), $totalPeriods);
            for ($i = 1; $i <= $totalAdditionalPeriods; $i++) {
                $futureValue += $additionalInvestment * pow((1 + $ratePerPeriod), $totalPeriods - ($i * ($compoundFrequency / $additionalFrequency)));
            }
            return $futureValue;
        }

        // Überprüfen, ob das Formular übermittelt wurde
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Sammeln von Daten aus dem Formular
            $principal = isset($_POST['principal']) ? floatval(str_replace(',', '', $_POST['principal'])) : 0;
            $annualInterestRate = isset($_POST['annualInterestRate']) ? floatval($_POST['annualInterestRate']) / 100 : 0;
            $years = isset($_POST['years']) ? intval($_POST['years']) : 0;
            $compoundFrequency = isset($_POST['compoundFrequency']) ? intval($_POST['compoundFrequency']) : 0;
            $additionalInvestment = isset($_POST['additionalInvestment']) ? floatval(str_replace(',', '', $_POST['additionalInvestment'])) : 0;
            $additionalFrequency = isset($_POST['additionalFrequency']) ? intval($_POST['additionalFrequency']) : 0;

            // Berechnung des Endgültigen Investitionsbetrags
            $finalAmount = calculateCompoundInterest($principal, $annualInterestRate, $years, $compoundFrequency, $additionalInvestment, $additionalFrequency);
            $totalAdditionalInvestments = $additionalInvestment * $additionalFrequency * $years;
            $totalInvested = $principal + $totalAdditionalInvestments;
            $income = $finalAmount - $totalInvested;

            $result = [
                "finalAmount" => number_format($finalAmount, 2, ',', ' ') . ' €',
                "totalInvested" => number_format($totalInvested, 2, ',', ' ') . ' €',
                "income" => number_format($income, 2, ',', ' ') . ' €'
            ];
        } else {
            $result = [];
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="principal">Startkapital (EUR):</label>
                <input type="text" id="principal" name="principal" required>
            </div>
            <div class="form-group">
                <label for="years">Anlagezeitraum (Jahre):</label>
                <input type="number" id="years" name="years" required>
            </div>
            <div class="form-group">
                <label for="annualInterestRate">Jahreszinssatz (%):</label>
                <input type="number" step="0.01" id="annualInterestRate" name="annualInterestRate" required>
            </div>
            <div class="form-group">
                <label for="compoundFrequency">Häufigkeit des Zinseszinses:</label>
                <select id="compoundFrequency" name="compoundFrequency" required>
                    <option value="1">Jährlich</option>
                    <option value="4">Quartalsweise</option>
                    <option value="12">Monatlich</option>
                </select>
            </div>
            <div class="form-group">
                <label for="additionalInvestment">Zusätzliche Investitionen (EUR):</label>
                <input type="text" id="additionalInvestment" name="additionalInvestment">
            </div>
            <div class="form-group">
                <label for="additionalFrequency">Häufigkeit der zusätzlichen Investitionen:</label>
                <select id="additionalFrequency" name="additionalFrequency">
                    <option value="1">Jährlich</option>
                    <option value="4">Quartalsweise</option>
                    <option value="12">Monatlich</option>
                </select>
            </div>
            <button type="submit" class="submit-btn">Berechnung</button>
        </form>

        <?php if ($result): ?>
            <div class="result-container">
                <p>Gesamtinvestition: <?= $result['totalInvested'] ?></p>
                <p>Einkommen: <?= $result['income'] ?></p>
                <p>Endgültiger Betrag: <?= $result['finalAmount'] ?></p>
                <div class="chart-container">
                    <canvas id="resultChart" class="chart"></canvas>
                </div>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                var ctx = document.getElementById('resultChart').getContext('2d');
                var resultChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ['Anfangskapital', 'Einkommen', 'Zusätzliche Investitionen'],
                        datasets: [{
                            data: [<?= $principal ?>, <?= $income ?>, <?= $totalAdditionalInvestments ?>],
                            backgroundColor: ['#007bff', '#28a745', '#ffc107'],
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                    }
                });
            </script>
        <?php endif; ?>

        <a href="../scripts/logOut.php" class="logout">Log out</a>
    </div>
</div>

</body>
</html>
