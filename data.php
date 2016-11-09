<?php 
	
	require("functions.php");
	
	//kui ei ole kasutaja id'd
	if (!isset($_SESSION["userId"])){
		
		//suunan sisselogimise lehele
		header("Location: login.php");
		exit();
	}
	
	
	//kui on ?logout aadressireal siis login välja
	if (isset($_GET["logout"])) {
		
		session_destroy();
		header("Location: login.php");
		exit();
	}
	
	$msg = "";
	if(isset($_SESSION["message"])){
		$msg = $_SESSION["message"];
		
		//kui ühe näitame siis kustuta ära, et pärast refreshi ei näitaks
		unset($_SESSION["message"]);
	}
	
	
	if ( isset($_POST["plate"]) && 
		isset($_POST["color"]) && 
		!empty($_POST["plate"]) && 
		!empty($_POST["color"])
	  ) {
		  
		$Car->save($Helper->cleanInput($_POST["plate"]), $Helper->cleanInput($_POST["color"]));
		
	}
	
	//saan kõik auto andmed
	//kas keegi otsib
	if(isset($_GET["q"])){
		//Kui otsib, võtame otsisõna aadressirealt
		$q = $_GET["q"];
		//otsisõna funktsiooni sisse
		//$carData = $Car->getAll($q);  Ka nii toimib
	} else {
		//otsisõna tühi
		$q = "";
		//$carData = $Car->getAll($q);
	}
	
	$sort = "id";
	$order = "ASC";
	
	if(isset($_GET["sort"]) && isset($_GET["order"])) {
		$sort = $_GET["sort"];
		$order = $_GET["order"];
	}
	
	//otsisõna funktsiooni sisse
	$carData = $Car->getAll($q, $sort, $order);
	//echo "<pre>";
	//var_dump($carData);
	//echo "</pre>";
?>
<h1>Data</h1>
<?=$msg;?>
<p>
	Tere tulemast <a href="user.php"><?=$_SESSION["userEmail"];?>!</a>
	<a href="?logout=1">Logi välja</a>
</p>


<h2>Salvesta auto</h2>
<form method="POST">
	
	<label>Auto nr</label><br>
	<input name="plate" type="text">
	<br><br>
	
	<label>Auto värv</label><br>
	<input type="color" name="color" >
	<br><br>
	
	<input type="submit" value="Salvesta">
	
	
</form>

<h2>Autod</h2>

<form>
	<input type="search" name="q" value="<?=$q;?>"> 
	<input type="submit" value="Otsi">
</form>

<?php 
	
	$html = "<table>";
	
	$html .= "<tr>";
		$idOrder = "ASC"; //ascending
		$plateOrder="ASC"; 
		$colorOrder="ASC"; 
		$idArrow = "&uarr;";
		$plateArrow = "&uarr;";
		$colorArrow = "&uarr;";
		if (isset($_GET["sort"]) && $_GET["sort"] == "id") {
			if (isset($_GET["order"]) && $_GET["order"] == "ASC") {
				$idOrder="DESC"; //descending
				$idArrow = "&darr;";
			}
		}
		if (isset($_GET["sort"]) && $_GET["sort"] == "plate") {
			if (isset($_GET["order"]) && $_GET["order"] == "ASC") {
				$plateOrder="DESC"; 
				$plateArrow = "&darr;";
			}
		}
		if (isset($_GET["sort"]) && $_GET["sort"] == "color") {
			if (isset($_GET["order"]) && $_GET["order"] == "ASC") {
				$colorOrder="DESC";
				$colorArrow = "&darr;";
			}
		}
		$html .= "<th>
				<a href='?q=".$q."&sort=id&order=".$idOrder."'>
					id ".$idArrow."
				</a>
				</th>";
		$html .= "<th>
				<a href='?q=".$q."&sort=plate&order=".$plateOrder."'>
					plate ".$plateArrow."
				</a>	
				</th>";
		$html .= "<th>
				<a href='?q=".$q."&sort=color&order=".$colorOrder."'>
					color ".$colorArrow."
				</a>
				</th>";
	$html .= "</tr>";
	
	//iga liikme kohta massiivis
	foreach($carData as $c){
		// iga auto on $c
		//echo $c->plate."<br>";
		
		$html .= "<tr>";
			$html .= "<td>".$c->id."</td>";
			$html .= "<td>".$c->plate."</td>";
			$html .= "<td style='background-color:".$c->carColor."'>".$c->carColor."</td>";
            $html .= "<td><a href='edit.php?id=".$c->id."'>edit.php</a></td>";

		$html .= "</tr>";
	}
	
	$html .= "</table>";
	
	echo $html;
	
	
	$listHtml = "<br><br>";
	
	foreach($carData as $c){
		
		
		$listHtml .= "<h1 style='color:".$c->carColor."'>".$c->plate."</h1>";
		$listHtml .= "<p>color = ".$c->carColor."</p>";
	}
	
	echo $listHtml;
	
	
	

?>

<br>
<br>
<br>
<br>
<br>

