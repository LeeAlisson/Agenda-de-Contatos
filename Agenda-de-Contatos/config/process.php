<?php

    session_start();

    include_once "connection.php";
    include_once "url.php";

    $data = $_POST;
    $id;

    if(!empty($data)){ // Inserir / alterar dados

        if($data["type"] === "create"){ // Inserir

            $name = $data["name"];
            $phone = $data["phone"];
            $observations = $data["observations"];

            $query = "INSERT INTO contacts (name, phone, observations) VALUES (:name, :phone, :observations)";

            $stmt = $conn->prepare($query);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":phone", $phone);
            $stmt->bindParam(":observations", $observations);

            try {

                $stmt->execute();
                $_SESSION["msg"] = "Contato criado com sucesso!";
        
            } catch(PDOException $e) {
                // erro na conexão
                $error = $e->getMessage();
                echo "Erro: $error";
            }
        } else if($data["type"] === "edit"){ //Atualizar

            $id = $data["id"];
            $name = $data["name"];
            $phone = $data["phone"];
            $observations = $data["observations"];

            $query = "UPDATE contacts SET name = :name, phone = :phone, observations = :observations WHERE id = :id";

            $stmt = $conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":phone", $phone);
            $stmt->bindParam(":observations", $observations);

            try {

                $stmt->execute();
                $_SESSION["msg"] = "Contato atualizado com sucesso!";
        
            } catch(PDOException $e) {
                // erro na conexão
                $error = $e->getMessage();
                echo "Erro: $error";
            }
        } else if($data["type"] === "delete"){

            $id = $data["id"];

            $query = "DELETE FROM contacts WHERE id = :id";

            $stmt = $conn->prepare($query);
            $stmt->bindParam(":id", $id);
            
            try {

                $stmt->execute();
                $_SESSION["msg"] = "Contato deletado com sucesso!";
        
            } catch(PDOException $e) {
                // erro na conexão
                $error = $e->getMessage();
                echo "Erro: $error";
            }
        }

        header("Location:" . $BASE_URL . "../index.php");
    
    } else{ // Selecionar dados

        if(!empty($_GET)){
            $id = $_GET["id"];
        }
    
        // retorna dado de um contato
        if(!empty($id)){
    
            $query = "SELECT * FROM contacts WHERE id = :id";
    
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
    
            $contact = $stmt->fetch();
    
        } else{
    
        // retorna todos os contatos
        $contacts = [];
    
        $query = "SELECT * FROM contacts";
    
        $stmt = $conn->prepare($query);
        $stmt->execute();
        
        $contacts = $stmt->fetchAll();
        }
    }

    $conn = null; // Fechar conexão