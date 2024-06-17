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
    <title>Logika</title>
    <link rel="stylesheet" href="../style/main.css">
</head>
<body>

<?php
// Funktion zur Berechnung des Zinseszinses
function calculateCompoundInterest($principal, $annualInterestRate, $years, $compoundFrequency) {
    $periodicInterestRate = $annualInterestRate / $compoundFrequency;
    $numberOfPeriods = $years * $compoundFrequency;
    $finalAmount = $principal * pow((1 + $periodicInterestRate), $numberOfPeriods);
    return $finalAmount;
}

// Überprüfen, ob das Formular übermittelt wurde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sammeln von Daten aus dem Formular
    $principal = isset($_POST['principal']) ? floatval($_POST['principal']) : 0;
    $annualInterestRate = isset($_POST['annualInterestRate']) ? floatval($_POST['annualInterestRate']) / 100 : 0; // Преобразуем в десятичную форму
    $years = isset($_POST['years']) ? intval($_POST['years']) : 0;
    $compoundFrequency = isset($_POST['compoundFrequency']) ? intval($_POST['compoundFrequency']) : 0;

    // Berechnung des Endgültigen Investitionsbetrags
    $finalAmount = calculateCompoundInterest($principal, $annualInterestRate, $years, $compoundFrequency);
    $result = "Endgültiger Investitionsbetrag: " . number_format($finalAmount, 2) . " EUR";
} else {
    $result = "Füllen Sie das Formular aus, 
    um den endgültigen Investitionsbetrag zu berechnen.";
}
?>

<h2 style="margin: 10px 0;">Welcome in investment calculator <?= htmlspecialchars($_SESSION['investUser']['full_name']) ?></h2>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    Anfangsinvestitionsbetrag (EUR): <input type="number" step="0.01" name="principal" required><br>
    Jahreszinssatz (%): <input type="number" step="0.01" name="annualInterestRate" required><br>
    Anlagezeitraum (Jahre): <input type="number" name="years" required><br>
    Häufigkeit des Zinseszinses (einmal im Jahr): <select name="compoundFrequency">
        <option value="1">Jährlich</option>
        <option value="4">Quartalsweise</option>
        <option value="12">Monatlich</option>
    </select><br>
    <input type="submit" value="Berechnung">
    <a href="../scripts/logOut.php" class="logout">Log out</a>
</form>

<p><?php echo $result; ?></p>

</body>
</html>
