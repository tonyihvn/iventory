<?php

namespace App\Policies;

use App\User;
use App\Note;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view the note
     *
     * @param  \App\User  $user
     * @param  \App\Note  $note
     * @return mixed
     */
    public function view(User $user, Note $note)
    {
        // Admin and Super users can view all notes
        if ($user->role == 'Admin' || $user->role == 'Super') {
            return true;
        }

        // Users can only view their own notes
        return $user->id === $note->user_id;
    }

    /**
     * Determine if the user can create notes
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true; // All authenticated users can create notes
    }

    /**
     * Determine if the user can update the note
     *
     * @param  \App\User  $user
     * @param  \App\Note  $note
     * @return mixed
     */
    public function update(User $user, Note $note)
    {
        // Admin and Super users can update all notes
        if ($user->role == 'Admin' || $user->role == 'Super') {
            return true;
        }

        // Users can only update their own notes
        return $user->id === $note->user_id;
    }

    /**
     * Determine if the user can delete the note
     *
     * @param  \App\User  $user
     * @param  \App\Note  $note
     * @return mixed
     */
    public function delete(User $user, Note $note)
    {
        // Admin and Super users can delete all notes
        if ($user->role == 'Admin' || $user->role == 'Super') {
            return true;
        }

        // Users can only delete their own notes
        return $user->id === $note->user_id;
    }
}
