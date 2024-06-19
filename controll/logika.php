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
    <link rel="stylesheet" href="../css/logika.css">
</head>
<body>

<div class="container">
    <button class="update-btn" onclick="document.getElementById('updateModal').style.display='block'">Update</button>
    <h2>Welcome in investment calculator <?= htmlspecialchars($_SESSION['investUser']['full_name']) ?></h2>
    <p>Füllen Sie das Formular aus, um den endgültigen Investitionsbetrag zu berechnen.</p>

    <div class="form-container">
        <?php
        // Funktion zur Berechnung des Zinseszinses
        function calculateCompoundInterest($principal, $annualInterestRate, $years, $compoundFrequency, $additionalInvestment, $additionalFrequency)
        {
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

            // Berechnung des Endgultigen Investitionsbetrags
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
                <input type="number" id="principal" name="principal" min="0" required>
            </div>
            <div class="form-group">
                <label for="years">Anlagezeitraum (Jahre):</label>
                <input type="number" id="years" name="years" min="0" required>
            </div>
            <div class="form-group">
                <label for="annualInterestRate">Jahreszinssatz (%):</label>
                <input type="number" step="0.01" min="0.00" id="annualInterestRate" name="annualInterestRate" required>
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
                <input type="number" id="additionalInvestment" name="additionalInvestment" min="0">
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
                    let ctx = document.getElementById('resultChart').getContext('2d');
                    let resultChart = new Chart(ctx, {
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
                            plugins: {
                                legend: {
                                    labels: {
                                        color: '#110101'
                                    }
                                }
                            }
                        }
                    });
                </script>
        <?php endif; ?>

        <a href="../scripts/logOut.php" class="logout">Logout</a>
    </div>
</div>

<!-- The Modal -->
<div id="updateModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('updateModal').style.display='none'">&times;</span>
        <h2>Update Profile</h2>
        <form action="../scripts/updateProfile.php" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username"
                       value="<?= isset($_SESSION['investUser']['username']) ? htmlspecialchars($_SESSION['investUser']['username']) : '' ?>"
                       required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email"
                       value="<?= isset($_SESSION['investUser']['email']) ? htmlspecialchars($_SESSION['investUser']['email']) : '' ?>"
                       required>
            </div>
            <div class="form-group">
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password">
            </div>
            <button type="submit" class="submit-btn">Update</button>
        </form>
    </div>
</div>

<script>
    let modal = document.getElementById('updateModal');
    window.onclick = function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }
</script>

</body>
</html>
