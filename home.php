<?php
include 'connection.php';

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

$username = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tradeDescription = isset($_POST['tradeDescription']) ? mysqli_real_escape_string($conn, $_POST['tradeDescription']) : null;
    $profit = isset($_POST['profit']) ? $_POST['profit'] : null;

    if ($tradeDescription !== null) {
        $insertTradeQuery = "INSERT INTO trades (username, tradeDescription, profit, timestamp) VALUES (?, ?, ?, CURRENT_TIMESTAMP)";

        $stmt = mysqli_prepare($conn, $insertTradeQuery);
        mysqli_stmt_bind_param($stmt, "ssd", $username, $tradeDescription, $profit);
        mysqli_stmt_execute($stmt);

        header("Location: home.php");
        exit();
    } else {
    }
}

$currentDate = date('Y-m-d');
$getTotalTradesQuery = "SELECT COUNT(*) as totalTrades FROM trades WHERE username='$username' AND DATE(timestamp) = '$currentDate'";
$resultTotalTrades = mysqli_query($conn, $getTotalTradesQuery);
$rowTotalTrades = mysqli_fetch_assoc($resultTotalTrades);
$totalTrades = $rowTotalTrades['totalTrades'];

$getWonTradesQuery = "SELECT COUNT(*) as wonTrades FROM trades WHERE username='$username' AND tradeDescription='won' AND DATE(timestamp) = '$currentDate'";
$resultWonTrades = mysqli_query($conn, $getWonTradesQuery);
$rowWonTrades = mysqli_fetch_assoc($resultWonTrades);
$wonTrades = $rowWonTrades['wonTrades'];

$getLostTradesQuery = "SELECT COUNT(*) as lostTrades FROM trades WHERE username='$username' AND tradeDescription='lost' AND DATE(timestamp) = '$currentDate'";
$resultLostTrades = mysqli_query($conn, $getLostTradesQuery);
$rowLostTrades = mysqli_fetch_assoc($resultLostTrades);
$lostTrades = $rowLostTrades['lostTrades'];

$getTotalProfitsQuery = "SELECT SUM(profit) AS totalProfits FROM trades WHERE username='$username' AND DATE(timestamp) = '$currentDate'";
$resultTotalProfits = mysqli_query($conn, $getTotalProfitsQuery);
$rowTotalProfits = mysqli_fetch_assoc($resultTotalProfits);
$totalProfits = $rowTotalProfits['totalProfits'];

$getWonProfitsQuery = "SELECT SUM(profit) AS wonProfits FROM trades WHERE username='$username' AND tradeDescription='won' AND DATE(timestamp) = '$currentDate'";
$resultWonProfits = mysqli_query($conn, $getWonProfitsQuery);
$rowWonProfits = mysqli_fetch_assoc($resultWonProfits);
$wonProfits = $rowWonProfits['wonProfits'];

$filterDateFormatted = date('Y-m-d');
$resultFilteredTrades = null;
$filteredTotalTrades = 0;
$filteredWonTrades = 0;
$filteredLostTrades = 0;
$filteredTotalProfits = 0;
$filteredWonProfits = 0;

$resultFilteredTrades = null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['filterDate'])) {
    $filterDate = $_POST['filterDate'];
    $filterDateFormatted = date('Y-m-d', strtotime($filterDate));

    $getFilteredTradesQuery = "SELECT * FROM trades WHERE username='$username' AND DATE(timestamp) = '$filterDate'";
    $resultFilteredTrades = mysqli_query($conn, $getFilteredTradesQuery);

    if (!$resultFilteredTrades) {
        die('Error: ' . mysqli_error($conn));
    }

    $getFilteredTotalTradesQuery = "SELECT COUNT(*) as totalTrades FROM trades WHERE username='$username' AND DATE(timestamp) = '$filterDate'";
    $resultFilteredTotalTrades = mysqli_query($conn, $getFilteredTotalTradesQuery);
    $rowFilteredTotalTrades = mysqli_fetch_assoc($resultFilteredTotalTrades);
    $filteredTotalTrades = $rowFilteredTotalTrades['totalTrades'];

    $getFilteredWonTradesQuery = "SELECT COUNT(*) as wonTrades FROM trades WHERE username='$username' AND tradeDescription='won' AND DATE(timestamp) = '$filterDate'";
    $resultFilteredWonTrades = mysqli_query($conn, $getFilteredWonTradesQuery);
    $rowFilteredWonTrades = mysqli_fetch_assoc($resultFilteredWonTrades);
    $filteredWonTrades = $rowFilteredWonTrades['wonTrades'];

    $getFilteredLostTradesQuery = "SELECT COUNT(*) as lostTrades FROM trades WHERE username='$username' AND tradeDescription='lost' AND DATE(timestamp) = '$filterDate'";
    $resultFilteredLostTrades = mysqli_query($conn, $getFilteredLostTradesQuery);
    $rowFilteredLostTrades = mysqli_fetch_assoc($resultFilteredLostTrades);
    $filteredLostTrades = $rowFilteredLostTrades['lostTrades'];

    $getFilteredTotalProfitsQuery = "SELECT SUM(profit) AS totalProfits FROM trades WHERE username='$username' AND DATE(timestamp) = '$filterDate'";
    $resultFilteredTotalProfits = mysqli_query($conn, $getFilteredTotalProfitsQuery);
    $rowFilteredTotalProfits = mysqli_fetch_assoc($resultFilteredTotalProfits);
    $filteredTotalProfits = $rowFilteredTotalProfits['totalProfits'];

    $getFilteredWonProfitsQuery = "SELECT SUM(profit) AS wonProfits FROM trades WHERE username='$username' AND tradeDescription='won' AND DATE(timestamp) = '$filterDate'";
    $resultFilteredWonProfits = mysqli_query($conn, $getFilteredWonProfitsQuery);
    $rowFilteredWonProfits = mysqli_fetch_assoc($resultFilteredWonProfits);
    $filteredWonProfits = $rowFilteredWonProfits['wonProfits'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="home.css">
    <script src="homeScript.js"></script></script>
</head>

<body>
    <header>
        <img class="header-logo" src="logo/mylogo.png" alt="Logo">
        <h2>Welcome, <span><?php echo $username; ?></span>!</h2>
        <a href="logout.php">Logout</a>
    </header>

    <div class="main-content">
        <div class="left-column">
            <div class="current-trade-container">
                <h3>Make record of your Trades</h3>
                <form action="home.php" method="post" onsubmit="return validateForm()">
                    <label for="tradeDescription">Trade Type:</label>
                    <select name="tradeDescription" id="tradeDescription">
                        <option value="" disabled selected>Select Trade Type</option>
                        <option value="won">Won</option>
                        <option value="lost">Lost</option>
                    </select>

                    <label for="profit">Profit for the trade ($): </label>
                    <input type="number" step="0.01" name="profit" id="profit"><br>
                    <input type="submit" value="Add Trade">
                </form>
            </div>

            <div id="customAlert" class="custom-alert">
                <span class="close-btn" onclick="closeAlert()">&times;</span>
                <p>Please fill in all fields before submitting the form.</p>
            </div>

            <div class="trade-statistics">
                <h3>Trade Statistics</h3>
                <p>Total Trades: <span><?php echo $totalTrades; ?></span></p>
                <p>Trades Won: <span><?php echo $wonTrades; ?></span></p>
                <p>Trades Lost: <span><?php echo $lostTrades; ?></span></p>
            </div>

            <div class="profit-statistics">
                <h3>Profit Statistics</h3>
                <p>Total Profits: $<span><?php echo $totalProfits; ?></span></p>
                <p>Won Profits: $<span><?php echo $wonProfits; ?></span></p>
            </div>
        </div>
        <div class="right-column">

            <nav class="section-toggle-container">
                <h3 class="toggle-item active" data-type="dateFilter" onclick="toggleSections('dateFilterSection')">Date Filter</h3>
                <h3 class="toggle-item " data-type="allTrades" onclick="toggleSections('allTradesSection')">All Trades</h3>
            </nav>
            <div class="date-filter-container" id="dateFilterSection" style="display: block;">
                <h3>Date Filter</h3>
                <form action="home.php" method="post">
                    <label for="filterDate">Filter Trades by Date:</label>
                    <input type="date" name="filterDate" id="filterDate" value="<?php echo date('Y-m-d'); ?>" required>
                    <input type="submit" value="Filter">
                </form>

                <div class="filtered-trades" id="filteredTradesContainer">
                    <h3>Filtered Trades for <?php echo $filterDateFormatted; ?></h3>
                    <?php if ($resultFilteredTrades !== null && mysqli_num_rows($resultFilteredTrades) > 0) : ?>
                        <p>Total Trades: <span><?php echo $filteredTotalTrades; ?></span></p>
                        <p>Trades Won: <span><?php echo $filteredWonTrades; ?></span></p>
                        <p>Trades Lost: <span><?php echo $filteredLostTrades; ?></span></p>

                        <h3>Profit Statistics</h3>
                        <p>Total Profits: $<span><?php echo $filteredTotalProfits; ?></span></p>
                        <p>Won Profits: $<span><?php echo $filteredWonProfits; ?></span></p>
                    <?php else : ?>
                        <p>No trades for the selected date.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="all-trades" id="allTradesSection" style="display: none;">
                <?php
                $getAllTradesQuery = "SELECT * FROM trades WHERE username='$username'";
                $resultAllTrades = mysqli_query($conn, $getAllTradesQuery);

                $totalAllTrades = mysqli_num_rows($resultAllTrades);
                $totalWonTrades = 0;
                $totalLostTrades = 0;
                $totalWonProfits = 0;

                if ($totalAllTrades > 0) {
                    while ($row = mysqli_fetch_assoc($resultAllTrades)) {
                        if ($row['tradeDescription'] == 'won') {
                            $totalWonTrades++;
                            $totalWonProfits += $row['profit'];
                        } elseif ($row['tradeDescription'] == 'lost') {
                            $totalLostTrades++;
                        }
                    }
                } else {
                    echo "<p>No trades available.</p>";
                }
                ?>
                <div class="trade-statistics">
                    <h3>All Trades Statistics</h3>
                    <p>All Trades: <span><?php echo $totalAllTrades; ?></span></p>
                    <p>Trades Won: <span><?php echo $totalWonTrades; ?></span></p>
                    <p>Trades Lost: <span><?php echo $totalLostTrades; ?></span></p>
                    <p>Profit Made: <span><?php echo $totalWonProfits; ?></span></p>
                </div>
            </div>



        </div>


    </div>

    <footer>
        &copy; 2023 AhhhhMoseTech
    </footer>
</body>

</html>


<?php
mysqli_close($conn);
?>