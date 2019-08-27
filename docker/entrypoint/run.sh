#!/bin/bash

ENTRYPOINT_MODE_DEFAULT="app"
ENTRYPOINT_MODE="${ENTRYPOINT_MODE:-${ENTRYPOINT_MODE_DEFAULT}}"

RUN_FILE="/usr/local/app/entrypoint/run-${ENTRYPOINT_MODE}.sh"

$RUN_FILE
