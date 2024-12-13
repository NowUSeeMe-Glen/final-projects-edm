<?php
class userModel
{
    private $user;
    private $pass;

    public function setUser($user){
        $this->user = $user;
    }

    public function getChildrenCount($userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM children WHERE parent_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    public function getUpcomingEventsCount($userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM events WHERE user_id = ? AND event_date >= CURRENT_DATE");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    public function getNotificationsCount($userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND read_status = 0");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    public function getChildren($userId) {
        $stmt = $this->db->prepare("SELECT * FROM children WHERE parent_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
