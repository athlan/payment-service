#!/bin/bash

docker build --target app-prod -t nowiny-payment-service:latest -f docker/Dockerfile .
