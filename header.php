  <body>
    <nav>
      <div class="topBanner">
        <ul>
          <li><a href="index.php">Home</a></li>
          <?php
            if (isset($_SESSION["userId"])) {
              echo "<p>Hello, " . $_SESSION["userUsername"] . "</p>";
              echo "<li><a href=\"profilePage.php\">Profile Page</a></li>";
              echo "<li><a href=\"backEnd/logout.php\">Log Out</a></li>";
              echo "<li><a href=\"upload.php\">Upload</a></li>";
            }
            else {
              echo "<li><a href=\"login.php\">Login</a></li>";
            }
          ?>
        </ul>
      </div>
    </nav>
