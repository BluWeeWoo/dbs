<?php

class database{

    function opencon(){
        return new PDO(
            'mysql:host=localhost;
            dbname=dbs_app',
            username: 'root',
            password: '');
    }

    function signupUser($firstname, $lastname ,$username, $email, $password) { 
        $con = $this->opencon();

        try{
    $con->beginTransaction();
    $stmt = $con->prepare("INSERT INTO Admin(admin_FN, admin_LN, admin_username, admin_email, admin_password) VALUES(?,?,?,?,?)");
    $stmt->execute([$firstname, $lastname ,$username, $email, $password]);
    $userID = $con-> lastInsertId();
    $con->commit();

    return $userID;

        }catch (PDOExecption $e){
            $con->rollBack();
            return false;
        }
    }
    
function isUsernameExists($username){
    $con = $this->opencon();
    $stmt = $con->prepare("SELECT COUNT(*) FROM Admin WHERE admin_username = ?");
    $stmt->execute([$username]);
    $count = $stmt->fetchColumn();
    return $count > 0;
}
function isCourseExists($course_name){
    $con = $this->opencon();
    $stmt = $con->prepare("SELECT COUNT(*) FROM courses WHERE course_name= ?");
    $stmt->execute([$course_name]);
    $count = $stmt->fetchColumn();
    return $count > 0;
}

function isEmailExists($email){
    $con = $this->opencon();
    $stmt = $con->prepare("SELECT COUNT(*) FROM Admin WHERE admin_email = ?");
    $stmt->execute([$email]);
    $count = $stmt->fetchColumn();
    return $count > 0;
}

function loginUser($username, $password){
    $con = $this->opencon();
    $stmt = $con->prepare("SELECT * FROM Admin WHERE admin_username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['admin_password'])) {
        return $user;
    } else {
        return false;
    }
}
function addStudent($firstname ,$lastname, $email, $admin_id) { 
        $con = $this->opencon();

        try{
    $con->beginTransaction();
    $stmt = $con->prepare("INSERT INTO Students (student_FN, student_LN, student_email, admin_id) VALUES(?,?,?,?)");
    $stmt->execute([$firstname, $lastname, $email, $admin_id]);
    $userID = $con-> lastInsertId();
    $con->commit();

    return $userID;

        }catch (PDOExecption $e){
            $con->rollBack();
            return false;
        }
    }
    
function addCourse($coursename, $admin_id) { 
        $con = $this->opencon();

        try{
    $con->beginTransaction();
    $stmt = $con->prepare("INSERT INTO Courses (course_name, admin_id) VALUES(?,?)");
    $stmt->execute([$coursename, $admin_id]);
    $userID = $con-> lastInsertId();
    $con->commit();

    return $userID;

        }catch (PDOExecption $e){
            $con->rollBack();
            return false;
        }
    }
}

