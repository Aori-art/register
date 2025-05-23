<?php

class database {
    function opencon(): PDO {
        return new PDO(
            'mysql:host=localhost;dbname=lms_app',
            'root',
            ''
        );
    }

    function signupUser($user_FN, $user_LN, $user_birthday, $user_email, $user_sex, $user_phone, $user_username, $user_password, $profile_picture_path) {
        $con = $this->opencon();
        try {
            $con->beginTransaction();

            $hashedPassword = password_hash($user_password, PASSWORD_DEFAULT);

            $stmt = $con->prepare("INSERT INTO Users (user_FN, user_LN, user_birthday, user_sex, user_email, user_phone, user_username, user_password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_FN, $user_LN, $user_birthday, $user_sex, $user_email, $user_phone, $user_username, $hashedPassword]);

            $userId = $con->lastInsertId();

            $stmt = $con->prepare("INSERT INTO users_pictures (user_id, user_pic_url) VALUES (?, ?)");
            $stmt->execute([$userId, $profile_picture_path]);

            $con->commit();
            return $userId;
        } catch (PDOException $e) {
            $con->rollBack();
            return false;
        }
    }

    function insertAddress($userID, $street, $barangay, $city, $province) {
        $con = $this->opencon();
        try {
            $con->beginTransaction();

            $stmt = $con->prepare("INSERT INTO Address (ba_street, ba_barangay, ba_city, ba_province) VALUES (?, ?, ?, ?)");
            $stmt->execute([$street, $barangay, $city, $province]);

            $addressId = $con->lastInsertId();

            $stmt = $con->prepare("INSERT INTO Users_Address (user_id, address_id) VALUES (?, ?)");
            $stmt->execute([$userID, $addressId]);

            $con->commit();
            return true;
        } catch (PDOException $e) {
            $con->rollBack();
            return false;
        }
    }

    function loginUser($user_username, $user_password) {
        $con = $this->opencon();
        $stmt = $con->prepare("SELECT * FROM Users WHERE user_username = ?");
        $stmt->execute([$user_username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($user_password, $user['user_password'])) {
            return $user;
        } else {
            return false;
        }
    }

    function addBook($title, $isbn, $pubYear, $quantity) {
    $con = $this->opencon();
    try {
        $stmt = $con->prepare("INSERT INTO books (book_title, book_isbn, book_pubyear, quantity_avail) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $isbn, $pubYear, $quantity]);
        return true;
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
     function insertAuthor($firstname, $lastname, $birthday, $nationality) {
        $con = $this->opencon();
        try {
            $con->beginTransaction();
    
            // Insert into Address table
            $stmt = $con->prepare("INSERT INTO authors (author_FN, author_LN, author_birthday, author_nat) VALUES (?, ?, ?, ?)") ;
            $stmt->execute([$firstname, $lastname, $birthday, $nationality]);
    
            // Get the newly inserted address_id
            $author_id = $con->lastInsertId();

            $con->commit();
            return $author_id;
        } catch (PDOException $e) {
            $con->rollBack();
            return false;
        }
    }
    function insertGenre($genrename) {
        $con = $this->opencon();
        try {
            $con->beginTransaction();
    
            // Insert into Address table
            $stmt = $con->prepare("INSERT INTO genres (genre_name) VALUES (?)") ;
            $stmt->execute([$genrename]);
    
            // Get the newly inserted address_id
            $genre_id = $con->lastInsertId();

            $con->commit();
            return $genre_id;
        } catch (PDOException $e) {
            $con->rollBack();
            return false;
        }
    }
}
?>
