<?php

namespace App\Jobs;

use App\Mail\AdminDailyReport as MailAdminDailyReport;
use App\Models\Admin;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class AdminDailyReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    //protected $details;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users_per_day = User::whereDate('created_at', Carbon::today())->count();
        $users_per_week = User::where('created_at', '>=', Carbon::today()->subDays(7))->count();
        $users_per_month = User::where('created_at', '>=', Carbon::today()->subDays(30))->count();

        $posts_per_day = Post::whereDate('created_at', Carbon::today())->count();
        $posts_per_week = Post::where('created_at', '>=', Carbon::today()->subDays(7))->count();
        $posts_per_month = Post::where('created_at', '>=', Carbon::today()->subDays(30))->count();

        $data = [
            'users_per_day' => $users_per_day,
            'users_per_week' => $users_per_week,
            'users_per_month' => $users_per_month,

            'posts_per_day' => $posts_per_day,
            'posts_per_week' => $posts_per_week,
            'posts_per_month' => $posts_per_month,
        ];

        $admins = Admin::all();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new MailAdminDailyReport($data));
        }
    }
}
