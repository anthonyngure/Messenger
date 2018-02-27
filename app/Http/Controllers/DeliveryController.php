<?php

namespace App\Http\Controllers;

use App\Client;
use App\Delivery;
use App\DeliveryItem;
use App\Exceptions\WrappedException;
use App\LogHelper;
use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'filter' => 'required|in:pending,complete,rider',
            'month' => 'required_unless:filter,rider',
        ]);

        if ($request->filter == 'rider') {
            return $this->riderDeliveries($request);
        } else {
            return $this->clientDeliveries($request);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws WrappedException
     */
    public function store(Request $request)
    {
        $client = Client::find(Auth::user()->client_id);
        if (is_null($client)) {
            throw new WrappedException("Sorry, you are not associated to any client.");
        }

        \Validator::validate($request->json()->all(), [
            'originName' => 'required',
            'originVicinity' => 'required',
            'originFormattedAddress' => 'required',
            'originLatitude' => 'required|numeric',
            'originLongitude' => 'required|numeric',
            'estimatedCost' => 'required|numeric',
            'estimatedMaxDuration' => 'required|numeric',
            'estimatedMaxDistance' => 'required|numeric',
        ]);

        LogHelper::info($request, "Add Delivery");


        $items = $request->json('items');

        $scheduleDate = $request->json('scheduleDate');
        $scheduleTime = $request->json('scheduleTime');
        $delivery = new Delivery([
            'origin_name' => $request->json('originName'),
            'origin_vicinity' => $request->json('originVicinity'),
            'origin_formatted_address' => $request->json('originFormattedAddress'),
            'origin_latitude' => $request->json('originLatitude'),
            'origin_longitude' => $request->json('originLongitude'),
            'estimated_cost' => $request->json('estimatedCost'),
            'estimated_max_duration' => $request->json('estimatedMaxDuration'),
            'estimated_max_distance' => $request->json('estimatedMaxDistance'),
            'schedule_date' => empty($scheduleDate) ? date("Y-m-d H:i:s") : $scheduleDate,
            'schedule_time' => empty($scheduleTime) ? date("H:i:s") : $scheduleTime,
        ]);


        $client->deliveries()->save($delivery);

        $deliveryItems = array();
        foreach ($items as $item) {
            $deliveryItem = ((object)$item);
            array_push($deliveryItems, new DeliveryItem([
                'courier_option_id' => $deliveryItem->courierOptionId,
                'destination_name' => $deliveryItem->destinationName,
                'destination_vicinity' => $deliveryItem->destinationVicinity,
                'destination_formatted_address' => $deliveryItem->destinationFormattedAddress,
                'destination_latitude' => $deliveryItem->destinationLatitude,
                'destination_longitude' => $deliveryItem->destinationLongitude,
                'recipient_contact' => $deliveryItem->recipientContact,
                'recipient_name' => $deliveryItem->recipientName,
                'quantity' => $deliveryItem->quantity,
                'note' => $deliveryItem->note,
                'estimated_duration' => $deliveryItem->estimatedDuration,
                'estimated_distance' => $deliveryItem->estimatedDistance,
            ]));
        }

        $delivery->items()->saveMany($deliveryItems);

        $data = Delivery::with('items')->find($delivery->getKey());

        return $this->itemCreatedResponse($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function clientDeliveries(Request $request)
    {
        $client = Client::find(Auth::user()->client_id);

        $deliveries = $client->deliveries()
            ->whereHas('items', function (Builder $builder) use ($request) {
                if ($request->filter === 'pending') {
                    $builder->where('status', '!=', 'AT_DESTINATION');
                } else {
                    $builder->where('status', 'AT_DESTINATION');
                }
            })
            ->with(['items' => function (HasMany $hasMany) {
                $hasMany->with(['courierOption' => function (BelongsTo $belongsTo) {
                    $belongsTo->select(['id', 'name', 'plural_name', 'icon']);
                }])->orderByDesc('id');
            }])->orderByDesc('id')
            //->toSql();
            ->get();

        //dd($deliveries);

        $deliveries->each(function (Delivery $delivery) {
            $stats = array();
            $courierOptionGroups = $delivery->items->groupBy('courier_option_id');
            foreach ($courierOptionGroups as $courierOptionGroup) {
                $totalQuantity = 0;
                foreach ($courierOptionGroup as $courierOptionDeliveryItem) {
                    $totalQuantity += $courierOptionDeliveryItem->quantity;
                }
                array_push($stats, [
                    'courierOption' => $courierOptionGroup->first()->courierOption,
                    'count' => $totalQuantity,
                ]);
            }
            $delivery->stats = $stats;
        });

        return $this->collectionResponse($deliveries);
    }

    private function riderDeliveries($request)
    {
        $deliveries = Delivery::whereHas('items', function (Builder $builder) use ($request) {
            $builder->where('status', 'AT_PICKUP');
        })->with(['items' => function (HasMany $hasMany) {
            $hasMany->with(['courierOption' => function (BelongsTo $belongsTo) {
                $belongsTo->select(['id', 'name', 'plural_name', 'icon']);
            }])->orderByDesc('id');
        }])->orderByDesc('id')->get();

        $deliveries->each(function (Delivery $delivery) {
            $stats = array();
            $courierOptionGroups = $delivery->items->groupBy('courier_option_id');
            foreach ($courierOptionGroups as $courierOptionGroup) {
                $totalQuantity = 0;
                foreach ($courierOptionGroup as $courierOptionDeliveryItem) {
                    $totalQuantity += $courierOptionDeliveryItem->quantity;
                }
                array_push($stats, [
                    'courierOption' => $courierOptionGroup->first()->courierOption,
                    'count' => $totalQuantity,
                ]);
            }
            $delivery->stats = $stats;
        });

        return $this->collectionResponse($deliveries);
    }
}
