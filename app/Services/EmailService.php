<?php

namespace App\Services;

use App\Models\Donation;
use App\Mail\DonationConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{    
    /**
     * Send donation confirmation email asynchronously (queued)
     */
    public function sendDonationConfirmationAsync(Donation $donation): bool
    {
        try {
            $donation->load(['createdBy', 'campaign', 'details']);

            Mail::to($donation->createdBy->email)
                ->queue(new DonationConfirmation($donation));
            
            Log::info('Donation confirmation email queued', [
                'donation_id' => $donation->id,
                'donor_email' => $donation->createdBy->email,
                'amount' => $donation->amount
            ]);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Failed to queue donation confirmation email', [
                'donation_id' => $donation->id,
                'donor_email' => $donation->createdBy->email ?? 'unknown',
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }
} 