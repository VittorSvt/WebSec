# Lab 01 — SQL Injection

## Objetivo

Compreender como a concatenação insegura de entradas fornecidas pelo usuário em consultas SQL pode causar uma vulnerabilidade de SQL Injection, e como mitigar esse problema utilizando consultas preparadas com PDO.

## Cenário

A aplicação utiliza PHP e MariaDB para consultar registros fictícios de usuários. O laboratório possui duas implementações:

- `vulnerable/`: versão com consulta SQL vulnerável.
- `secure/`: versão corrigida com consulta preparada.

## Laboratório vulnerável

No arquivo `vulnerable/index.php`, a consulta é construída da seguinte maneira:

```php
$sql = "SELECT id, username, email FROM users WHERE username = '$search'";
$stmt = $pdo->query($sql);
$users = $stmt->fetchAll();
```

Quando o usuário informa um nome como `luket`, o valor é armazenado na variável `$search` e inserido diretamente na consulta SQL. O banco filtra os registros pelo campo `username` e retorna os dados correspondentes.

O problema é que a entrada é concatenada à instrução SQL sem separação entre os dados e o código SQL. Isso permite que uma entrada maliciosa altere a lógica da consulta.

### Exploração

Foi utilizado o seguinte payload no campo de busca:

```sql
' OR '1'='1' -- 
```

O payload funciona da seguinte forma:

1. A primeira aspa simples fecha a string que deveria conter o nome de usuário.
2. `OR '1'='1'` adiciona uma condição sempre verdadeira à consulta.
3. `-- ` inicia um comentário SQL no MariaDB, ignorando o restante da instrução.

Como resultado, a condição do `WHERE` passa a ser verdadeira para todas as linhas, e a consulta retorna todos os usuários da tabela.

**Impacto observado:** a busca deixa de restringir os resultados ao usuário solicitado e expõe todos os registros disponíveis nessa consulta.

## Correção

A vulnerabilidade foi corrigida em `secure/index.php` utilizando uma consulta preparada com PDO:

```php
$stmt = $pdo->prepare(
    'SELECT id, username, email FROM users WHERE username = :username'
);

$stmt->execute(['username' => $search]);
$users = $stmt->fetchAll();
```

Com `prepare()`, a instrução SQL é definida separadamente dos dados. O método `execute()` envia o valor de `$search` como parâmetro, impedindo que ele seja interpretado como parte da estrutura SQL.

Dessa forma, o payload é tratado como um valor literal de busca, e não como código SQL.

## Testes

Os testes foram realizados localmente nas versões vulnerável e segura, utilizando os mesmos tipos de entrada.

| Entrada | Versão vulnerável | Versão segura |
|---|---|---|
| Nome inexistente (`Tulio`) | Nenhum usuário encontrado | Nenhum usuário encontrado |
| Nome existente (`luket`) | Dados do usuário retornados | Dados do usuário retornados |
| `' OR '1'='1' -- ` | Todos os registros retornados | Nenhum usuário encontrado |

Os resultados demonstram que a versão vulnerável permite alterar a lógica da consulta por meio de uma entrada manipulada. Na versão segura, a mesma entrada é tratada como um valor de busca e não retorna todos os registros.

## Conclusão

O laboratório demonstrou como a concatenação direta de entradas do usuário em consultas SQL pode permitir SQL Injection.

A utilização de consultas preparadas com PDO mitigou a vulnerabilidade testada ao separar a instrução SQL dos valores fornecidos pelo usuário.

## Referências

- OWASP — SQL Injection: https://owasp.org/www-community/attacks/SQL_Injection
- PHP — PDO::prepare(): https://www.php.net/manual/en/pdo.prepare.php
- PHP — PDOStatement::execute(): https://www.php.net/manual/en/pdostatement.execute.php
