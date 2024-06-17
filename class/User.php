<?php

class User
{
    private int $id;
    private string $vorNachname;
    private string $userName;
    private string $email;
    private string $pwhash;

    public function __construct(int $id, string $vorNachname, string $userName, string $email, string $pwhash)
    {
        $this->id = $id;
        $this->vorNachname = $vorNachname;
        $this->userName = $userName;
        $this->email = $email;
        $this->pwhash = $pwhash;
    }

    public static function dbcon(): PDO
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "investUser";

        try {
            $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public static function create(string $vorNachname, string $userName, string $password, string $email): User
    {
        try {
            $con = self::dbcon();
            $sql = 'INSERT INTO user (VorNachname, userName, email, pwhash) VALUES (:vorNachname, :userName, :email, :pwhash)';
            $stmt = $con->prepare($sql);
            $pwhash = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bindParam(':vorNachname', $vorNachname);
            $stmt->bindParam(':userName', $userName);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':pwhash', $pwhash);
            $stmt->execute();
            return self::findById($con->lastInsertId());
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public static function findById(int $id): User
    {
        $con = self::dbcon();
        $sql = 'SELECT * FROM user WHERE id = :id';
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return new User($result['id'], $result['VorNachname'], $result['userName'], $result['email'], $result['pwhash']);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getVorNachname(): string
    {
        return $this->vorNachname;
    }

    public function setVorNachname(string $vorNachname): void
    {
        $this->vorNachname = $vorNachname;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPwhash(): string
    {
        return $this->pwhash;
    }

    public function setPwhash(string $pwhash): void
    {
        $this->pwhash = $pwhash;
    }

}

