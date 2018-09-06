#!/bin/bash

DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

INPUT_DIR=$(realpath "$DIR/../input")

echo $INPUT_DIR

docker run --rm \
    -v $DIR:/powershell \
    -v $INPUT_DIR:/input \
    mcr.microsoft.com/powershell:ubuntu-16.04 \
    pwsh "/powershell/$@"