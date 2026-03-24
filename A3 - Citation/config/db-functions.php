<?php
    declare(strict_types= 1);
    require_once __DIR__ . '/db-config.php'; 
    $sql="SELECT * FROM `citation`";

    //Interact with database
    function db_get_authors(): array{
        global $pdo;
        //echo $pdo ? "Not null":"Null";
        $stmt = $pdo->query("SELECT * FROM auteur");
        //$pdoStatement=$pdo->query($sql);
        return $stmt->fetchAll();
    }

    function db_get_authors_id(string $firstName, string $lastName): ?int{
        global $pdo;
        $sql = "SELECT idAuteur FROM auteur WHERE LOWER(firstName)=LOWER(:fn) AND LOWER(lastName)=LOWER(:ln) LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':fn' => $firstName, ':ln' => $lastName]);
        $row = $stmt->fetch();
        return $row ? (int)$row['idAuteur'] : null;
    }

    function db_insert_author(string $firstName, string $lastName, ?string $birthDate): int{
        global $pdo;
        $sql = "INSERT INTO auteur (firstName, lastName, birthDate) VALUES (:fn, :ln, :bd)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':fn' => $firstName, ':ln' => $lastName, ':bd' => $birthDate]);
        return (int)$pdo->lastInsertId();
    }

    function db_insert_citation(string $login, string $texte, ?string $dateCitation, int $idAuteur): int
    {
    global $pdo;
    $sql = "INSERT INTO citation(login, texte, dateCitation, idAuteur) VALUES (:login, :texte, :dc, :ida)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':login' => $login,
        ':texte' => $texte,
        ':dc' => $dateCitation,
        ':ida' => $idAuteur
    ]);
    return (int)$pdo->lastInsertId();
    }


    //other solution
    /*echo "</br></br>";
    $pdoStatement = $pdo->query($sql);
    while ($row = $pdoStatement->fetch())
    echo "<br>\nFirstName = ". $row['first_name'] . "</br>" .
    " LastName = " . $row['last_name'] ."</br>" .
    " Mail = " . $row['mail'] . "/<br>" .
    "</br>" ;*/
?>
