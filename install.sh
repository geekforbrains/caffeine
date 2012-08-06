#!/usr/bin/env bash

echo 'Caffeine Installer v1.0'
echo '-----------------------'

git --version >/dev/null 2>&1 || { echo >&2 "Oops, it looks like you dont have git. I need that to install Caffeine Tools."; exit 1; }

INSTALL_DIR="$HOME/.caffeine"
EXPORT_STR='export PATH=$PATH:$HOME/.caffeine # Caffeine Tools'

if [ -d "$INSTALL_DIR" ]; then
    echo 'Looks like Caffeine Tools are already installed.'
    echo 'To update, run: caffeine update tools'
    exit 1;
fi

echo "Downloading latest Caffeine Tools from GitHub, please wait..."
git clone -b tools git@github.com:geekforbrains/caffeine $INSTALL_DIR &> /dev/null

echo "Caffeine Tools were installed to: $INSTALL_DIR"

echo "Adding installation directory to your PATH (~/.bash_profile)"
echo $EXPORT_STR >> ~/.bash_profile
source ~/.bash_profile

echo 'All done.'
echo 'To start using Caffeine, run: caffeine'
