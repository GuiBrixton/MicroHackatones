# MicroHackatones - Caso 4 - Jenkins Templating

## Descripción

En la actualidad, una de las prácticas más comunes en el mundo del desarrollo es la creación de ***Microservicios***. El equipo de arquitectura valida las tecnología a usar en los ***Microservicios*** y crea el esqueleto. Una vez se libera a los desarrolladores, estos se vuelven locos y empiezan a crear ***microservicios*** como si no hubiera un mañana, y además… quieren Pipelines para todos los Micros de forma inmediata.

### Pasos del caso de uso

1. Es necesario en Jenkins crear un sistema de gobernanza ***utilizando el plugin Jenkins Templating Engine (JTE)***.
2. Habrá ***dos tipos de pipelines***, uno de **Java para el backend** y otro de ***NodeJS (puede ser Angular) para el Frontend***.
3. Los pipelines serán sencillos, ***tienen que tener tres stages con sus respectivos "echos" indicando el nombre del stage***.
4. Además, ***deben imprimir una variable (configServer, con el valor que queráis) en el stage de Deploy que tiene que ser definida a nivel de gobernanza***.
5. Estos serán los stages:
   - Checkout
   - Build
   - Deploy
6. Ya que habrá un sistema de Gobernanza para los pipelines, ***lo ideal sería que la lógica de los stages estuvieran en unas librerías*** que se pudieran compartir, por si en un futuro se pueden reutilizar en otros pipelines. Además, de esta forma, podemos cambiar la lógica para los 400 microservicios y 50 frontends que se van a desplegar, si tener que modificar 450 Jenkinsfile.

### Tips

- JTE
- pipeline_config.groovy
- Shared Libraries

## Pre-requisitos

- Disponer de un servicio Jenkins
- Repositorio GIT donde se definirán las librerías

## Procedimiento

### 1. Instalando *Jenkins Template Engine*

*Jenkins Templating Engine* (JTE), se encuentra como *Templating Engine* en Jenkins en `Administrar Jenkins > Plugins`

Si no esta instalado seleccionar `Available plugins`, marcarlo y `botón Install`, esto instalar el plugin y sus dependencias. Reiniciar Jenkins en caso de ser necesario

### 2. Validando la instalación

Para llevar a cabo una validación simple de la instalación  creamos un *Pipeline JTE*

- Desde la página de inicio de Jenkins, `+ Nueva Tarea` en el menú de navegación de la izquierda.
- Establecemos un nombre para la tarea, ***validarJTE***, por ejemplo
- Seleccionamos el tipo `Pipeline`
- Pulsamos el botón `OK`  en la parte inferior

A continuación configuramos el *Pipeline*

Por defecto en la sección `Pipeline` `Definition` aparecerá  con el valor `Jenkins Template Engine`, eso confirmará ña correcta configuración del plugin.

- Activar la casilla `Provide default pipeline template (Jenkinsfile)`
- E introducir en el área de texto Jenkinsfile el siguiente código

   ```GROOVY
   podTemplate(yaml: '''
      apiVersion: v1
      kind: Pod
      spec:
      containers:
      - name: maven
         image: maven:latest
         command: ["/bin/sh"]
         args: ["-c", "sleep 1000"]

      '''){
      node(POD_LABEL) {
         container('maven') {
            sh 'mvn -v'
         }
      }
   }
   ```

- Solo nos queda probar el *Pipeline*

  ```TEXT
  ...
   Apache Maven 3.9.6 (bc0240f3c744dd6b6ec2920b3cd08dcc295161ae)
   Maven home: /usr/share/maven
   Java version: 21.0.2, vendor: Eclipse Adoptium, runtime: /opt/java/openjdk
   Default locale: en_US, platform encoding: UTF-8
   OS name: "linux", version: "5.15.0-92-generic", arch: "amd64", family: "unix"
   [Pipeline] }
   [Pipeline] // container
   [Pipeline] }
   [Pipeline] // node
   [Pipeline] }
   [Pipeline] // podTemplate
   [Pipeline] End of Pipeline
   Finished: SUCCESS
  ```

Siguientes pasos <https://jenkinsci.github.io/templating-engine-plugin/2.5.3/tutorials/jte-the-basics/1-prerequisites/>

## Referencias
