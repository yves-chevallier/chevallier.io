<?php

use Illuminate\Support\Facades\Broadcast;
use App\Broadcasting\ActivityChannel;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('activity', ActivityChannel::class);

Broadcast::channel('quiz', function ($user) {
    if ($user->isTeacher()) {
        Log::debug('Request to access quiz-channel approved for ' . $user->name);
        return true;
    } else {
        Log::debug('Request denied to access quiz-channel for ' . $user->name);
        return false;
    }
});

Broadcast::channel('activity.{activity_id}', function ($user, $activity_id) {
    if ($user->isStudent() && $user->student->canJoinActivity($activity_id)) {
        return ['id' => $user->id, 'name' => $user->name, 'type' => 'student'];
    }
    if ($user->isTeacher()) {
        return ['id' => $user->id, 'name' => $user->name, 'type' => 'teacher'];
    }
});

/**
 * Receives answers when posted
 */
Broadcast::channel('activity.{activity_id}.teacher', function ($user, $activity_id) {
    return $user->isTeacher();
});
