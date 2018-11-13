<?php 
	$user = $_SESSION["userId"]; ?>
	
<!--HTML home page for shop.qscu.org-->
<!DOCTYPE HTML>
<html lang="en">
<!--    Head-->

<head lang="en">
    <meta charset="utf-8">
    <title>QSCU Merch Store</title>
    <link rel="stylesheet" href="css/header.css" />
    <link rel="stylesheet" href="css/footer.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="icon" type="image/x-icon" href="images/QSCU_favicon.png" />
    <script src="http://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="script/browsebar.js"></script>
</head>

<!--    Body-->

<body>
    <!--    Header-->
    <header>
        <div id="htop">
            <a href="homeWithoutTables.php" id="logo"><img id="headerIcon" src="images/QSCU_whitePlain.png" alt="Quantitative Sciences Course Union"/> </a>
            <h1 id="title">QSCU Merchandise Store</h1>
        </div>

        <div id="hbot"><?php 
	        if($headerSet==1){
		        echo '<div id="variablebutton" class="hbotline">
                			<a href="homeWithoutTables.php" id="homebutton">Home</a>
							</div>';
	        }else{
		        echo '<div id="variablebutton" class="hbotline">
	            		<div id="browsedropsection">
							<p id="browsedropbutton">Browse</p>
						</div>
					</div>';
	        }
	        ?>
            

            <div id="searchbar" class="hbotline">
                <form id="searchform">
                    <label id="search" for="textinput">Search: </label>
                    <input type="text" name="Search" id="textinput" />
                    <a href="searchpage.php" id="searchicon"><i class="fa fa-search"></i></a>
                </form>
            </div>

            <div id="profile" class="hbotline">
                <a href="cart.php"><img src="images/shoppingcart.png" alt="shopping cart icon" id="shoppingcart"/></a>
                
	                <?php 
	                if(isset($_SESSION["userId"])){
						echo "<a href='profile.php'>" . $user . "</a>";
	        		}else{
						echo "<a href='login.php'>Login</a>";
	        		}
	        		?>
	        
            </div>

        </div>

    </header>
</body>
</html>
<!-- Header end -->