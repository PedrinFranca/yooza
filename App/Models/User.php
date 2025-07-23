<?php 

    namespace App\Models;

    use MF\Model\Model;

    class User extends Model {
        private $id;
        private $name;
        private $email;
        private $password;
        private $username;

        public function __get($attr){
            return $this->$attr;
        }
        public function __set($attr, $value){
            $this->$attr = $value;
        }

        public function register(){
            $query = "INSERT INTO usuarios(name, email, password, username) VALUES(:name, :email, :password, :username)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':name', $this->__get('name'));
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->bindValue(':password', $this->__get('password'));
            $stmt->bindValue(':username', $this->__get('username'));
            $stmt->execute();
            return $this;

        }

        public function validedRegister(){
            $return = ['return' => true, 'error' => ""];
            $regex_space = "/\s/";
            if(preg_match($regex_space, $this->__get('password'))){
                $return['return'] = false;
                $return['error'] = 'password';
            }
            if(preg_match($regex_space, $this->__get('username'))){
                $return['return'] = false;
                $return['error'] = 'username';
            }
            return $return;
        }

        public function getUserBy($selector){
            $query = "SELECT * FROM usuarios WHERE $selector = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(1, $this->__get($selector));
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);

        }

        public function login(){
            $query = "SELECT * FROM usuarios WHERE email = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(1, $this->__get('email'));
            $stmt->execute();

            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if($user && password_verify($this->__get('password'), $user['password'])){
                $this->__set('name', $user['name']);
                $this->__set('id', $user['id']);
                return $user;
            }
            return false;

        }

        public function getAll(){
            $query = "SELECT * FROM usuarios WHERE username LIKE :username AND id != :id_usuario";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':username', '%'.$this->__get('username').'%');
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }


    }

?>