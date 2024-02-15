# MicroHackatones - Caso 5 - Kubernetes monitoring

## Descripción

Una empresa ha recibido quejas de sus usuarios sobre el rendimiento lento de su sitio web/aplicación en determinados momentos. El CISO  (Chief Information Security Officer) ha solicitado al equipo DevOps que monitorice la web/aplicación para identificar el cuello de botella que causa la lentitud.

### Objetivos

- Desplegar 3 o 4 pods utilizando la imagen <https://github.com/cloudogu/hello-k8s>, de forma similar a como se hizo en el taller 3.
- Implementar un sistema de monitorización con Prometheus y Grafana para el cluster de minikube, utilizando Helm para su instalación.
- Identificar las métricas más relevantes para detectar problemas de rendimiento.
- Ver si es posible configurar alertas para notificar al equipo DevOps sobre problemas de rendimiento y caídas de pods.
- Si es posible, configurar las alertas y simular la caída de algún pod.

### Tips

- alert-manager
- kuberntes_sd_configs
- alerting rules

## Pre-requisitos

- Kubernetes. Infra
- Helm. Despliegue Prometheus y Grafana

## Procedimiento

### Instalación kube-prometheus-stack

Instalación del *helm chart* `kube-prometheus-stack` en el *namespace* `prometheus`. Instala ***Grafana*** y ***Prometheus***

```BASH
helm repo add prometheus-community https://prometheus-community.github.io/helm-charts
helm repo update
kubectl create namespace prometheus
helm install prometheus-stack prometheus-community/kube-prometheus-stack -n prometheus
```

Output

```text
W0215 21:44:16.915907   64811 warnings.go:70] unknown field "spec.scrapeConfigNamespaceSelector"
W0215 21:44:16.915938   64811 warnings.go:70] unknown field "spec.scrapeConfigSelector"
NAME: prometheus-stack
LAST DEPLOYED: Thu Feb 15 21:43:53 2024
NAMESPACE: prometheus
STATUS: deployed
REVISION: 1
NOTES:
kube-prometheus-stack has been installed. Check its status by running:
  kubectl --namespace prometheus get pods -l "release=prometheus-stack"

Visit https://github.com/prometheus-operator/kube-prometheus for instructions on how to create & configure Alertmanager and Prometheus instances using the Operator.
```

Para validar la instalación consultamos el estado de los pods, los servicios, realizamos un port-forward y accedemos al grafana

```BASH
kubectl --namespace prometheus get pods
```

```TEXT
NAME                                                     READY   STATUS    RESTARTS   AGE
alertmanager-prometheus-stack-kube-prom-alertmanager-0   2/2     Running   0          13m
prometheus-prometheus-stack-kube-prom-prometheus-0       2/2     Running   0          13m
prometheus-stack-grafana-766b756fc7-2jvgq                3/3     Running   0          14m
prometheus-stack-kube-prom-operator-6dd4dc4c9b-vbrgj     1/1     Running   0          14m
prometheus-stack-kube-state-metrics-69bc8887dd-l7m4h     1/1     Running   0          14m
prometheus-stack-prometheus-node-exporter-rrp6z          1/1     Running   0          14m
```

```BASH
kubectl --namespace prometheus get services
```

```TEXT
NAME                                        TYPE        CLUSTER-IP       EXTERNAL-IP   PORT(S)                      AGE
alertmanager-operated                       ClusterIP   None             <none>        9093/TCP,9094/TCP,9094/UDP   15m
prometheus-operated                         ClusterIP   None             <none>        9090/TCP                     15m
prometheus-stack-grafana                    ClusterIP   10.100.254.21    <none>        80/TCP                       15m
prometheus-stack-kube-prom-alertmanager     ClusterIP   10.102.196.158   <none>        9093/TCP,8080/TCP            15m
prometheus-stack-kube-prom-operator         ClusterIP   10.109.98.79     <none>        443/TCP                      15m
prometheus-stack-kube-prom-prometheus       ClusterIP   10.105.225.137   <none>        9090/TCP,8080/TCP            15m
prometheus-stack-kube-state-metrics         ClusterIP   10.99.52.212     <none>        8080/TCP                     15m
prometheus-stack-prometheus-node-exporter   ClusterIP   10.96.155.36     <none>        9100/TCP                     15m
```

```BASH
kubectl -n prometheus port-forward service/prometheus-stack-grafana 8989:80
```

Una vez realizado el port-forward acedemos por `http://localhost:8989`, el usuario y el pasword lo obtenemos consultado el secreto de en kubernetes

```BASH
kubectl -n prometheus get  secret prometheus-stack-grafana -o  jsonpath='{.data.admin-user}' | base64 -d
kubectl -n prometheus get  secret prometheus-stack-grafana -o  jsonpath='{.data.admin-password}' | base64 -d
```

## Referencias

- [kube-prometheus-stack](https://artifacthub.io/packages/helm/prometheus-community/kube-prometheus-stack)