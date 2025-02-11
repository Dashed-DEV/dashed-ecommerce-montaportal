<?php

namespace Dashed\DashedEcommerceMontaportal;

use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Illuminate\Console\Scheduling\Schedule;
use Dashed\DashedEcommerceCore\Models\Order;
use Dashed\DashedEcommerceCore\Models\Product;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Dashed\DashedEcommerceMontaportal\Classes\Montaportal;
use Dashed\DashedEcommerceMontaportal\Models\MontaportalOrder;
use Dashed\DashedEcommerceMontaportal\Models\MontaportalProduct;
use Dashed\DashedEcommerceMontaportal\Commands\DeleteMontaportalProducts;
use Dashed\DashedEcommerceMontaportal\Commands\PushProductsToMontaportal;
use Dashed\DashedEcommerceMontaportal\Livewire\Orders\ShowMontaportalOrder;
use Dashed\DashedEcommerceMontaportal\Commands\PushOrdersToMontaportalCommand;
use Dashed\DashedEcommerceMontaportal\Commands\SyncProductStockWithMontaportal;
use Dashed\DashedEcommerceMontaportal\Livewire\Products\EditMontaportalProduct;
use Dashed\DashedEcommerceMontaportal\Commands\SyncUnconnectedMontaportalOrders;
use Dashed\DashedEcommerceMontaportal\Commands\UpdateOrdersToMontaportalCommand;
use Dashed\DashedEcommerceMontaportal\Filament\Pages\Settings\MontaportalSettingsPage;
use Dashed\DashedEcommerceMontaportal\Commands\UpdateOrderTrackAndTraceFromMontaportalCommand;

class DashedEcommerceMontaportalServiceProvider extends PackageServiceProvider
{
    public static string $name = 'dashed-ecommerce-montaportal';

    public function bootingPackage()
    {
        Livewire::component('show-montaportal-order', ShowMontaportalOrder::class);
        Livewire::component('edit-montaportal-product', EditMontaportalProduct::class);

        Order::addDynamicRelation('montaPortalOrder', function (Order $model) {
            return $model->hasOne(MontaportalOrder::class);
        });

        Product::addDynamicRelation('montaportalProduct', function (Product $model) {
            return $model->hasOne(MontaportalProduct::class);
        });

        $this->app->booted(function () {
            $schedule = app(Schedule::class);
            $schedule->command(DeleteMontaportalProducts::class)
                ->everyFiveMinutes()
                ->withoutOverlapping();
            $schedule->command(PushProductsToMontaportal::class)
                ->everyFiveMinutes()
                ->withoutOverlapping();
            $schedule->command(SyncProductStockWithMontaportal::class)
                ->everyFiveMinutes()
                ->withoutOverlapping();
            $schedule->command(PushOrdersToMontaportalCommand::class)
                ->everyFiveMinutes()
                ->withoutOverlapping();
            $schedule->command(UpdateOrderTrackAndTraceFromMontaportalCommand::class)
                ->everyFifteenMinutes()
                ->withoutOverlapping();
            $schedule->command(UpdateOrdersToMontaportalCommand::class)
                ->everyFifteenMinutes()
                ->withoutOverlapping();
            $schedule->command(SyncUnconnectedMontaportalOrders::class)
                ->everyMinute()
                ->withoutOverlapping();
        });
    }

    public function configurePackage(Package $package): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        cms()->registerSettingsPage(MontaportalSettingsPage::class, 'Montaportal', 'archive-box', 'Koppel Montaportal aan je bestellingen');

        ecommerce()->widgets(
            'orders',
            array_merge(ecommerce()->widgets('orders'), [
                'show-montaportal-order' => [
                    'name' => 'show-montaportal-order',
                    'width' => 'sidebar',
                ],
            ])
        );

        ecommerce()->builder(
            'fulfillmentProviders',
            array_merge(ecommerce()->builder('fulfillmentProviders'), [
                'montaportal' => [
                    'name' => 'MontaPortal',
                    'class' => Montaportal::class,
                ],
            ])
        );

        $package
            ->name('dashed-ecommerce-montaportal')
            ->hasViews()
            ->hasCommands([
                DeleteMontaportalProducts::class,
                PushProductsToMontaportal::class,
                SyncProductStockWithMontaportal::class,
                PushOrdersToMontaportalCommand::class,
                UpdateOrdersToMontaportalCommand::class,
                UpdateOrderTrackAndTraceFromMontaportalCommand::class,
                SyncUnconnectedMontaportalOrders::class,
            ]);

        cms()->builder('plugins', [
            new DashedEcommerceMontaportalPlugin(),
        ]);
    }
}
