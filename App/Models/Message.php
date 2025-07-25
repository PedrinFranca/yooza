<?php 

    namespace App\Models;

    use MF\Model\Model;

    class Message extends Model {
        private $id;
        private $from;
        private $to;
        private $type_msg;
        private $message;

        public function __get($attr){
            return $this->$attr;
        }
        public function __set($attr, $value){
            $this->$attr = $value;
        }

        public function getMessageBy($selector){
            $query = "SELECT * FROM messages WHERE $selector = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(1, $this->__get($selector));
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);

        }

        public function send_msg(){
            $query = "INSERT INTO messages (`from`, `to`, type_msg, `message`) VALUES(:from, :to, :type_msg, :message)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':from', $this->__get('from'));
            $stmt->bindValue(':to', $this->__get('to'));
            $stmt->bindValue(':type_msg', $this->__get('type_msg'));
            $stmt->bindValue(':message', $this->__get('message'));
            $stmt->execute();
            // return $this->db->lastInsertId();
            return $this;

        }

        public function getAll(){
            $query = "SELECT m.id, m.from, m.to, m.message, DATE_FORMAT(m.data,'%d/%m/%Y %H:%i') as data FROM messages as m WHERE `from` = ? AND `to` = ? OR `from` = ? AND `to` = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(1, $this->__get('from'));
            $stmt->bindValue(2, $this->__get('to'));
            $stmt->bindValue(3, $this->__get('to'));
            $stmt->bindValue(4, $this->__get('from'));
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
        }
        
        public function del_message() {
            $query = "DELETE FROM `messages` WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(1, $this->__get('id'));
            $stmt->execute();

            return $this;
            
        }
    }

?>