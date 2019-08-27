#!/bin/bash

DOCKER_SERVER=cloud.canister.io:5000
DOCKER_SERVER_USERNAME=nowinypl
VERSION=`git describe --tags`

publish_docker() {
    docker login ${DOCKER_SERVER} -u ${DOCKER_SERVER_USERNAME}

    docker tag nowiny-payment-service:latest ${DOCKER_SERVER}/nowinypl/nowiny-payment-service:${VERSION}

    docker push ${DOCKER_SERVER}/nowinypl/nowiny-payment-service:${VERSION}

    docker logout ${DOCKER_SERVER}
}

echo "You are about to publish $VERSION".
read -n 1 -s -r -p "Press enter to continue or please abort"
echo

publish_docker
