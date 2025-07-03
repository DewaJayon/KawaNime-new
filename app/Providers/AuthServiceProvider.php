<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        ResetPassword::toMailUsing(function ($notifiable, string $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new MailMessage)
                ->greeting('Halo, ' . $notifiable->name)
                ->subject('Reset Password KawaNime')
                ->line('Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda.')
                ->action('Atur Ulang Kata Sandi', $url)
                ->line('Jika Anda tidak meminta pengaturan ulang, abaikan email ini.')
                ->salutation(new HtmlString('Terima kasih,<br>KawaNime'));
        });
    }
}
