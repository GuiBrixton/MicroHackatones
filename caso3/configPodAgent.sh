#!/bin/sh
apk add curl
curl -LO "https://dl.k8s.io/release/$(curl -L -s https://dl.k8s.io/release/stable.txt)/bin/linux/amd64/kubectl"
curl -LO "https://dl.k8s.io/release/$(curl -L -s https://dl.k8s.io/release/stable.txt)/bin/linux/amd64/kubectl.sha256"
echo "$(cat kubectl.sha256)  kubectl" | sha256sum -c
install -o root -g root -m 0755 kubectl /usr/local/bin/kubectl
kubectl version --client --output=yaml
apk add helm
export KUBECONFIG="/kubeconfig/.kubeconfig"
apk add git
git clone https://github.com/antoniollv/MicroHackatones.git
kubectl delete namespace mh-caso2
helm install webapp1969 ./webapp --create-namespace --namespace mh-caso2
