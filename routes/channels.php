<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('games.{gameKey}', function () {
    return true;
});

Broadcast::channel('rooms.{roomId}', function () {
    return true;
});

Broadcast::channel('rooms.sessions.{sessionId}', function () {
    return true;
});
