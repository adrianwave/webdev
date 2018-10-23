<?php
    class Account {

        private $con;
        private $errorArray;

        public function __construct($con) {
            $this->con = $con;
            $this->errorArray = [];
        }

        public function register($username, $firstName, $lastName,
        $email, $email2, $password, $password2) {
            // Validation
            $this->validateUsername($username);
            $this->validateFirstName($firstName);
            $this->validateLastName($lastName);
            $this->validateEmails($email, $email2);
            $this->validatePasswords($password, $password2);

            if(empty($this->errorArray) == true) {
                // Insert into db
                return $this->insertUserDetails($username, $firstName, $lastName, $email, $password);
            } else {
                return false;
            }
        }

        public function getError() {
            if(!empty($this->errorArray)) {
                echo "<ul>";
                foreach($this->errorArray as $error) {
                    echo "<li>$error</li>";
                }
                echo "</ul>";
            }
        }

        private function insertUserDetails($un, $fn, $ln, $em, $pw) {
            $encryptedPw = md5($pw);
            $profilePic = "assets/images/profile-pics/head_emerald.png";
            $date = date("Y-m-d");

            $result = mysqli_query($this->con, "INSERT INTO users VALUES (0, '$un', '$fn', '$ln', '$em', '$encryptedPw', '$date', '$profilePic')");

            return $result;
        }

        public function getInputValue($value) {
            if(isset($_POST[$value])) {
                echo $_POST[$value];
            }
        }

        private function validateUsername($un) {
            if(strlen($un) > 25 || strlen($un) < 5)  {
                array_push($this->errorArray, Constants::$usernameCharacters);
                return;
            }

            //TODO: check if username exists.
        }
    
        private function validateFirstName($fn) {
            if(strlen($fn) > 25 || strlen($fn) < 2)  {
                array_push($this->errorArray, Constants::$firstNameCharacters);
                return;
            }
        }
    
        private function validateLastName($ln) {
            if(strlen($ln) > 25 || strlen($ln) < 2)  {
                array_push($this->errorArray, Constants::$lastNameCharacters);
                return;
            }
        }
    
        private function validateEmails($em, $em2) {
            if($em != $em2)  {
                array_push($this->errorArray, Constants::$emailsDoNotMatch);
                return;
            }

            if(!filter_var($em, FILTER_VALIDATE_EMAIL)) {
                array_push($this->errorArray, Constants::$emailInvalid);
                return;
            }
        }

        //TODO: Check that email hasn't already been used.
    
        private function validatePasswords($pw, $pw2) {
            if($pw != $pw2)  {
                array_push($this->errorArray, Constants::$passwordsDoNotMatch);
                return;
            }

            if(preg_match('/[^A-Za-z0-9]/', $pw)) {
                array_push($this->errorArray, Constants::$passwordNotAConstants);
                return;
            }

            if(strlen($pw) > 30 || strlen($pw) < 5) {
                array_push($this->errorArray, Constants::$passwordCharacters);
                return;
            }
        }
    }
?>