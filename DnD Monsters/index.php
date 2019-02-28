<?php
$servername ="localhost";
$username = "root";
$password = "";
$dbname = "dnd monsters";
$conn = new mysqli($servername, $username, $password, $dbname);
$monsters = array();
$message = "";

if(isset($_GET['monsterType']))
{
    $monsterType = $_GET['monsterType'];
}
else
{
    $monsterType = "'Humanoid'";
}

if(isset($_GET['monsterId']))
{
    $monsterId = $_GET['monsterId'];
}
else
{
    $monsterId = 0;
}

if(isset($_POST["review_Button"]))
{
    if(isset($_POST["review"]) & isset($_POST["rating"]))
    {
        $review = addslashes($_POST["review"]);
        $monsterId = $_POST["monsterId"];
        $rating = $_POST["rating"];
        if($review != "")
        {
            $date = time();
            $insertSQL = "INSERT INTO rating VALUES (0, $monsterId, $rating, $date, '$review')"; // defines SQL NOW() is date
            echo $insertSQL;
            $resultinsert = $conn->query($insertSQL) or die(mysqli_error($conn)); //executes query
            header("location: .?monsterType=$monsterType&monsterId=$monsterId");
        }
        else
        {
            $message = "please fill in all fields";
        }
    }
    else
    {
        $message = "please fill in all fields";
    }
}

?>

<!doctype HTML>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="description" content="Fear the Legion of Monsters">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <title>DnD Monsters</title>
    </head>
    <body>
        <header id = "header">
            <div id = "logo">
                <a href = "index.php" id = "logo-link"><img src = "images/DnD.png" id = "DnD-image"></a>
            </div>
        </header>
        <div id = "main-container">
            <div id = "left-column">
                <nav id = "main-nav">
                <?php 
                    $sql = "SELECT * FROM `team`";
                    $results = $conn->query($sql);
                    while($type = $results->fetch_assoc()){ ?>
                        <ul>
                            <li><a href = "index.php?monsterType='<?php echo $type["monsterType"];?>'"><img src = "<?php echo $type["categoryImage"]; ?>"><?php echo $type["monsterType"]; ?></a></li>
                        </ul>
                        <?php } ?>
                </nav>       
            </div>
            <div id = "center-column">
                <?php
                    $sql = "SELECT * FROM `monster` WHERE monsterType=" . $monsterType; 
                    $results = $conn->query($sql);
                    while($monster = $results->fetch_assoc())
                        {
                            ?>
                            <div class = "monsters">
                                <h3><?php echo $monster["monsterName"]; ?></h3>
                                <img src = "<?php echo $monster["monsterImage"]; ?>">
                                <div class = "desc"><?php echo $monster["monsterDesc"]; ?></div>
                                <div class = "clearfix"></div>
                                <a href = "index.php?monsterType= <?php echo $monsterType ?>&monsterId= <?php echo $monster["monsterId"]?>">
                                    <input type="button" value="more info" class = "info">
                                </a>
                            </div>
                            <?php    
                        }
                ?>
                </div>
            <div id = "right-column">
            <?php
                if($monsterId != 0)
                {                   
                    $sql = "SELECT * FROM `monster` WHERE monsterId=$monsterId"; 
                    $results = $conn->query($sql);
                    while($monster = $results->fetch_assoc())
                    {
                        ?>
                        <div><img src = "<?php echo $monster["monsterImage"]; ?>" id = "profilePic"></div>
                        <div id = "profile"><h1><?php echo $monster["monsterName"]; ?></h1></div>
                        <div id = "story"><p><?php echo $monster["monsterDesc"]; ?></p></div>
                        <div id = "stats"><p><?php echo $monster["monsterPower"]; ?></p></div>
                        <?php   ?> 
                        <form method = "post" action = "index.php?monsterId=<?php echo $monsterType;?>&monsterId<?php echo $monsterId; ?>">
                            <div>
                                <br />
                                please leave a review
                                <br />
                                1: <input type = "radio" name = "rating" value = "1" />
                                2: <input type = "radio" name = "rating" value = "2" />
                                3: <input type = "radio" name = "rating" value = "3" />
                                4: <input type = "radio" name = "rating" value = "4" />
                                5: <input type = "radio" name = "rating" value = "5" /><br />
                                <textarea name = "review"></textarea>
                                <?php
        
                                ?>
                                <button type = "submit" name ="review_Button"> rate the legion</button>
                                <input type = "hidden" name ="monsterId" value = "<?php echo $monsterId ?>" />
                            </div>
                            <?php echo "<h3>" , $message , "</h3>"; ?>
                        </form> 
                        <div id = "reviews">
                            <?php 
                            $sql = "SELECT * FROM rating WHERE monsterId = '$monsterId'";
                            $results = $conn->query($sql);
                            while($review = $results->fetch_assoc())
                            {
                                ?>
                                <div class = "review"><p><?php echo $review["ratingReview"]; ?></p></div>
                          <?php  } ?>
                        </div> <?php                      
                    }
                }
                ?>               
            </div>
        </div>
    </body>
</html>