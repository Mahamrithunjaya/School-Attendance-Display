<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homework Submission Form</title>
    <link rel="stylesheet" href="styles2.css">
</head>
<body>
    <div class="container">
        <h1>ABC SCHOOL, XYZ</h1>
        <div class="center">
            <input type="button" name="back" value="BACK" onclick="window.location.href='../index.html';">
        </div>

        <p class="text-before-gifs">Winter Vaccation Homeworks  2024-25</p>

        <div class="gifs">
            <img src="img/6.gif" width="150" height="100" alt="GIF 1">
            <img src="img/4.gif" width="150" height="100" alt="GIF 2">
            <img src="img/5.gif" width="150" height="100" alt="GIF 3">
        </div>

        <div class="visitor-counter">
        <span>VISITORS:</span>
            <!-- hitwebcounter Code START -->
<a href="https://www.hitwebcounter.com" target="_blank">
<img src="https://hitwebcounter.com/counter/counter.php?page=16133247&style=0036&nbdigits=6&type=page&initCount=0" title="Counter Widget" Alt="Visit counter For Websites"   border="0" /></a>  
        </div>

        <form method="post" action="submit_homework.php" id="myForm">
            <div class="form-group">
                <label for="username">ENTER USERNAME</label>
                <input type="text" id="username" name="username">
            </div>
            <div class="form-group">
                <label for="pwd">ENTER PASSWORD</label>
                <input type="password" id="pwd" name="pwd">
            </div>
            <div class="form-group">
                <label for="n">NAME OF TEACHER</label>
                <input type="text" id="n" name="n">
            </div>
            <div class="form-group">
                <label for="d">DESIG. OF TEACHER</label>
                <select id="d" name="d">
                    <option value="" disabled selected>SELECT</option>
                    <option value="PGT">PGT</option>
                    <option value="TGT">TGT</option>
                    <option value="PRT">PRT</option>
                </select>
            </div>
            <div class="form-group">
                <label for="c">SELECT CLASS</label>
                <select id="c" name="c">
                    <option value="" disabled selected>SELECT</option>
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
            </div>
            <div class="form-group">
                <label for="s">SELECT SUBJECT</label>
                <select id="s" name="s">
                    <option value="" disabled selected>SELECT</option>
                    <option value="MULTIDISCIPLINARY PROJECT">MULTIDISCIPLINARY PROJECT</option>
                    <option value="ENGLISH">ENGLISH</option>
                    <option value="HINDI">HINDI</option>
                    <option value="MATH">MATH</option>
                    <option value="EVS">EVS</option>
                    <option value="MUSIC">MUSIC</option>
                    <option value="SCIENCE">SCIENCE</option>
                    <option value="SSC">SSC</option>
                    <option value="SANSKRIT">SANSKRIT</option>
                    <option value="WE">WE</option>
                    <option value="LIBRARY">LIBRARY</option>
                    <option value="DRAWING">DRAWING</option>
                    <option value="PHE">PHE</option>
                    <option value="PHYSICS">PHYSICS</option>
                    <option value="CHEMISTRY">CHEMISTRY</option>
                    <option value="CS">CS</option>
                    <option value="BIOLOGY">BIOLOGY</option>
                    <option value="BST">BST</option>
                    <option value="ACCOUNTS">ACCOUNTS</option>
                    <option value="ECONOMICS">ECONOMICS</option>
                    <option value="IP">IP</option>
                    <option value="HISTORY">HISTORY</option>
                    <option value="GEOGRAPHY">GEOGRAPHY</option>
                    <option value="COMPUTER">COMPUTER</option>
                    <option value="AI">AI</option>
                </select>
            </div>
            <div class="form-group">
                <label for="t1">HOMEWORK 1 (MAX 500 CHARACTERS)</label>
                <textarea id="t1" name="t1" maxlength="500"></textarea>
            </div>
            <div class="form-group">
                <label for="t2">HOMEWORK 2 (MAX 500 CHARACTERS)</label>
                <textarea id="t2" name="t2" maxlength="500"></textarea>
            </div>
            <div class="form-group">
                <label for="t3">HOMEWORK 3 (MAX 500 CHARACTERS)</label>
                <textarea id="t3" name="t3" maxlength="500"></textarea>
            </div>
            <div class="form-group">
                <label for="t4">HOMEWORK 4 (MAX 500 CHARACTERS)</label>
                <textarea id="t4" name="t4" maxlength="500"></textarea>
            </div>
            <div class="form-group">
                <label for="t5">HOMEWORK 5 (MAX 500 CHARACTERS)</label>
                <textarea id="t5" name="t5" maxlength="500"></textarea>
            </div>
            <div class="form-group">
                <label for="t6">HOMEWORK 6 (MAX 500 CHARACTERS)</label>
                <textarea id="t6" name="t6" maxlength="500"></textarea>
            </div>
            <div class="form-group">
                <label for="t7">HOMEWORK 7 (MAX 500 CHARACTERS)</label>
                <textarea id="t7" name="t7" maxlength="500"></textarea>
            </div>
            <div class="buttons center">
                <input type="submit" name="sub1" value="SUBMIT">
                <input type="reset" name="sub11" value="RESET">
            </div>
        </form>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> ABC SCHOOL, XYZ. All rights reserved.
    </footer>

</body>
</html>
