<?php

namespace App\Controllers;
 
//os recursos do miniframework

use MF\Controller\Action;
use MF\Model\Container;

class MsgController extends Action {


    public function message(){
        $this->validaAutenticacao();
        $action = $_POST['action'] ?? "";
        $message = Container::getModel('Message');
        switch($action){
            case 'send':
                $message = Container::getModel('Message');
                $relation = Container::getModel('Relations');
                $relation->__set('user1', $_SESSION['id']);
                
                $message->__set('from', $_SESSION['id']);
                $message->__set('to', $_POST['id_to']);
                $message->__set('type_msg', 1);
                $message->__set('message', $_POST['msg']);
                
                $message->send_msg();
                
                $relation->__set('user1', $_SESSION['id']);
                $relation->__set('user2', $_POST['id_to']);
                $lastId = $relation->getRelationBy2()['id'] ?? "";
                // echo "<pre>";
                // print_r($relation->getRelationBy2() ?? "Nada");
                // echo "</pre>";
                if(!$relation->getRelationBy2()){
                    $lastId = $relation->createRelation();
                }
                $relation->__set('id', $lastId);
                $relation->update_at();
                header("Location: /chat_list?id_to=".$_POST['id_to']);
                break;


            case 'edit':
                $message->__set('id', $_POST['id_message']);
                $message->__set('message', $_POST['msg']);
                if($message->getMessageBy('id')[0]['from'] == $_SESSION['id']){
                    $message->edit_message();
                    header("Location: /chat_list?id_to={$_POST['id_to']}");
                } else {
                    header('Location: /?login=not_auth');   
                }
                break;


            case 'del':
                $message->__set('id', $_POST['id']);
                // die();
                if($message->getMessageBy('id')[0]['from'] == $_SESSION['id']){
                    $message->del_message();
                    header("Location: /chat_list?id_to={$_POST['id_to']}");
                } else {
                    header('Location: /?login=not_auth');   
                }
                break;
        }

    }
}


?>