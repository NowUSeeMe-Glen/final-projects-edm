<?php
include_once('database.php');

class userController
{
    public function login($username, $password){
        $db = new database();
        $con = $db->initDatabase();
        $statement = $con->prepare(query::LOGIN_QUERY());
        $statement->execute([$username, $password]);
        $row = $statement->fetch();
        if ($row) {
            return json_encode(['status' => 'success', 'role' => $row['role']]);
        } else {
            return json_encode(['status' => 'error', 'message' => 'Invalid username or password.']);
        }
    }

    // public function user_login(){
    //     $db = new database();
    //     $con = $db->initDatabase();
    //     $statement = $con->prepare("select * from user_tbl");
    //     $statement->execute();
    //     $row = $statement->fetchAll();
    //     foreach ($row as $data) {
    //         echo $data['user']."|".$data['pass']."<br>";
    //     }
    // }

    public function register($username, $password, $email){
        $db = new database();
        $con = $db->initDatabase();
        $statement = $con->prepare("INSERT INTO user (user, pass, email) VALUES (?, ?, ?)");
        try {
            $statement->execute([$username, $password, $email]);
            return "Registration successful!";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function getAllUsers() {
        $db = new database();
        $con = $db->initDatabase();
        $statement = $con->prepare("SELECT id, user, email, role FROM user");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateRole($userId, $role) {
        $db = new database();
        $con = $db->initDatabase();
        $statement = $con->prepare("UPDATE user SET role = ? WHERE id = ?");
        try {
            $statement->execute([$role, $userId]);
            return "Role updated successfully!";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function deleteUser($userId) {
        $db = new database();
        $con = $db->initDatabase();
        $statement = $con->prepare("DELETE FROM user WHERE id = ?");
        try {
            $statement->execute([$userId]);
            return "User deleted successfully!";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function dashboard() {
        // Ensure user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        // Get user's children count
        $childrenCount = $this->userModel->getChildrenCount($_SESSION['user_id']);
        
        // Get upcoming events count
        $eventsCount = $this->userModel->getUpcomingEventsCount($_SESSION['user_id']);
        
        // Get notifications count
        $notificationsCount = $this->userModel->getNotificationsCount($_SESSION['user_id']);
        
        // Get children details
        $children = $this->userModel->getChildren($_SESSION['user_id']);

        // Include these variables in the view
        include 'public/view/userdashboard.php';
    }

}
