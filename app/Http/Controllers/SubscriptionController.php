<?php
	
	namespace App\Http\Controllers;
	
	use App\Subscription;
	use Illuminate\Database\Eloquent\Relations\HasOne;
	use Illuminate\Http\Request;
	
	class SubscriptionController extends Controller
	{
		/**
		 * Display a listing of the resource.
		 *
		 * @param \Illuminate\Http\Request $request
		 * @return \Illuminate\Http\Response
		 * @throws \App\Exceptions\WrappedException
		 */
		public function index(Request $request)
		{
			$this->validate($request, [
				'filter' => 'required|in:newspaper,milk',
			]);
			
			$client = $this->getClient();
			
			$subscriptionsBuilder = Subscription::with(['clientSubscription' => function (HasOne $hasOne) use ($client) {
				$hasOne->where('client_id', $client->getKey());
			}]);
			$subscriptions = null;
			if ($request->filter == 'newspaper') {
				$subscriptions = $subscriptionsBuilder->where('type', 'NEWSPAPER')->get();
			} else {
				$subscriptions = $subscriptionsBuilder->where('type', 'MILK')->get();
			}
			
			return $this->collectionResponse($subscriptions);
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
		 */
		public function store(Request $request)
		{
			//
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
		 * @param  int                      $id
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
	}
