<?php

namespace App\Providers;

use App\Models\Notice;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class NoticeComposerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // আগে শুধু 'frontend.dashboard.app' ছিল — এখন public-facing
        // 'layouts.frontend' (homepage, about, contact, ইত্যাদি) ও যোগ হলো,
        // যাতে দুই জায়গাতেই ব্যানার দেখায়।
        View::composer(['frontend.dashboard.app', 'layouts.frontend'], function ($view) {
            $activeNotices = Cache::remember('active_notices', 300, function () {
                return Notice::currentlyActive()->latest()->get();
            });

            $view->with('activeNotices', $activeNotices);
        });
    }
}
