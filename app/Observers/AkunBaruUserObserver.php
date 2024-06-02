<?php

namespace App\Observers;

use App\Models\AkunBaruUser;

class AkunBaruUserObserver
{
    /**
     * Handle the AkunBaruUser "created" event.
     */
    public function created(AkunBaruUser $akunBaruUser): void
    {
        //
    }

    /**
     * Handle the AkunBaruUser "updated" event.
     */
    public function updated(AkunBaruUser $akunBaruUser): void
    {
        //
    }

    /**
     * Handle the AkunBaruUser "deleted" event.
     */
    public function deleted(AkunBaruUser $akunBaruUser): void
    {
        //
    }

    /**
     * Handle the AkunBaruUser "restored" event.
     */
    public function restored(AkunBaruUser $akunBaruUser): void
    {
        //
    }

    /**
     * Handle the AkunBaruUser "force deleted" event.
     */
    public function forceDeleted(AkunBaruUser $akunBaruUser): void
    {
        //
    }
}
