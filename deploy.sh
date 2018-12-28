#!/usr/bin/env bash
PLUGIN_PATH="/Users/quypv/go/src/mmc-nalkathon-chim-non/dist"
PLUGIN_ID="zoho-plugin"
make
rm -rf $PLUGIN_PATH/$PLUGIN_ID
echo "remove old plugin"
cp -rf dist/$PLUGIN_ID $PLUGIN_PATH/
echo "copy new plugin version to MMC server"