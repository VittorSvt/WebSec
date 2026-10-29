
<?php

require __DIR__ . '/../../../src/db.php';

$search = $_GET['username'] ?? '';

$users = [];

if ($search !== '') {
    $sql = "SELECT id, username, email FROM users WHERE username = '$search'";
    $stmt = $pdo->query($sql);
    $users = $stmt->fetchAll();
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebSecLab - SQL Injection</title>

    <link rel="stylesheet" href="/style.css">
</head>
<body>

    <header>
        <h1>WebSecLab - SQL Injection</h1>
        <p>Laboratório de segurança web</p>
    </header>

    <main>
        <section>
            <h2>Buscar usuário</h2>

            <form method="GET">
                <label for="username">Nome de usuário:</label>

                <input
                    type="text"
                    id="username"
                    name="username"
                    value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>"
                >

                <button type="submit">Buscar</button>
            </form>
        </section>

        <?php if ($users): ?>

            <section>
                <h2>Resultados da busca</h2>

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome de usuário</th>
                            <th>E-mail</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td>
                                    <?= htmlspecialchars((string) $user['id'], ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>

        <?php elseif ($search !== ''): ?>

            <section>
                <p>Nenhum usuário encontrado.</p>
            </section>

        <?php endif; ?>

    </main>

</body>
</html>
