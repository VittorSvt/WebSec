# WebSecLab — Comandos importantes do Docker

## 1. Iniciar o projeto

Inicia os containers e cria os que ainda não existem.

```bash
docker compose up -d
```

## 2. Atualizar/reconstruir as imagens e iniciar

**Comando principal para reconstruir a imagem da aplicação web.**

```bash
docker compose up --build -d
```

Use quando alterar o `Dockerfile` ou precisar reconstruir a imagem da aplicação.

## 3. Parar os containers

```bash
docker compose stop
```

Para iniciar novamente depois:

```bash
docker compose start
```

## 4. Derrubar os containers

```bash
docker compose down
```

Remove os containers e a rede do projeto. **Preserva o volume do banco de dados.**

Para subir novamente:

```bash
docker compose up -d
```

## 5. Ver o estado dos containers

```bash
docker compose ps
```

Mostra os serviços, o estado e as portas utilizadas.

## 6. Ver os logs

Todos os serviços:

```bash
docker compose logs
```

Acompanhar os logs em tempo real:

```bash
docker compose logs -f
```

Somente a aplicação PHP/Apache:

```bash
docker compose logs -f web
```

Somente o MariaDB:

```bash
docker compose logs -f db
```

Para sair do acompanhamento dos logs, pressione `Ctrl + C`. Isso não para os containers.

## 7. Entrar no container da aplicação

```bash
docker compose exec web bash
```

Para sair do container:

```bash
exit
```

## 8. Entrar no container do banco de dados

```bash
docker compose exec db bash
```

Para sair:

```bash
exit
```

## 9. Atualizar a imagem de um serviço

Baixar a versão da imagem configurada no `compose.yaml`:

```bash
docker compose pull db
```

Recriar/iniciar o serviço usando a imagem baixada:

```bash
docker compose up -d db
```

Para atualizar todas as imagens dos serviços:

```bash
docker compose pull
docker compose up -d
```

Atenção: isso baixa as imagens configuradas; não significa necessariamente instalar a versão mais recente disponível. Para isso, confira a tag definida no `compose.yaml`.

## 10. Consultar imagens e volumes

Listar imagens locais:

```bash
docker images
```

Listar volumes:

```bash
docker volume ls
```

Listar containers, inclusive os parados:

```bash
docker ps -a
```

## 11. Reconstruir apenas a aplicação web

```bash
docker compose build web
docker compose up -d web
```

Útil quando você altera o `Dockerfile` ou precisa reconstruir a imagem do PHP/Apache sem reconstruir os outros serviços.

## 12. Atualizar os dados do banco MariaDB

Os arquivos `database/schema.sql` e `database/seed.sql` são executados automaticamente na inicialização de um banco novo, não toda vez que o container reinicia.

Para executar manualmente o `seed.sql` no banco existente:

```bash
docker compose exec -T db sh -lc 'mariadb -u"$MARIADB_USER" -p"$MARIADB_PASSWORD" "$MARIADB_DATABASE"' < database/seed.sql
```

Atenção: esse comando executa o SQL novamente. Se o arquivo contiver `INSERT`, os registros poderão ser adicionados novamente ou gerar erros de duplicidade. Ele não substitui automaticamente os registros antigos.

## 13. Apagar os containers e recriá-los

```bash
docker compose down
docker compose up --build -d
```

Isso recria os serviços, mas preserva o volume do banco de dados.

## 14. Reiniciar um serviço específico

Aplicação web:

```bash
docker compose restart web
```

Banco de dados:

```bash
docker compose restart db
```

## 15. Verificar se o Docker está funcionando

Versão do Docker:

```bash
docker --version
```

Versão do Docker Compose:

```bash
docker compose version
```

Verificar o estado geral:

```bash
docker info
```

## 16. Apagar tudo e recriar o banco do zero — CUIDADO!

```bash
docker compose down -v
docker compose up --build -d
```

**PERIGO:** a opção `-v` remove os volumes declarados pelo projeto. No WebSecLab, isso pode apagar permanentemente os dados armazenados no volume `mariadb_data`.

Ao iniciar novamente, o MariaDB cria um banco novo e executa os scripts de inicialização. Use somente se realmente quiser perder os dados atuais.

---

# Resumo rápido

| O que quero fazer | Comando |
|---|---|
| Iniciar o projeto | `docker compose up -d` |
| Reconstruir e iniciar | `docker compose up --build -d` |
| Parar containers | `docker compose stop` |
| Iniciar containers parados | `docker compose start` |
| Remover containers sem apagar o banco | `docker compose down` |
| Ver estado | `docker compose ps` |
| Ver logs | `docker compose logs -f` |
| Reiniciar a aplicação | `docker compose restart web` |
| Reconstruir só a aplicação | `docker compose build web` |
| Baixar imagens configuradas | `docker compose pull` |
| Entrar no container web | `docker compose exec web bash` |
| Listar volumes | `docker volume ls` |

**Regra prática para o WebSecLab:**

- Alterou PHP, HTML ou CSS em pastas montadas como volumes? Normalmente, basta atualizar a página.
- Alterou o `Dockerfile`? Use `docker compose up --build -d`.
- Alterou o `compose.yaml`? Execute `docker compose up -d` para aplicar as mudanças; use `--build` se também mudou a imagem.
- Alterou o `seed.sql`? Execute o SQL manualmente no banco existente, considerando se quer adicionar ou substituir registros.
- Quer apagar e recriar o banco? Faça backup antes de qualquer operação destrutiva.

Execute esses comandos no terminal, dentro da pasta que contém o arquivo `compose.yaml`.
