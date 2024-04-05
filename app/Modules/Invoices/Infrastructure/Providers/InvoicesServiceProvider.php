<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Infrastructure\Providers;

use App\Modules\Approval\Api\Events\EntityApproved;
use App\Modules\Approval\Api\Events\EntityRejected;
use App\Modules\Invoices\Api\InvoicesFacadeInterface;
use App\Modules\Invoices\Application\InvoiceRepositoryInterface;
use App\Modules\Invoices\Application\InvoicesFacade;
use App\Modules\Invoices\Application\Listeners\EntityApprovedListener;
use App\Modules\Invoices\Application\Listeners\EntityRejectedListener;
use App\Modules\Invoices\Infrastructure\Database\Repository\InvoiceRepository;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class InvoicesServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->scoped(InvoiceRepositoryInterface::class, InvoiceRepository::class);
        $this->app->scoped(InvoicesFacadeInterface::class, InvoicesFacade::class);
    }

    /** @return array<class-string> */
    public function provides(): array
    {
        return [
            InvoicesFacadeInterface::class,
        ];
    }

    public function boot(): void
    {
        Event::listen(
            EntityApproved::class,
            EntityApprovedListener::class
        );
        Event::listen(
            EntityRejected::class,
            EntityRejectedListener::class
        );
    }
}
