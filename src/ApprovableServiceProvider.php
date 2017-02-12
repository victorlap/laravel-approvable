<?php

namespace Victorlap\Approvable;

use Illuminate\Support\ServiceProvider;

/**
 * Class ApprovableServiceProvider
 * @package Victorlap\Approvable
 */
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
}
