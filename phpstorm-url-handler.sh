#!/bin/bash
REGEX="^phpstorm://(.*)$"

if [[ $1 =~ $REGEX ]]; then
    /usr/local/bin/phpstorm "${BASH_REMATCH[1]}"

    exit 0
fi

exit 1