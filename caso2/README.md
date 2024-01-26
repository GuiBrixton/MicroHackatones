# MicroHackatones - Caso 2 - Helm Chart Web

## Descripción

Vamos a crear un ***Helm Chart*** para desplegar una aplicación web llamada ***WebAppX***, donde ***X*** será un número cualquiera a elegir por nosotros mismos.

La aplicación es simple y consta de un servidor web que sirve una página **HTML** básica que podemos encontrar en esta imagen:

<https://github.com/cloudogu/hello-k8s> **¡USA ESTA IMAGEN!**

### Requisitos de la Aplicación

- Servidor Web:
  - La aplicación utiliza un servidor web <https://github.com/cloudogu/hello-k8s>
  - Hacer ***Port Forwarding*** para ver la ver la web
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

- `helm create`
- `-f`

## Pre-requisitos

Partimos desde un entorno con los siguientes supuestos:

- Acceso a un nodo/cluster de ***Kubernetes***, donde aplicar el caso
- Tener instaladas las herramientas necesarias para la resolución del caso:
  - Docker, o gestor de contenedores, podman, rancher...
  - kubectl
  - helm

## Procedimiento

- Contenedor con la aplicación indicada
- Crear un despliegue en ***Kubernetes***
- Crea el helm charm solicitado
- Desplegar las distintas aplicaciones

## Contenedor con la aplicación indicada

Revisado el repositorio <https://github.com/cloudogu/hello-k8s>, se indica lo siguiente:

> - This container image can be deployed on a ***Kubernetes*** cluster. When accessed via a web browser on port `8080`, it will display:
>   - a default *Hello world!* message
>   - the pod name
>   - node os information
>
> - The default "Hello world!" message displayed can be overridden using the `MESSAGE` environment variable. The default port of 8080 can be overriden using the `PORT` environment variable
> - It is available on DockerHub as `paulbouwer/hello-kubernetes:1.8`

¡Bien! pues en primer lugar comprobaremos el funcionamiento de un contenedor a partir de esta imagen al que le pasaremos ya un puerto y un mensaje personalizado. Para ello ejecutamos

```BASH
docker run --rm -e "MESSAGE=¿Que hay de nuevo Viejo?" -e "PORT=8081" -p 8081:8081 --name WebApp1969 paulbouwer/hello-kubernetes:1.8
```

Accediendo mediante navegador a <http://localhost:8081> comprobamos que el resultado es el esperado. `^C` para terminar el contenedor

## Despliegue en ***Kubernetes***

Siguiendo con la revisión del repositorio <https://github.com/cloudogu/hello-k8s> ya se nos propone un ***Deploy*** para ***Kubernetes*** con variantes para personalizar tanto  el **mensaje** como el **puerto**. Tomando esto como referencia lo personalizamos un poco mas para incluir un ***namespace*** propio. Archivo `hello-kubernetes.yaml`

```YAML
# hello-kubernetes.yaml
apiVersion: v1
kind: Namespace
metadata:
  name: mh-caso2
---
apiVersion: v1
kind: Service
metadata:
  name: hello-kubernetes
  namespace: mh-caso2
spec:
  type: LoadBalancer
  ports:
  - port: 80
    targetPort: 8081
  selector:
    app: hello-kubernetes
---
apiVersion: apps/v1
kind: Deployment
metadata:
  name: hello-kubernetes
  namespace: mh-caso2
spec:
  replicas: 1
  selector:
    matchLabels:
      app: hello-kubernetes
  template:
    metadata:
      labels:
        app: hello-kubernetes
    spec:
      containers:
      - name: hello-kubernetes
        image: paulbouwer/hello-kubernetes:1.8
        ports:
        - containerPort: 8081
        env:
        - name: MESSAGE
          value: Desde Kubernetes, ¿Que hay de nuevo Viejo?
        - name: PORT
          value: "8081"
```

Aplicamos nuestra configuración y realizamos ***port-forward*** sobre el ***Service***

```BASH
kubectl apply -f hello-kubernetes.yaml
```

```TEXT
namespace/mh-caso2 created
service/hello-kubernetes created
deployment.apps/hello-kubernetes created
```

```BASH
kubectl -n mh-caso2 get all
```

```TEXT
NAME                                    READY   STATUS    RESTARTS   AGE
pod/hello-kubernetes-6969876487-j5zjg   1/1     Running   0          3m17s

NAME                       TYPE           CLUSTER-IP      EXTERNAL-IP   PORT(S)        AGE
service/hello-kubernetes   LoadBalancer   10.99.203.120   <pending>     80:31768/TCP   3m18s

NAME                               READY   UP-TO-DATE   AVAILABLE   AGE
deployment.apps/hello-kubernetes   1/1     1            1           3m18s

NAME                                          DESIRED   CURRENT   READY   AGE
replicaset.apps/hello-kubernetes-6969876487   1         1         1       3m17s
```

```BASH
kubectl -n mh-caso2 port-forward service/hello-kubernetes 8081:80
```

```TEXT
Forwarding from 127.0.0.1:8081 -> 8081
Forwarding from [::1]:8081 -> 8081
Handling connection for 8081
```

Todo correcto hasta ahora, sigamos avanzando.

## Crear el ***helm charm*** solicitado

Creamos un ***helm charm*** 

```BASH
helm create webapp
```

Obtenemos la siguiente estructura en el directorio `webapp`

```TEXT
webapp/:
charts  Chart.yaml  templates  values.yaml

webapp/charts:

webapp/templates:
deployment.yaml  _helpers.tpl  hpa.yaml  ingress.yaml  NOTES.txt  serviceaccount.yaml  service.yaml  tests

webapp/templates/tests:
test-connection.yaml
```

Al examinar el contenido de cada uno de los archivos se observa que se trata de un ***helm charm*** de ejemplo que desplegaría en ***Kubernetes*** un contenedor  con el popular servidor web ***nginx***.

El ejemplo es mucho mas complejo de lo requerido para el *caso 2*, por lo que se decide simplificar, eliminando los archivo que no usaremos y adaptando el resto a nuestras necesidades. Quedando del siguiente estructura.

```TEXT
webapp/
├── Chart.yaml
├── templates
│   ├── deployment.yaml
│   ├── NOTES.txt
│   └── service.yaml
└── values.yaml
```

`webapp/Chart.yaml` no se ha modificado

```YAML
apiVersion: v2
name: webapp
description: A Helm chart for Kubernetes

# A chart can be either an 'application' or a 'library' chart.
#
# Application charts are a collection of templates that can be packaged into versioned archives
# to be deployed.
#
# Library charts provide useful utilities or functions for the chart developer. They're included as
# a dependency of application charts to inject those utilities and functions into the rendering
# pipeline. Library charts do not define any templates and therefore cannot be deployed.
type: application

# This is the chart version. This version number should be incremented each time you make changes
# to the chart and its templates, including the app version.
# Versions are expected to follow Semantic Versioning (https://semver.org/)
version: 0.1.0

# This is the version number of the application being deployed. This version number should be
# incremented each time you make changes to the application. Versions are not expected to
# follow Semantic Versioning. They should reflect the version the application is using.
# It is recommended to use it with quotes.
appVersion: "1.16.0"
```

`webapp/templates/deployment.yaml`

```YAML
apiVersion: apps/v1
kind: Deployment
metadata:
  name: {{ .Values.app.name }}{{ .Values.app.suffix }}
spec:
  replicas: {{ .Values.replicaCount }}
  selector:
    matchLabels:
      app: {{ .Values.app.name }}{{ .Values.app.suffix }}
  template:
    metadata:
      labels:
        app: {{ .Values.app.name }}{{ .Values.app.suffix }}
    spec:
      containers:
      - name: {{ .Values.app.name }}{{ .Values.app.suffix }}
        image: paulbouwer/hello-kubernetes:1.8
        ports:
        - containerPort: {{ .Values.container.port }}
        env:
        - name: MESSAGE
          value: {{ .Values.container.message.part1 }}{{ .Release.Name }}{{ .Values.container.message.part2 }}{{ .Chart.Name }}
        - name: PORT
          value: {{ .Values.container.port | quote }}
```

`webapp/templates/NOTES.TXT`

```TXT
Gracias por instalar {{ .Chart.Name }}.

El nombre de la 'release' instalada es {{ .Release.Name }}.

Opten más información sobre ella ejecutando:

  $ helm status {{ .Release.Name }}
  $ helm get all {{ .Release.Name }}

Para obtener acceso a la aplicación mediante Port Forwarding:

  $ kubectl --namespace {{ .Release.Namespace }} port-forward service/{{ .Values.app.name }}{{ .Values.app.suffix }} XXXXX:{{ .Values.service.port }}

Donde XXXXX es un número entre 49152–65535, puertos efímeros, dinámicos o privados, procura ¡que esté libre!

http://localhost:XXXXX
```

`webapp/templates/service.yaml`

```YAML
apiVersion: v1
kind: Service
metadata:
  name: {{ .Values.app.name }}{{ .Values.app.suffix }}
spec:
  type: {{ .Values.service.type }}
  ports:
  - port: {{ .Values.service.port }}
    targetPort: {{ .Values.container.port }}
  selector:
    app: {{ .Values.app.name }}{{ .Values.app.suffix }}
```

`webapp/values.yaml`

```YAML
# Default values for webapp.
# This is a YAML-formatted file.
# Declare variables to be passed into your templates.

replicaCount: 1

app:
  name: webapp
  suffix: 1969

service:
  type: LoadBalancer
  port: 8081

container:
  port: 8081
  message:
    part1: '¿Que hay de nuevo Viejo?  Soy la release "'
    part2: '" del Chart de Helm '
```

## Desplegar las distintas aplicaciones

Antes de continuar limpiamos y preparamos el entorno

```BASH
kubectl delete namespace mh-caso2
```

Procedemos a crear la primera  ***release*** de nuestro ***Helm Chart***

```BASH
helm install webapp1969 ./webapp --create-namespace --namespace mh-caso2
```

Mediante **Port Forwarding** accedemos a la aplicación

```BASH
kubectl --namespace mh-caso2 port-forward service/webapp1969 8081:8081
```

Para la segunda y tercer release procedemos a cambiar el nombre de la aplicación, el mensaje de la web y el puerto de escucha mediante el uso del modificador '--set'

```BASH
helm install webapp1974 ./webapp --namespace mh-caso2 --set app.suffix=1974 --set service.port=8082 --set container.message.part1='Segunda release "'
```

Para la tercera aumentaremos el número de replicas
    
```BASH
helm install webapp2004 ./webapp --namespace mh-caso2 --set app.suffix=2004 --set service.port=8083 --set container.message.part1='Aquí la tercera release "' --set replicaCount=2
```

Realizando los correspondientes **Port Forwarding** accedemos a las distintas aplicaciones y si consultamos el **namespaces**, vemos que todo se ha desplegado según lo esperado.

```BASH
kubectl --namespace mh-caso2 get all
```

```TEXT
NAME                              READY   STATUS    RESTARTS   AGE
pod/webapp1969-69b6b9b558-tdf8l   1/1     Running   0          27m
pod/webapp1974-dd6c8c8b5-vncx9    1/1     Running   0          3m17s
pod/webapp2004-6fcc87b8cf-9fdbz   1/1     Running   0          97s
pod/webapp2004-6fcc87b8cf-xqf2v   1/1     Running   0          97s

NAME                 TYPE           CLUSTER-IP      EXTERNAL-IP   PORT(S)          AGE
service/webapp1969   LoadBalancer   10.106.82.78    <pending>     8081:32758/TCP   27m
service/webapp1974   LoadBalancer   10.102.38.92    <pending>     8082:31943/TCP   3m17s
service/webapp2004   LoadBalancer   10.105.66.137   <pending>     8083:30365/TCP   97s

NAME                         READY   UP-TO-DATE   AVAILABLE   AGE
deployment.apps/webapp1969   1/1     1            1           27m
deployment.apps/webapp1974   1/1     1            1           3m17s
deployment.apps/webapp2004   2/2     2            2           97s

NAME                                    DESIRED   CURRENT   READY   AGE
replicaset.apps/webapp1969-69b6b9b558   1         1         1       27m
replicaset.apps/webapp1974-dd6c8c8b5    1         1         1       3m17s
replicaset.apps/webapp2004-6fcc87b8cf   2         2         2       97s
```

## Referencias

- [Github](https://github.com/cloudogu/hello-k8s)
- [HELM](https://helm.sh/)
