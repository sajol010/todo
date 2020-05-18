<?php
namespace Todo;

class Database{
    private $db_host;
    private $db_user;
    private $db_pass;
    private $db_name;
    private $conn;

    public function __construct(){
        $this->db_host = 'localhost';
        $this->db_user = 'root';
        $this->db_pass = '';
        $this->db_name = 'webdev_todo';
        $this->config();
        
    }

    private function config(){
        $this->conn = new \mysqli($this->db_host ,$this->db_user, $this->db_pass, $this->db_name);

        // Check connection
        if ($this->conn -> connect_errno) {
          echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
          exit();
        }
    }

    public function insert($name){
        // print_r($name);
        // die();
        $sql = "INSERT INTO tasks(name)
        VALUES ('$name')";

        if ($this->conn->query($sql) === TRUE) {
          return true;
        } else {
          return "Error: " . $sql . "<br>" . $this->conn->error;
        }
    }

    public function select($filter){
        if ($filter == 'all') {
            $sql = "SELECT * FROM tasks";
        }elseif ($filter == 'active') {
            $sql = "SELECT * FROM tasks WHERE status=1";
        }elseif ($filter == 'completed') {
            $sql = "SELECT * FROM tasks WHERE status=2";
        }else{
            $sql = "SELECT * FROM tasks";
        }


        $result = $this->conn->query($sql);
         return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function complete_task($id){

        $sel_sql = "SELECT * FROM tasks WHERE id='$id' limit 1";
        $result = $this->conn->query($sel_sql);
        $sel_data = $result->fetch_all(MYSQLI_ASSOC);
        if($sel_data[0]['status'] == 1){
           $sql = "UPDATE tasks SET status=2 WHERE id='$id'";
        }else{
           $sql = "UPDATE tasks SET status=1 WHERE id='$id'";
        }
        if ($this->conn->query($sql) === TRUE) {
          return true;
        } else {
          return "Error: " . $sql . "<br>" . $this->conn->error;
        }
    }


    public function countStatus($status){
        $sql = "SELECT * FROM tasks WHERE status='$status'";
        $result = $this->conn->query($sql);
        return $result->num_rows;
    }

    public function removeTaskById($id){
        $sql = "DELETE FROM tasks WHERE id='$id'";
        $result = $this->conn->query($sql);
        return $result;
    }

    public function updateTask($id, $name){
       $sql = "UPDATE tasks SET name='$name' WHERE id='$id'";
        $result = $this->conn->query($sql);
        return $result;
    }

    public function deleteAllComeplete(){
       $sql = "DELETE FROM tasks WHERE status='2'";
        $result = $this->conn->query($sql);
        return $result;
    }

}