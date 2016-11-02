<?php class User {
	
	private $connection;
	
	function __construct($mysqli) {
		$this->connection = $mysqli;
	}
}

	function saveInterest ($interest) {

		$stmt = $this->connection->prepare("INSERT INTO interests (interest) VALUES (?)");
	
		echo $this->connection->error;
		
		$stmt->bind_param("s", $interest);
		
		if($stmt->execute()) {
			echo "salvestamine õnnestus";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		
		$stmt->close();
		//$mysqli->close();
		
	}
	
	function saveUserInterest ($interest_id) {
		
		echo "huviala: ".$interest_id."<br>";
		echo "kasutaja: ".$_SESSION["userId"]."<br>";
		

		//kas on juba olemas
		
		$stmt = $this->connection->prepare("
			SELECT id FROM user_interests_1 
			WHERE user_id=? AND interest_id=?
		");
		$stmt->bind_param("ii", $_SESSION["userId"], $interest_id);
		$stmt->execute();
		
		if ($stmt->fetch()) {
			// oli olemas 
			echo "juba olemas";
			
			//ära salvestamisega jätka
			return;
		}
	
		$stmt->close();
		// jätkan salvestamisega...
		
		$stmt = $this->connection->prepare("
			INSERT INTO user_interests_1 
			(user_id, interest_id) VALUES (?, ?)
		");
	
		echo $this->connection->error;
		
		$stmt->bind_param("ii", $_SESSION["userId"], $interest_id);
		
		if($stmt->execute()) {
			echo "salvestamine õnnestus";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		
		$stmt->close();
		//$mysqli->close();
		
	}
		
	function getAllInterests() {
		
		$stmt = $this->connection->prepare("
			SELECT id, interest
			FROM interests
		");
		echo $this->connection->error;
		
		$stmt->bind_result($id, $interest);
		$stmt->execute();
		
		
		//tekitan massiivi
		$result = array();
		
		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {
			
			//tekitan objekti
			$i = new StdClass();
			
			$i->id = $id;
			$i->interest = $interest;
		
			array_push($result, $i);
		}
		
		$stmt->close();
		//$mysqli->close();
		
		return $result;
	}
	
	function getAllUserInterests() {
		
		$stmt = $$this->connection->prepare("
			SELECT interest
			FROM interests
			JOIN user_interests
			ON interests.id = user_interests.interest_id
			WHERE user_interests.user_id = ?
		");
		echo $this->connection->error;
		
		$stmt->bind_param("i", $_SESSION["userId"]);
		
		$stmt->bind_result($interest);
		$stmt->execute();
		
		
		//tekitan massiivi
		$result = array();
		
		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {
			
			//tekitan objekti
			$i = new StdClass();
			
			$i->interest = $interest;
		
			array_push($result, $i);
		}
		
		$stmt->close();
		//$mysqli->close();
		
		return $result;
	}
?>