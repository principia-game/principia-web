#!/bin/bash

find data/ -type d -exec chmod 777 {} \;
find data/ -type f -exec chmod 666 {} \;
