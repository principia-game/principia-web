#!/bin/bash

WATCH_DIR="/tmp/principia_pending_screenshots"
POLL_INTERVAL=10

mkdir -p $WATCH_DIR

while true; do
	for file in "$WATCH_DIR"/*; do
		if [[ ! -e "$file" ]]; then
			continue
		fi

		LEVEL_ID=$(cat "$file")

		echo "Found $file: Level ID $LEVEL_ID"

		./tools/take_screenshot.sh "$LEVEL_ID"

		rm "$file"

		if [[ $? -eq 0 ]]; then
			echo "Error processing file $file"
		fi
	done

	sleep "$POLL_INTERVAL"
done
