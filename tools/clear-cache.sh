#!/bin/bash
# This script clears Twig's template cache at the default location, useful during updates that change templates.

rm -rf templates/cache/*
