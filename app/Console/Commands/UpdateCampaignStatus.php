<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use App\Enums\CampaignStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateCampaignStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-campaign-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if campaign is active and update status if todays date is after end date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $campaigns = Campaign::where('status', CampaignStatus::ACTIVE->value)->get();
        $updatedCount = 0;

        foreach ($campaigns as $campaign) {
            if ($campaign->end_date < now()) {
                $campaign->status = CampaignStatus::COMPLETED->value;
                $campaign->save();
                $updatedCount++;
            }
        }

        Log::channel('campaigns')->info("Updated {$updatedCount} campaign(s) from ACTIVE to COMPLETED status.");
    }
}
