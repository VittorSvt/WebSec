<?php

require __DIR__ . '/../../../src/db.php';

$search = $_GET['username'] ?? '';

$users = [];

if ($search !== '') {
    $sql = "SELECT id, username, email FROM users WHERE username = '$search'";
    $stmt = $pdo->query($sql);
    $users = $stmt->fetchAll();
}

echo "<h1 style='color: purple;'>WebSecLab - SQL Injection</h1>";

echo '<form method="GET">';
echo '<label for="username" style="background-color: #222; color: white; padding: 2px;">Nome de usuário:</label>';
echo '<input type="text" id="username" name="username">';
echo '<button type="submit">Buscar</button>';
echo '</form>';

if ($users) {
    echo '<h2>Resultados da busca</h2>';
    echo '<table>';
    echo '<thead>';
    echo '<tr>';
    echo '<th style="background-color: #222; color: white;">ID</th>';
    echo '<th style="background-color: #222; color: white;">Nome de usuário</th>';
    echo '<th style="background-color: #222; color: white;">E-mail</th>';
    echo '</tr>';
    echo '</thead>';

    echo '<tbody>';

    foreach ($users as $user) {
        echo '<tr>';

        echo '<td style="border: 1px solid #000000; padding: 15px; color: purple">';
        echo htmlspecialchars((string) $user['id'], ENT_QUOTES, 'UTF-8');
        echo '</td>';

        echo '<td style="border: 1px solid #000000; padding: 15px; color: purple">';
        echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8');
        echo '</td>';

        echo '<td style="border: 1px solid #000000; padding: 15px; color: purple">';
        echo htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8');
        echo '</td>';

        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
} elseif ($search !== '') {
    echo '<p>Nenhum usuário encontrado.</p>';
}
