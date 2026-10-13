<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\BookingStatusHistory;
use App\Models\Customer;
use App\Models\Provider;
use App\Models\Service;
use App\Models\ServiceArea;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | GET EXISTING CUSTOMER
        |--------------------------------------------------------------------------
        */

        $customer = Customer::with('user')->first();

        if (!$customer) {

            $this->command->error(
                'No customer found. Please create a customer first.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | GET EXISTING PROVIDER
        |--------------------------------------------------------------------------
        */

        $provider = Provider::with('user')->first();

        if (!$provider) {

            $this->command->error(
                'No provider found. Please create a provider first.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | GET EXISTING SERVICE AREA
        |--------------------------------------------------------------------------
        */

        $area = ServiceArea::first();

        if (!$area) {

            $this->command->error(
                'No service area found. Please create a service area first.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | GET EXISTING SERVICES
        |--------------------------------------------------------------------------
        |
        | Get active services that already exist in the database.
        |
        */

        $services = Service::where('is_active', true)
            ->orderBy('id')
            ->get()
            ->values();


        if ($services->isEmpty()) {

            $this->command->error(
                'No active services found. Please create at least one service first.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | REMOVE PREVIOUS TEST BOOKINGS
        |--------------------------------------------------------------------------
        */

        $bookingIds = Booking::where(
            'customer_id',
            $customer->id
        )->pluck('id');


        BookingStatusHistory::whereIn(
            'booking_id',
            $bookingIds
        )->delete();


        Booking::where(
            'customer_id',
            $customer->id
        )->delete();


        /*
        |--------------------------------------------------------------------------
        | SERVICE VARIABLES
        |--------------------------------------------------------------------------
        |
        | Use REAL services from the database.
        |
        | If there are fewer than 5 active services,
        | the available service is reused.
        |
        */

        $service1 = $services->get(0);

        $service2 = $services->get(1) ?? $service1;

        $service3 = $services->get(2) ?? $service1;

        $service4 = $services->get(3) ?? $service1;

        $service5 = $services->get(4) ?? $service1;


        /*
        |--------------------------------------------------------------------------
        | BOOKING 1
        | COMPLETED
        |--------------------------------------------------------------------------
        */

        $booking1 = Booking::create([

            'customer_id' => $customer->id,

            'provider_id' => $provider->id,

            'area_id' => $area->id,

            'booking_type' => 'scheduled',

            'scheduled_at' =>
                now()
                    ->subDays(2)
                    ->setTime(10, 0),

            'address' => $customer->address,

            'latitude' => 16.8409,

            'longitude' => 96.1735,

            'chosen_services' => [
                [
                    'service_id' => $service1->id,

                    'name' => $service1->name,

                    'quantity' => 1,

                    'price' => $service1->base_price,
                ],
            ],

            'total_amount' => $service1->base_price,

            'response_deadline' =>
                now()
                    ->subDays(2)
                    ->addMinutes(15),

            'status' => 'completed',

            'customer_note' =>
                'Please complete the requested service carefully.',
        ]);


        $this->createHistory(
            $booking1,
            'pending',
            $customer->user_id,
            'Booking created by customer.'
        );


        $this->createHistory(
            $booking1,
            'accepted',
            $provider->user_id,
            'Provider accepted the booking.'
        );


        $this->createHistory(
            $booking1,
            'on_the_way',
            $provider->user_id,
            'Provider is on the way.'
        );


        $this->createHistory(
            $booking1,
            'arrived',
            $provider->user_id,
            'Provider arrived at the customer location.'
        );


        $this->createHistory(
            $booking1,
            'in_progress',
            $provider->user_id,
            'Service started.'
        );


        $this->createHistory(
            $booking1,
            'completed',
            $provider->user_id,
            'Service completed successfully.'
        );


        /*
        |--------------------------------------------------------------------------
        | BOOKING 2
        | IN PROGRESS
        |--------------------------------------------------------------------------
        */

        $booking2 = Booking::create([

            'customer_id' => $customer->id,

            'provider_id' => $provider->id,

            'area_id' => $area->id,

            'booking_type' => 'asap',

            'scheduled_at' => now(),

            'address' => $customer->address,

            'latitude' => 16.8409,

            'longitude' => 96.1735,

            'chosen_services' => [
                [
                    'service_id' => $service2->id,

                    'name' => $service2->name,

                    'quantity' => 1,

                    'price' => $service2->base_price,
                ],
            ],

            'total_amount' => $service2->base_price,

            'response_deadline' =>
                now()->addMinutes(15),

            'status' => 'in_progress',

            'customer_note' =>
                'Please check and fix the problem.',
        ]);


        $this->createHistory(
            $booking2,
            'pending',
            $customer->user_id,
            'Booking created by customer.'
        );


        $this->createHistory(
            $booking2,
            'accepted',
            $provider->user_id,
            'Provider accepted the booking.'
        );


        $this->createHistory(
            $booking2,
            'on_the_way',
            $provider->user_id,
            'Provider is on the way.'
        );


        $this->createHistory(
            $booking2,
            'arrived',
            $provider->user_id,
            'Provider arrived.'
        );


        $this->createHistory(
            $booking2,
            'in_progress',
            $provider->user_id,
            'Service is currently in progress.'
        );


        /*
        |--------------------------------------------------------------------------
        | BOOKING 3
        | PENDING
        |--------------------------------------------------------------------------
        */

        $booking3 = Booking::create([

            'customer_id' => $customer->id,

            'provider_id' => $provider->id,

            'area_id' => $area->id,

            'booking_type' => 'scheduled',

            'scheduled_at' =>
                now()
                    ->addDay()
                    ->setTime(14, 0),

            'address' => $customer->address,

            'latitude' => 16.8409,

            'longitude' => 96.1735,

            'chosen_services' => [
                [
                    'service_id' => $service3->id,

                    'name' => $service3->name,

                    'quantity' => 1,

                    'price' => $service3->base_price,
                ],
            ],

            'total_amount' => $service3->base_price,

            'response_deadline' =>
                now()->addMinutes(15),

            'status' => 'pending',

            'customer_note' =>
                'Please check the issue before starting the service.',
        ]);


        $this->createHistory(
            $booking3,
            'pending',
            $customer->user_id,
            'Booking created and waiting for provider response.'
        );


        /*
        |--------------------------------------------------------------------------
        | BOOKING 4
        | ACCEPTED
        |--------------------------------------------------------------------------
        */

        $booking4 = Booking::create([

            'customer_id' => $customer->id,

            'provider_id' => $provider->id,

            'area_id' => $area->id,

            'booking_type' => 'scheduled',

            'scheduled_at' =>
                now()
                    ->addDays(2)
                    ->setTime(11, 0),

            'address' => $customer->address,

            'latitude' => 16.8409,

            'longitude' => 96.1735,

            'chosen_services' => [
                [
                    'service_id' => $service4->id,

                    'name' => $service4->name,

                    'quantity' => 1,

                    'price' => $service4->base_price,
                ],
            ],

            'total_amount' => $service4->base_price,

            'response_deadline' =>
                now()->addMinutes(15),

            'status' => 'accepted',

            'customer_note' =>
                'Please contact me before starting the service.',
        ]);


        $this->createHistory(
            $booking4,
            'pending',
            $customer->user_id,
            'Booking created.'
        );


        $this->createHistory(
            $booking4,
            'accepted',
            $provider->user_id,
            'Provider accepted the booking.'
        );


        /*
        |--------------------------------------------------------------------------
        | BOOKING 5
        | CANCELLED
        |--------------------------------------------------------------------------
        */

        $booking5 = Booking::create([

            'customer_id' => $customer->id,

            'provider_id' => $provider->id,

            'area_id' => $area->id,

            'booking_type' => 'scheduled',

            'scheduled_at' =>
                now()
                    ->subDay()
                    ->setTime(15, 0),

            'address' => $customer->address,

            'latitude' => 16.8409,

            'longitude' => 96.1735,

            'chosen_services' => [
                [
                    'service_id' => $service5->id,

                    'name' => $service5->name,

                    'quantity' => 1,

                    'price' => $service5->base_price,
                ],
            ],

            'total_amount' => $service5->base_price,

            'response_deadline' =>
                now()
                    ->subDay()
                    ->addMinutes(15),

            'status' => 'cancelled',

            'customer_note' =>
                'Customer cancelled the booking.',
        ]);


        $this->createHistory(
            $booking5,
            'pending',
            $customer->user_id,
            'Booking created.'
        );


        /*
        |--------------------------------------------------------------------------
        | IMPORTANT STATUS SPELLING
        |--------------------------------------------------------------------------
        |
        | bookings.status:
        |     cancelled
        |
        | booking_status_history.status:
        |     canceled
        |
        */

        $this->createHistory(
            $booking5,
            'canceled',
            $customer->user_id,
            'Customer cancelled the booking.'
        );


        /*
        |--------------------------------------------------------------------------
        | SUCCESS MESSAGE
        |--------------------------------------------------------------------------
        */

        $this->command->info(
            '5 QuickFix bookings created successfully using existing database data.'
        );
    }


    /**
     * Create booking status history.
     */
    private function createHistory(
        Booking $booking,
        string $status,
        int $updatedBy,
        ?string $note = null
    ): void {

        BookingStatusHistory::create([

            'booking_id' => $booking->id,

            'status' => $status,

            'updated_by' => $updatedBy,

            'note' => $note,

        ]);
    }
}