<?php
    session_start();
    $dados = $_SESSION['pessoas'];
    $conteudo = json_encode($dados, JSON_PRETTY_PRINT);
    file_put_contents("pessoas.json", $conteudo);
    header("Locations: index.php");
?>