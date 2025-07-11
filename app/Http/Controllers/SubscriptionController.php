<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Laravel\Cashier\Cashier;
use Illuminate\Http\Request;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use App\Models\Organization;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    public function index()
    {
        if (auth()->user()->role_id != 1) {
            return redirect()->route(route: '/dashboard')->with('error', 'You do not have permission to access this page.');
        }
        $subscriptions = Subscription::all();
        return view('admin.subscriptions.index', compact('subscriptions'));
    }
    public function create($planId)
    {
        // Fetch the plan
        $plan = Subscription::findOrFail($planId);
        return view('subscriptions.create', compact('plan'));
    }

    public function showPricing()
    {
        $subscriptions = Subscription::where('is_active', '1')->get();
        $user = auth()->user();
        $selectedSubscription = $user?->organization?->subscription_id ?? null;
        logger()->info('Selected Subscription ID: ' . $selectedSubscription);
        $activeSubscription =Subscription::where('id', $selectedSubscription)->first();
        $currentSubscription = [
            'name' => $activeSubscription->name ?? 'NA',
            'expiry_date' => $user?->organization->subscription_valid ?? now()->addDays(30), // Default to 30 days from now if no subscription
            'max_users' => $user?->organization->max_users ?? 5, // Default to 5 users if not set
            'max_locations' => $user?->organization->max_locations ?? 10, // Default to 10 locations if not set
            'price' =>  $activeSubscription->price ?? 0, // Default to 0 if no subscription
            'duration' =>$activeSubscription->duration ?? 0, // in months
        ];

        return view('organization.settings.subscription_settings.index', [
            'subscriptions' => $subscriptions,
            'selectedSubscription' => $selectedSubscription,
            'subscriptionDuration' => 0,
            'currentSubscription' => $currentSubscription,
        ]);
    }
    public function checkout(Request $request)
    {
        $org = auth()->user()->organization;
        if (!$org->hasStripeId()) {
            $org->createAsStripeCustomer();
        }

        $subscription = Subscription::findOrFail($request->subscription_id);
        if ((float)$subscription->price == 0.00) {
    $org->subscription_id = $subscription->id;

    $durationMonths = is_numeric($subscription->duration) ? (int)$subscription->duration : 3;
    $org->subscription_valid = now()->addMonths($durationMonths);
        $org->save();

        return redirect()->route('catalog')->with('success', 'Free plan activated.');
    }
        $org->subscription_id = $subscription->id;
        $org->save();
        $stripePriceId = $subscription->stripe_price_id;

        return $org->checkout($stripePriceId, [
            'success_url' => route('dashboard') . '?subscribed=true',
            'cancel_url' => route('pricing'),
            'mode' => 'subscription',
            'client_reference_id' => $org->id,
            'metadata' => [
                'organization_id' => $org->id,
            ],
        ]);
    }


    public function billingPortal(Request $request)
    {
        $org = auth()->user()->organization;

        $session = $org->stripe()->billingPortal->sessions->create([
            'customer' => $org->stripe_id,
            'return_url' => route('dashboard'),
        ]);

        return redirect($session->url);
    }

    public function handleWebhook(Request $request)
    {

        // \Log::info('Webhook test hit!', ['payload' => $request->getContent()]);

        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            // Verify the webhook signature
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid Stripe webhook payload: ' . $e->getMessage());
            return response('Invalid payload', 400);
        } catch (SignatureVerificationException $e) {
            Log::error('Invalid Stripe webhook signature: ' . $e->getMessage());
            return response('Invalid signature', 400);
        }

        // Handle the event directly
        switch ($event->type) {
            case 'checkout.session.completed':
                $this->handleCheckoutSessionCompleted($event->data->object);
                break;
            case 'customer.subscription.created':
                $this->handleSubscriptionCreated($event->data->object);
                break;
            case 'customer.subscription.updated':
                $this->handleSubscriptionUpdated($event->data->object);
                break;
            case 'customer.subscription.deleted':
                $this->handleSubscriptionDeleted($event->data->object);
                break;
            default:
                Log::info('Unhandled Stripe webhook event type: ' . $event->type);
        }

        return response('Webhook handled successfully', 200);
    }

    private function handleCheckoutSessionCompleted($session)
    {
        $customerId = $session->customer ?? null;
        $clientReferenceId = $session->client_reference_id ?? null;
        $organizationIdFromMetadata = $session->metadata->organization_id ?? null;

        Log::info('Stripe Checkout Session Completed', [
            'customer_id' => $customerId,
            'session_id' => $session->id,
            'payment_status' => $session->payment_status,
            'amount_total' => $session->amount_total,
            'client_reference_id' => $clientReferenceId,
            'metadata' => $session->metadata,
        ]);

        $organization = null;

        // Try to find organization by customer ID first
        if ($customerId) {
            $organization = Organization::where('stripe_id', $customerId)->first();
        }

        // If not found, try by client_reference_id (organization ID)
        if (!$organization && $clientReferenceId) {
            $organization = Organization::find($clientReferenceId);
        }

        // If still not found, try by metadata
        if (!$organization && $organizationIdFromMetadata) {
            $organization = Organization::find($organizationIdFromMetadata);
        }

        if ($organization) {
            // Update the organization's Stripe customer ID if we have one and it's not set
            if ($customerId && !$organization->stripe_id) {
                $organization->stripe_id = $customerId;
            }

            // Update subscription validity
            $organization->subscription_valid = now()->addMonth();
            $organization->save();

            Log::info('Organization subscription updated', [
                'organization_id' => $organization->id,
                'subscription_valid_until' => $organization->subscription_valid,
                'stripe_customer_id' => $customerId,
            ]);
        } else {
            Log::warning('No organization found for checkout session', [
                'session_id' => $session->id,
                'customer_id' => $customerId,
                'client_reference_id' => $clientReferenceId,
                'metadata_org_id' => $organizationIdFromMetadata,
            ]);
        }
    }

    private function handleSubscriptionCreated($subscription)
    {
        // Log::info('Stripe Subscription Created', [
        //     'subscription_id' => $subscription->id,
        //     'customer_id' => $subscription->customer,
        //     'status' => $subscription->status,
        // ]);

        // $organization = Organization::where('stripe_id', $subscription->customer)->first();

        // if ($organization) {
        //     $organization->stripe_id = $subscription->id;
        //     $organization->subscription_status = $subscription->status;

        //     // Set subscription validity based on current period end
        //     if ($subscription->current_period_end) {
        //         $organization->subscription_valid = \Carbon\Carbon::createFromTimestamp($subscription->current_period_end);
        //     }

        //     $organization->save();
        // }
    }

    private function handleSubscriptionUpdated($subscription)
    {
        // Log::info('Stripe Subscription Updated', [
        //     'subscription_id' => $subscription->id,
        //     'customer_id' => $subscription->customer,
        //     'status' => $subscription->status,
        // ]);

        $organization = Organization::where('stripe_id', $subscription->customer)->first();

        if ($organization) {
            // $organization->subscription_status = $subscription->status;

            // Update subscription validity
            // if ($subscription->current_period_end) {
                // $organization->subscription_valid = \Carbon\Carbon::createFromTimestamp($subscription->current_period_end);
            // }

            // $organization->save();
        }
    }

    private function handleSubscriptionDeleted($subscription)
    {
        // Log::info('Stripe Subscription Deleted', [
        //     'subscription_id' => $subscription->id,
        //     'customer_id' => $subscription->customer,
        // ]);

        $organization = Organization::where('stripe_id', $subscription->customer)->first();

        if ($organization) {
            // $organization->subscription_status = 'canceled';
            // You might want to set a grace period instead of immediate expiration
            // $organization->subscription_valid = now()->addDays(7); // 7-day grace period
            // $organization->save();
        }
    }
}