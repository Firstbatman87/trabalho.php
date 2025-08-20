<?php
    session_start();
    if (!isset($_SESSION['pessoas'])) {
        $_SESSION['pessoas'] = [];
    }
    if (!isset($_SESSION['pessoas'])) {
        $_SESSION['pessoas'] = [];
    }
    if (empty($_SESSION['pessoas'])) {
        $dados = json_decode(file_get_contents("pessoas.json"), true);
        $_SESSION['pessoas'] = $dados;
    }
    $id_edicao = null;
    $nome_edicao = '';
    $senha_edicao = '';
    $modo_edicao = false;
    //coração do CRUD
    //DELETE via GET
    if (isset($_GET['acao']) && $_GET['acao'] == 'deletar' && isset($_GET['id'])) {
        $id_para_deletar = $_GET['id'];
        foreach ($_SESSION['pessoas'] as $indice => $pessoa) {
            if ($pessoa['id'] == $id_para_deletar) {
                unset($_SESSION['pessoas'][$indice]);
                break;
            }
        }
        header('Location: index.php');
        exit;
    }
    //Preparar a edição
    if (isset($_GET['acao']) && $_GET['acao'] == 'editar' && isset($_GET['id'])) {
        $id_para_editar = $_GET['id'];
        foreach ($_SESSION['pessoas'] as $pessoa) {
            if ($pessoa['id'] == $id_para_editar) {
                $id_edicao = $pessoa['id'];
                $nome_edicao = $pessoa['nome'];
                $senha_edicao = $pessoa['senha'];
                $modo_edicao = true; //ativa a edicao no form
                break;
            }
        }
    }
    //criar e atualizar via POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nome = $_POST['nome'];
        $senha = $_POST['senha'];
        //atualizar
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $id_para_atualizar = $_POST['id'];
            foreach ($_SESSION['pessoas'] as $indice => $pessoa) {
                if ($pessoa['id'] == $id_para_atualizar) {
                    $_SESSION['pessoas'][$indice]['nome'] = $nome;
                    $_SESSION['pessoas'][$indice]['senha'] = $senha;
                    break;
                }
            }
        }
        //criar
        else {
            $nova_pessoa = [
                'id' => uniqid(),
                'nome' => $nome,
                'senha' => $senha,
            ];
            $_SESSION['pessoas'][] = $nova_pessoa;
        }
        header('Location: index.php');
        exit;
    }
?>
<DOCTYPE html>
    <html lang="pt-br">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>CRUD - PHP/Array</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                h1, h2 {color: #333; }
                .container { max-width: 800px; margin: auto; }
                form { margin-bottom: 20px; padding: 20px; border: 1px solid #ccc; border-radius: 5px; }
                form div { margin-bottom: 10px; }
                label { display: block; margin-bottom: 5px; }
                input[type="text"], input[type="email"] { width: calc(100% - 16px); padding: 8px; border: 1px solid #ccc; border-radius: 3px; }
                button { padding: 10px 15px; background-color: #28a745; color: white; border: none; border-radius: 3px; cursor: pointer; }
                button.update { background-color: #007bff; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                a { color: #007bff; text-decoration: none; }
                a.delete { color: #dc3545; margin-left: 10px; }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>CADASTRO DE CONTA (YOUTUBE)</h1>
                <form action="index.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $id_edicao; ?>">
                    <div>
                        <label for="nome">Nome:</label>
                        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome_edicao); ?>" required>
                    </div>
                    <div>
                        <label for="senha">Senha:</label>
                        <input type="senha" id="senha" name="senha" value="<?php echo htmlspecialchars($senha_edicao); ?>" required>
                    </div>
                    <div>
                        <?php if ($modo_edicao): ?>
                            <button type="submit" class="update">Atualizar pessoa</button>
                        <?php else: ?>
                            <button type="submit">Adicionar pessoa</button>
                        <?php endif; ?>
                    </div>
                </form>
                <a href="gravar.php">Gravar dados...</a>
                <h2>Pessoas Cadastradas</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Senha</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($_SESSION['pessoas'])): ?>
                            <tr>
                                <td colspan="4">Nenhuma pessoa cadastrada!</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($_SESSION['pessoas'] as $pessoa): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($pessoa['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($pessoa['senha']); ?></td>
                                    <td>
                                        <a href="index.php?acao=editar&id=<?php echo $pessoa['id']; ?>">Editar</a>
                                        <a href="index.php?acao=deletar&id=<?php echo $pessoa['id']; ?>" class="detele" onclick="return confirm('Tem certeza que deseja excluir esta pessoa?')">Deletar</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                    </tbody>
                </table>
                <a href="videos.php">
                    <button>Entrar</button>
                </a>
            </div>
        </body>
    </html>