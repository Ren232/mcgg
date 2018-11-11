<?php // MCHostPanel Configuration

// Server IP Address
define('KT_LOCAL_IP','127.0.0.1');

// Prefix for GNU-Screen names (prepended to username)
define('KT_SCREEN_NAME_PREFIX','mc.srv-');

// Path to download server updates from (uses wget)
define('KT_UPDATE_URL_MC','http://s3.amazonaws.com/MinecraftDownload/launcher/minecraft_server.jar');
define('KT_UPDATE_URL_CB','http://dl.bukkit.org/latest-rb/craftbukkit.jar');

// Screen commands (these should never be modified)
define('KT_SCREEN_CMD_START','screen -dmS %s java -Xms%sM -Xmx%sM -XX:+AlwaysPreTouch -XX:+DisableExplicitGC -XX:+UseG1GC -XX:+UnlockExperimentalVMOptions -XX:MaxGCPauseMillis=45 -XX:TargetSurvivorRatio=90 -XX:G1NewSizePercent=50 -XX:G1MaxNewSizePercent=80 -XX:InitiatingHeapOccupancyPercent=10 -XX:G1MixedGCLiveThresholdPercent=50 -XX:+AggressiveOpts -jar craftbukkit.jar nogui');
define('KT_SCREEN_CMD_EXEC','screen -S %s -p 0 -X stuff "%s$(printf \\\\r)"');
define('KT_SCREEN_CMD_KILL','screen -X -S %s quit');
define('KT_SCREEN_CMD_KILLALL','killall screen');
define('KT_SCREEN_CMD_KILLALL_USER','for session in $(screen -ls | /bin/grep -o \'[0-9]*\\.%s\'); do screen -S "${session}" -X quit; done');

// ngrok commands
define('NGROK_ID','mc.scid-');
define('NGROK_START','screen -dmS %s ./ngrok tcp -config=%s/ngrok.yml %s');
