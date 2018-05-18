<?php

namespace Victorlap\Approvable;

use Illuminate\Support\ServiceProvider;

class ApprovableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        if (!class_exists('CreateApprovalsTable')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__ . '/../migrations/create_approvals_table.php.stub'
                    => database_path("/migrations/{$timestamp}_create_approvals_table.php"),
            ], 'migrations');
        }
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
    }
}
