<?php

namespace App\Providers;

use App\Models\Work;
use App\Models\Publication;
use App\Models\Platform;
use App\Models\Marketplace;
use App\Models\BookPromotion;
use App\Models\KdpSelectPeriod;
use App\Policies\WorkPolicy;
use App\Policies\PublicationPolicy;
use App\Policies\PlatformPolicy;
use App\Policies\MarketplacePolicy;
use App\Policies\BookPromotionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Work::class => WorkPolicy::class,
        Publication::class => PublicationPolicy::class,
        Platform::class => PlatformPolicy::class,
        Marketplace::class => MarketplacePolicy::class,
        BookPromotion::class => BookPromotionPolicy::class,
        KdpSelectPeriod::class => BookPromotionPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}