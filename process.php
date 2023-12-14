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

    <div id="customAlert" class="custom-alert">
        <span class="close-btn" onclick="closeAlert()">&times;</span>
        <p>Please fill in all fields before submitting the form.</p>
    </div>

    <h3>Trade Statistics</h3>
    <p>Total Trades: <span><?php echo $totalTrades; ?></span></p>
    <p>Trades Won: <span><?php echo $wonTrades; ?></span></p>
    <p>Trades Lost: <span><?php echo $lostTrades; ?></span></p>

    <h3>Profit Statistics</h3>
    <p>Total Profits: $<span><?php echo $totalProfits; ?></span></p>
    <p>Won Profits: $<span><?php echo $wonProfits; ?></span></p> -->

  <div>
        <h3>Date Filter</h3>
        <form action="home.php" method="post">
            <label for="filterDate">Filter Trades by Date:</label>
            <input type="date" name="filterDate" id="filterDate" value="<?php echo date('Y-m-d'); ?>" >
            <input type="submit" value="Filter">
        </form>
    </div>

    <h3>Filtered Trades for <?php echo $filterDateFormatted; ?></h3>
    <ul>
    <?php
        if (mysqli_num_rows($resultFilteredTrades) > 0) {
            while ($row = mysqli_fetch_assoc($resultFilteredTrades)) {
                echo "<li>{$row['tradeDescription']}: {$row['profit']}</li>";

            }
        } else {
            echo "<p>No trades for the selected date.</p>";
        }
        ?>
    </ul>