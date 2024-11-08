<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="../img/logo.png" sizes="32x32">
    <link rel="icon" type="image/png" href="../img/logo.png" sizes="16x16">
    <title>SUBJECT ENRICHMENT ENTRY</title>
    <link rel="stylesheet" href="se_entry_styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>

    <div class="header">
        <h2>ABC School, XYZ</h2>
        <h3>ENTER SUBJECT ENRICHMENT DETAILS : 2024-25 लिखें</h3>
    </div>

    <div class="form-container">
        <form method="post" action="se_submit_form.php">
            <label for="pwd">ENTER USERNAME</label>
            <input type="text" id="username" name="username" required>

            <label for="pwd">ENTER PASSWORD</label>
            <input type="password" id="pwd" name="pwd" required>

            <label for="name">NAME OF TEACHER:</label>
            <input type="text" id="name" name="name" required>

            <label for="des">DESIGNATION:</label>
            <input type="text" id="des" name="des" required>

            <label for="m">MONTH</label>
            <select id="m" name="m" required>
                <option value="" disabled selected>Select</option>
                <option value="APRIL-2024">APRIL-2024</option>
                <option value="MAY-2024">MAY-2024</option>
                <option value="JUNE-2024">JUNE-2024</option>
                <option value="JULY-2024">JULY-2024</option>
                <option value="AUGUST-2024">AUGUST-2024</option>
                <option value="SEPTEMBER-2024">SEPTEMBER-2024</option>
                <option value="OCTOBER-2024">OCTOBER-2024</option>
                <option value="NOVEMBER-2024">NOVEMBER-2024 </option>
                <option value="DECEMBER-2024">DECEMBER-2024</option>
                <option value="JANUARY-2025">JANUARY-2025</option>
                <option value="FEBRUARY-2025">FEBRUARY-2025</option>
                <option value="MARCH-2025">MARCH-2025</option>
            </select>

            <label for="c">CLASS</label>
            <select id="c" name="c" required>
            <option value="" disabled selected>Select</option>
                <option value="1A">1A</option><br>
                <option value="1B">1B</option><br>
                <option value="2A">2A</option><br>
                <option value="2B">2B</option><br>
                <option value="3A">3A</option><br>
                <option value="3B">3B</option><br>
                <option value="BALVATIKA-III">BALVATIKA-III</option><br>
                <option value="4A">4A</option><br>
                <option value="4B">4B</option><br>
                <option value="5A">5A</option><br>
                <option value="5B">5B</option><br>
                <option value="6A">6A</option><br>
                <option value="6B">6B</option><br>
                <option value="7A">7A</option><br>
                <option value="7B">7B</option><br>
                <option value="8A">8A</option><br>
                <option value="8B">8B</option><br>
                <option value="8C">8C</option><br>
                <option value="9A">9A</option><br>
                <option value="9B">9B</option><br>
                <option value="9C">9C</option><br>
                <option value="10A">10A</option><br>
                <option value="10B">10B</option><br>
                <option value="10C">10C</option><br>
                <option value="11-SCI">11-SCI</option><br>
                <option value="11-COMM">11-COMM</option><br>
                <option value="12-SCI">12-SCI</option><br>
                <option value="12-COMM">12-COMM</option><br>
            </select>

            <label for="s">SUBJECT</label>
            <select id="s" name="s" required>
                <option value="" disabled selected>Select</option>
                <option value="HINDI">HINDI</option>
                <option value="ENGLISH">ENGLISH</option>
                <option value="MATH">MATH</option>
                <option value="EVS">EVS</option>
                <option value="SCIENCE">SCIENCE</option>
                <option value="SSC">SSC</option>
                <option value="SANSKRIT">SANSKRIT</option>
                <option value="PHYSICS">PHYSICS</option>
                <option value="CHEMISTRY">CHEMISTRY</option>
                <option value="BIOLOGY">BIOLOGY</option>
                <option value="COMPUTER SC">COMPUTER SC</option>
                <option value="IP">IP</option>
                <option value="ACCOUNTANCY">ACCOUNTANCY</option>
                <option value="BST">BST</option>
                <option value="ECONOMICS">ECONOMICS</option>
                <option value="GEOGRAPHY">GEOGRAPHY</option>
                <option value="HISTORY">HISTORY</option>
                <option value="PHY EDU">PHY EDU</option>
                <option value="MUSIC">MUSIC</option>
                <option value="AE">AE</option>
                <option value="WE">WE</option>
                <option value="LIBRARY">LIBRARY</option>
                <option value="AI">AI</option>
            </select>

            <label for="t">WRITE TOPIC (MAX 500 CHARACTERS)</label>
            <textarea id="t" name="t" maxlength="500" required></textarea>

            <input type="submit" name="sub1" value="SUBMIT">
        </form>
    </div>

    <div class="center">
        <input type="button" name="back" value="BACK" onclick="window.location.href='../index.html';">
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> ABC School, XYZ. All rights reserved.
    </footer>

</body>

</html>