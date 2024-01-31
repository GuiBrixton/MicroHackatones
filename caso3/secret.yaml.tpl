apiVersion: v1
kind: Secret
metadata:
  name: dotfile-secret
data:
  .secret-file: |
    ===SECRETO EN BASE64==
---
apiVersion: v1
kind: Pod
metadata:
  name: secret-dotfiles-pod
spec:
  volumes:
    - name: secret-volume
      secret:
        secretName: dotfile-secret
  containers:
    - name: dotfile-test-container
      image: registry.k8s.io/busybox
      command: ["/bin/sh", "-ec", "while :; do echo '.'; sleep 5 ; done"]
      volumeMounts:
        - name: secret-volume
          readOnly: true
          mountPath: "/etc/secret-volume"
