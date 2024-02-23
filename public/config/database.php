<?php 

    namespace App\Database;

	class Sql {

		const HOSTNAME = "mysql";
		const USERNAME = "root";
		const PASSWORD = "root";
		const DBNAME = "mysql";

		private $conn;

		public function __construct() {
			$this->conn = new \PDO(
				"mysql:dbname=".Sql::DBNAME.";host=".Sql::HOSTNAME, 
				Sql::USERNAME,
				Sql::PASSWORD
			);
		}

		private function setParams($statement, $parameters = []) {
			foreach ($parameters as $key => $value) {			
				$this->bindParam($statement, $key, $value);
			}
		}

		private function bindParam($statement, $key, $value) {
			$statement->bindParam($key, $value);
		}

		public function query($rawQuery, $params = []) {
			$stmt = $this->conn->prepare($rawQuery);
			$this->setParams($stmt, $params);
			$stmt->execute();
		}

		public function select($rawQuery, $params = []):array {
			$stmt = $this->conn->prepare($rawQuery);
			$this->setParams($stmt, $params);
			$stmt->execute();

			return $stmt->fetchAll(\PDO::FETCH_ASSOC);
		}

	}

?>