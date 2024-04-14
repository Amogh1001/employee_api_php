<?php

require __DIR__.'/database.php'; //Importing database.php to import the class Database()

class rest{ //Initialising the class rest 
	private $mysqli;
	private $db_connection;
	private $table = "hr"; //Table name, here we are using hr table, but you can use any table you want
	
	public function __construct() { //Constructor to initialise the database connection
		$this->db_connection = new Database(); //Creating object of class Database()
		$this->mysqli = new mysqli($this->db_connection->servername, $this->db_connection->username, $this->db_connection->password, $this->db_connection->dbname); //Creating mysqli object
	}

	public function create($getName, $getNum, $getemail, $getaddress, $getlogin, $getpwd, $getdate){ //Function to create a new record
		$getpwd = password_hash($getpwd, PASSWORD_BCRYPT); //Hashing the password using password_hash() function
		$query = "INSERT INTO hr(name, mobile_number, email, address, login_name, pwd, dt_creation) VALUES (?, ?, ?, ?, ?, ?, ?)"; 
		if ($this->mysqli->execute_query($query, [$getName, $getNum, $getemail, $getaddress, $getlogin, $getpwd, $getdate]) === TRUE) {
			return 1; //If the query is executed successfully, it will return 1
		} else {
			return 0; 
		}
	}

	public function login($getlogin, $getpwd) { //Function to login, takes in username and password as parameters
 		$query = "SELECT * FROM `$this->table` WHERE login_name = ?";
		$result = $this->mysqli->execute_query($query, [$getlogin]);
		if($result->num_rows == 0){ //If the username is not found in the database, it will return 0
			echo "Invalid username or password";
			exit();
		}
		$user = $result->fetch_assoc();
		if (password_verify($getpwd, $user["pwd"])) { //If the password matches the hashed password in the database, it will return 1
			$token = bin2hex(random_bytes(32));
			$updateQuery = "UPDATE hr SET token=? WHERE id=?";
			$this->mysqli->execute_query($updateQuery, [$token, $user["id"]]);
			$_SESSION["token"] = $token; //Setting the token in the session so that we can use the API for other programs in the same session
			return 1; //If the password matches, it will return 1
		} else {
			return 0;
		}
	}

	public function read($token) { //Function to read the data from the database, takes in token as parameter
		$query = "SELECT * FROM `$this->table` WHERE token = ?";
        $result = $this->mysqli->execute_query($query, [$token]);
		$techarray = array();
		while($row =mysqli_fetch_assoc($result)){
			$techarray[] = $row;
			unset($techarray[0]["token"]);
		}
		$techarray = json_encode($techarray);
		return $techarray; //Returns the data in JSON format
	}

	public function update($token, $getName, $getNum, $getemail, $getaddress, $getlogin, $getpwd) { //Function to update the data in the database, takes in token and other parameters as parameters
		$getdate = date("Y-m-d H:i:s"); //Getting the current date and time, it sets the date of updation
		$getpwd = password_hash($getpwd, PASSWORD_BCRYPT);
		$query = "UPDATE `$this->table` SET name = ?, mobile_number = ?, email = ?, address = ?, login_name = ?, pwd = ?, dt_creation = ? WHERE token = ?";
		if ($this->mysqli->execute_query($query, [$getName, $getNum, $getemail, $getaddress, $getlogin, $getpwd, $getdate, $token]) === TRUE) {
			return 1; //If the query is executed successfully, it will return 1
		} else {
			return 0;
		}
	}

	public function delete($getid) { //Function to delete the data from the database, takes in id as parameter
		$query = "DELETE FROM `$this->table` WHERE id = ?";
		if ($this->mysqli->execute_query($query, [$getid]) === TRUE) {
			return 1; //If the record is deleted successfully, it will return 1
		} else {
			return 0;
		}	
	}
}
?>