<?php

class Database
{
    private static $pdo;

    public static function conectar($dbname, $host, $user, $senha)
    {
        if (!isset(self::$pdo)) {
            try {
                self::$pdo = new PDO("mysql:dbname={$dbname};host={$host}", $user, $senha);
            } catch (PDOException $e) {
                echo "Erro ao se conectar ao banco: " . $e->getMessage();
            } catch (Exception $e) {
                echo "Erro: " . $e->getMessage();
            }
        }
        return self::$pdo;
    }
}
?>
