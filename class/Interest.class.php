<?php class Interest {
	
	private $connection;
	
	function __construct($mysqli) {
		$this->connection = $mysqli;
	}

	function save($interest) {

		$stmt = $this->connection->prepare("INSERT INTO interests (interest) VALUES (?)");
	
		echo $this->connection->error;
		
		$stmt->bind_param("s", $interest);
		
		if($stmt->execute()) {
			echo "salvestamine �nnestus";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		
		$stmt->close();
		//$mysqli->close();
		
	}
	
	function saveUser($interest_id) {
		
		echo "huviala: ".$interest_id."<br>";
		echo "kasutaja: ".$_SESSION["userId"]."<br>";
		

		//kas on juba olemas
		
		$stmt = $this->connection->prepare("
			SELECT id FROM user_interests
			WHERE user_id=? AND interest_id=?
		");
		$stmt->bind_param("ii", $_SESSION["userId"], $interest_id);
		$stmt->execute();
		
		if ($stmt->fetch()) {
			// oli olemas 
			echo "juba olemas";
			
			//�ra salvestamisega j�tka
			return;
		}
	
		$stmt->close();
		// j�tkan salvestamisega...
		
		$stmt = $this->connection->prepare("
			INSERT INTO user_interests 
			(user_id, interest_id) VALUES (?, ?)
		");
	
		echo $this->connection->error;
		
		$stmt->bind_param("ii", $_SESSION["userId"], $interest_id);
		
		if($stmt->execute()) {
			echo "salvestamine �nnestus";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		
		$stmt->close();
		//$mysqli->close();
		
	}
		
	function getAll() {
		
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
	
	function getAllUser() {
		
		$stmt = $this->connection->prepare("
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
}
?>