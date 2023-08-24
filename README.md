
# Sistema de Gestión de Citas Médicas


Repositorio con el código fuente de las APIs desarrolladas par el consultorio odontológico OdontoArias




## Uso del código

Descargar [ZIP](https://github.com/jhon-torres/EndPoints_CO/archive/refs/heads/master.zip)

```bash
  composer install 
  cp .env.example .env 
  php artisan key:generate
```
Posterior configurar las variables de entorno.

A continuación, configurar la Base de Datos con nombre especificado en las variables
```bash
  php artisan migrate 
  npm install   
```
    
## Despliegue del Sistema Backend en Railway
 - Acceder al panel de **[Railway.app](https://railway.app/)** 
 - Crear **nuevo proyecto**
 - Creado el proyecto, crear un **nuevo servicio**
 - Elegir la opción **GitHub Repo**
 - Iniciar y autorizar permisos de acceso a cuenta de GitHub
 - Seleccionar el repositorio a desplegar, proyecto Laravel
 - Una vez cargado el servicio, seleccionar la opción **Variables**
 - Seleccionar **RAW Editor** y configurar las variables de entorno necesarias del proyecto


## Autores

- Backend [@Jhon Torres](https://github.com/jhon-torres) 
- Frontend [@Lesly Herrera](https://github.com/Lesly-liseth) ***[Repositorio](https://github.com/Lesly-liseth/Odontoarias)***
- App Móvil [@Mayra Ñaupari](https://github.com/mayP2201) ***[Repositorio](https://github.com/mayP2201/ConsultorioOdont)***

