# MicroHackatones - Caso 2 - Helm Chart Web

## Descripción

Vamos a crear un ***Helm Chart*** para desplegar una aplicación web llamada ***WebAppX***, donde ***X*** será un número cualquiera a elegir por nosotros mismos.

La aplicación es simple y consta de un servidor web que sirve una página **HTML** básica que podemos encontrar en esta imagen:

<https://github.com/cloudogu/hello-k8s> **¡USA ESTA IMAGEN!**

### Requisitos de la Aplicación

- Servidor Web:
  - La aplicación utiliza un servidor web <https://github.com/cloudogu/hello-k8s>
  - Haced ***Port Forwarding*** para ver la ver la web
  - Página HTML:
    La imagen ya viene con un servidor web y un contenido customizable

- Requisitos de Chart de Helm:
  - Valores Configurables:
    - Nombre de la aplicación.
    - Puerto donde escucha la aplicación.
    - Mensaje (mirar documentación de la imagen ***hello-k8s***)
    - Posibilidad de cambiar el número de replicas.

Una vez desplegada la primera aplicación, desplegar 2 más con el mismo Chart solo cambiando el nombre, el mensaje y el puerto de la aplicación.

## Tips

- `helm create -f`

## Procedimiento

- Crear contenedor con la aplicación indicada
- Crear un despliegue en Kubernetes
- Crea el helm charm solicitado
- Desplegar las distintas aplicaciones

## Crear contenedor con la aplicación indicada

Crear imagen de contenedor con la aplicación indicada preparando las variables de entorno necesarias para realizar el *custom* de la aplicación

## Crear un despliegue en Kubernetes

Crear un despliegue en Kubernetes con la imagen anterior, comprobar que se comporta de la forma esperada al recibir las variables de entorno.

## Desplegar las distintas aplicaciones

Desplegar las distintas aplicaciones, `helm apply`

## Conclusiones

## Extra

## Referencias

- [Github](https://github.com/cloudogu/hello-k8s)
