
<?php

require_once __DIR__ . '/../src/db.php';

$stmt = $pdo->prepare(
    'SELECT id, username, email FROM users ORDER BY id'
);

$stmt->execute();
$users = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WebSecLab</title>
</head>
<body>
    <h1>WebSecLab</h1>
    <p>Laboratório local de segurança web.</p>

    <h2>Usuários fictícios</h2>

    <?php if (count($users) > 0): ?>
        <ul>
            <?php foreach ($users as $user): ?>
                <li>
                    <?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?>
                    —
                    <?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Nenhum usuário cadastrado.</p>
    <?php endif; ?>
</body>
</html>
