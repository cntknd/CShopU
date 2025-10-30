<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;

class CancelOverdueOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:cancel-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel orders that have not been paid within 24 hours of confirmation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for overdue orders...');
        
        // Find orders that are confirmed, not paid, and confirmed_at is more than 24 hours ago
        $overdueOrders = Order::where('status', 'confirmed')
            ->whereNull('paid_at')
            ->where('confirmed_at', '<=', Carbon::now()->subHours(24))
            ->get();
        
        $count = 0;
        
        foreach ($overdueOrders as $order) {
            $this->info("Cancelling overdue order #{$order->id}");
            $order->cancelOrder();
            $count++;
        }
        
        if ($count > 0) {
            $this->info("Cancelled {$count} overdue order(s)");
        } else {
            $this->info('No overdue orders found');
        }
        
        return Command::SUCCESS;
    }
}

