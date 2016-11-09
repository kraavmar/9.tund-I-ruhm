<?php class Car {
	
	private $connection;

	function __construct($mysqli) {
		
		$this->connection = $mysqli;
	}

	function save ($plate, $color) {
		

		$stmt = $this->connection->prepare("INSERT INTO cars_and_colors (plate, color) VALUES (?, ?)");
	
		echo $this->connection->error;
		
		$stmt->bind_param("ss", $plate, $color);
		
		if($stmt->execute()) {
			echo "salvestamine õnnestus";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		
		$stmt->close();
		//$mysqli->close();
		
	}
	
	function getAll($q, $sort, $order) {
		$allowedSort = ["id", "plate", "color"];
		
		if(!in_array($sort, $allowedSort)) { //esimene asi, mis ta tahab, on nõel ja teine heinakuhi
			// ei ole lubatud tulp, siis sorteerime id järgi
			$sort = "id";
		}
		
		$orderBy = "ASC";
		
		//see if tagab, et orderby saab aint 2 väärtust olla
		if($order == "DESC") {
			$orderBy = "DESC";
		}
		
		echo "Sorteerin ".$sort." ".$orderBy." ";
		
		//kas otsib
		if($q != "") {
			echo "Otsib: ".$q;
			$stmt =  $this->connection->prepare("
				SELECT id, plate, color
				FROM cars_and_colors
				WHERE deleted IS NULL 
				AND (plate LIKE ? OR color LIKE ?)
				ORDER BY $sort $order
			"); //WHERE asi juurde, kui kustutamise lisad
			$searchWord = "%".$q."%";
			$stmt->bind_param("ss", $searchWord, $searchWord);
			
		} else {
			$stmt =  $this->connection->prepare("
				SELECT id, plate, color
				FROM cars_and_colors
				WHERE deleted IS NULL
				ORDER BY $sort $order				
			");
		}
		
		echo  $this->connection->error;
		
		$stmt->bind_result($id, $plate, $color);
		$stmt->execute();
		
		
		//tekitan massiivi
		$result = array();
		
		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {
			
			//tekitan objekti
			$car = new StdClass();
			
			$car->id = $id;
			$car->plate = $plate;
			$car->carColor = $color;
			
			//echo $plate."<br>";
			// iga kord massiivi lisan juurde nr märgi
			array_push($result, $car);
		}
		
		$stmt->close();
		//$mysqli->close();
		
		return $result;
	}
	
	function getSingle($edit_id){
    

		//echo "id on ".$edit_id;
		
		$stmt = $this->connection->prepare("SELECT plate, color FROM cars_and_colors WHERE id=? AND deleted IS NULL"); // AND asi juurde kui kustutamise lisad 

		$stmt->bind_param("i", $edit_id);
		$stmt->bind_result($plate, $color);
		$stmt->execute();
		
		//tekitan objekti
		$car = new Stdclass();
		
		//saime ühe rea andmeid
		if($stmt->fetch()){
			// saan siin alles kasutada bind_result muutujaid
			$car->plate = $plate;
			$car->color = $color;
			
			
		}else{
			// ei saanud rida andmeid kätte
			// sellist id'd ei ole olemas
			// see rida võib olla kustutatud
			header("Location: data.php");
			exit();
		}
		
		$stmt->close();
		//$mysqli->close();
		
		return $car;
	}
	
	function update($id, $plate, $color){
		
		$stmt = $this->connection->prepare("UPDATE cars_and_colors SET plate=?, color=? WHERE id=? AND deleted IS NULL"); // AND asi juurde kui kustutamise lisad 
		$stmt->bind_param("ssi",$plate, $color, $id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "salvestus õnnestus!";
		}
		
		$stmt->close();
		//$mysqli->close();
		
	}
	
	function del($id){
 		
 		$stmt = $this->connection->prepare("UPDATE cars_and_colors SET deleted=NOW() WHERE id=? AND deleted IS NULL");
 		$stmt->bind_param("i",$id);
 		
 		// kas õnnestus salvestada
 		if($stmt->execute()){
 			// õnnestus
 			echo "kustutamine õnnestus!";
 		}
 		
 		$stmt->close();
 		//$mysqli->close();
 		
 	}
}