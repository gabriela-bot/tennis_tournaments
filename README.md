
# Tennis Tournament
Este proyecto se desarrolló para gestionar torneos de tenis. Proporciona una API que permite interactuar con los torneos y jugadores. Además, se puede utilizar desde la consola para facilitar su uso en diferentes entornos.



## Teconologias usadas

**Laravel 11**: Framework PHP utilizado para desarrollar la API.

**PHP 8.4**: Lenguaje de programación utilizado.

**Docker**: Utilizado para contenerizar la aplicación.

**MySQL 8**: Base de datos para almacenar la información de los torneos y jugadores.



## Instalacion

1. Clonar el repositorio:

```bash
  git clone https://github.com/gabriela-bot/tennis_tournaments.git
  cd tennis_tournaments
```

2. Instalar y descargar los servicios correspondientes de Docker: Ejecuta el siguiente comando para descargar las imágenes necesarias y crear los contenedores:

```bash
  docker compose up --build -d
```

3. Asegúrate de que los servicios estén corriendo correctamente:

```bash
  docker compose ps
```

4. Acceder al servidor de Docker: Ingresa al contenedor de laravel para realizar la migración de la base de datos:

```bash
  docker compose exec -it app bash
```

5. Migrar la base de datos: Ejecuta el siguiente comando para migrar la base de datos:

```bash
  php artisan migrate
```

6. Acceder a la aplicación: La API estará disponible en http://localhost.





## Test

Para ejecutar los tests, primero asegúrate de que tienes una base de datos que puedas borrar y migrar muchas veces .
Por defecto esta conectada la base `test`. Si es diferente modifica el archivo `phpunit.xml`
Luego, ejecuta:

```bash
  docker compose exec app php artisan test
```


## Documentation

Puedes acceder a la documentación de la API a través del servicio Docker en la siguiente URL:

[Documentation](http://localhost/docs)


El archivo YAML que define la documentación de la API se encuentra en:

`public/tennis_tournament.yaml`


