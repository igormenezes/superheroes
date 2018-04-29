PHP | Laravel | Unit Test 

SuperHeroes - Projeto de cadastro de heróis, onde cada herói pode ter mais de uma foto, além de outras informações.

1 - Instalar as dependencias:

composer install

2 - (IMPORTANTE!!) Criar o arquivo .env (na raiz) ou configurar o arquivo /config/database.php com os dados do seu banco de dados local, para poder rodar o projeto.

3 - Editar o arquivo .env.testing com os dados do banco de dados de teste, para poder rodar os testes automatizados.

4 - Rodar as migrations para os dois ambientes:

php artisan migrate

php artisan migrate --env=testing

5 - Rodar a aplicação:

php artisan serve

6 - Acessar: 

http://localhost:8000/
