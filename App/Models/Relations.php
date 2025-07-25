<?php 

    namespace App\Models;

    use MF\Model\Model;

    class Relations extends Model {
        private $id;
        private $user1;
        private $user2;
        private $surname_1;
        private $surname_2;

        public function __get($attr){
            return $this->$attr;
        }
        public function __set($attr, $value){
            $this->$attr = $value;
        }

        public function getRelation(){
            // $query = "SELECT * FROM relations WHERE `from` = ? AND `to` = ?";
            $query = "  SELECT 
                            r.*, DATE_FORMAT(r.update_at,'%d/%m/%Y %H:%i') as update_at, 
                            u.id as id_u, u.name as name_u, u.email as email_u, u.username as username_u, u.profile_image as profile_image_u,
                            u2.id as id_u2, u2.name as name_u2, u2.email as email_u2, u2.username as username_u2, u2.profile_image as profile_image_u2,
                            (
                                SELECT m.message
                                FROM messages m
                                WHERE 
                                    (m.from = r.user_1 AND m.to = r.user_2)
                                    OR 
                                    (m.from = r.user_2 AND m.to = r.user_1)
                                ORDER BY m.id DESC
                                LIMIT 1
                            ) as last_message,
                            (
                                SELECT DATE_FORMAT(m.data,'%d/%m/%Y %H:%i')
                                FROM messages m
                                WHERE 
                                    (m.from = r.user_1 AND m.to = r.user_2)
                                    OR 
                                    (m.from = r.user_2 AND m.to = r.user_1)
                                ORDER BY m.id DESC
                                LIMIT 1
                            ) as data_last_message

                        FROM 
                            relations as r 
                            LEFT JOIN usuarios as u ON r.user_1 = u.id 
                            LEFT JOIN usuarios as u2 ON r.user_2 = u2.id 
                        WHERE 
                            (r.user_1 = :user_1)
                            OR 
                            (r.user_2 = :user_1)
                        ORDER BY
                            r.update_at desc
                        ";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':user_1', $this->__get('user1'));
            $stmt->bindValue(':user_2', $this->__get('user2'));
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function getRelationBy2(){ // OBS: By2 = De duas pessoas especificas;
            // $query = "SELECT * FROM relations WHERE `from` = ? AND `to` = ?";
            $query = "  SELECT 
                            r.*, DATE_FORMAT(r.update_at,'%d/%m/%Y %H:%i') as update_at, 
                            u.id as id_u, u.name as name_u, u.email as email_u, u.username as username_u, u.profile_image as profile_image_u,
                            u2.id as id_u2, u2.name as name_u2, u2.email as email_u2, u2.username as username_u2, u2.profile_image as profile_image_u2
                        FROM 
                            relations as r 
                            LEFT JOIN usuarios as u ON r.user_1 = u.id 
                            LEFT JOIN usuarios as u2 ON r.user_2 = u2.id 
                        WHERE 
                            (r.user_1 = ? AND r.user_2 = ?)
                            OR 
                            (r.user_2 = ? AND r.user_1 = ?)
                        
                        ";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(1, $this->__get('user1'));
            $stmt->bindValue(2, $this->__get('user2'));
            $stmt->bindValue(3, $this->__get('user1'));
            $stmt->bindValue(4, $this->__get('user2'));
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        public function getAll(){
            $query = "SELECT * FROM relations";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function createRelation(){
            $query = "INSERT INTO relations(`user_1`, `user_2`, surname_1, surname_2) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(1, $this->__get('user1'));
            $stmt->bindValue(2, $this->__get('user2'));
            $stmt->bindValue(3, $this->__get('surname_1'));
            $stmt->bindValue(4, $this->__get('surname_2'));
            $stmt->execute();
            return $this->db->lastInsertId();
        }

        public function update_at(){
            $query = "UPDATE `relations` SET `update_at` = CURRENT_TIMESTAMP WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(1, $this->__get('id'));
            $stmt->execute();
            return $this;
        }

    }

?>