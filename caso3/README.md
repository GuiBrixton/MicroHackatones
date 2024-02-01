# MicroHackatones - Caso 3 - Jenkins Base

## Descripción
 
Imagina que trabajas en un equipo de desarrollo de software y han estado construyendo una aplicación web. Quieren implementar un proceso de despliegue continuo para garantizar que las nuevas versiones se desplieguen de manera eficiente y sin errores en el entorno de producción.
 
Pasos del Caso de Uso:

1. Instalación de *Jenkins* utilizando *Helm* <https://github.com/jenkinsci/helm-charts> en *K8s* (ej. *Minikube*).
2. La ejecución de las *pipelines* deben de ser en **agentes efímeros** que se ejecutan dentro del mismo *K8s*.
3. Crear una *pipeline* con todos los *stages* que creas que deba llevar una aplicación web. Los *stages* de momento mostrarán un `echo` con el nombre del mismo a excepción de el/los ultimo/s.
3. Los últimos *stages* deben configurar el *pod agente* y desplegar en *K8s* el micro <https://github.com/cloudogu/hello-k8s>, puede ser alguna otra web a vuestra elección, incluso algún repo *helm* con alguna web. Esto hará que el agente también necesite *helm*.
 
Tips:
- Kubeconfig
- Kubectl run (sin complicarnos mucho en el primer caso de Jenkins :D)
- https://hub.docker.com/r/bitnami/kubectl
- podTemplate
- GitHub - jenkinsci/helm-charts: Jenkins helm charts
- Jenkins helm charts. Contribute to jenkinsci/helm-charts development by creating an account on GitHub.


## Paso1

Instalación de Jenkins en Kubernetes con Helm

Sin complicarnos la vida, la única personalización,s definir un NodePort en el puerto 32000 

## Paso2
Probar la instalación de kubectl y helm en un contenedor con la imagen que usaremos más adelante para definir los agentes o directamente un pod con la siguiente definición:


docker run --rm -it alpine:3.19.1 /bin/sh

kubectl -n mh-caso3 -it exec containers-for-jenkins-agent -- /bin/sh