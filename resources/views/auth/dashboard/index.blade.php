@include('auth.dashboard.header')
		
	<div class="content-body mt--30 mb-50 mt-xs-10">
		<div class="container-fluid">
			
			<h3>Hello {{ ucwords($user->firstname.' '.$user->lastname) }}</h3>
			<div class="mt-10" style="color:#444; font-size:15px">Welcome to your dashboard. You have <a href="javascript:;" style="color:#915433;">8 new notifications</a></div>

			<div class="row mt-30 mt-xs-20">
				<div class="col-md-12 mb-30 pl-30 pr-30 pl-xs-15 pr-xs-15 d-sm-block d-none">
					<div class="row text-center captions_div">
						<div class="col-4 pl-0 pr-0 pl-sm-10 pr-sm-10 pl-xs-0 pr-xs-0 border_right">
							STATISTICS
						</div>
						<div class="col-4 pl-0 pr-0 pl-sm-10 pr-sm-10 pl-xs-0 pr-xs-0 border_right">
							LAW
						</div>
						<div class="col-4 pl-0 pr-0 pl-sm-10 pr-sm-10 pl-xs-0 pr-xs-0">
							NOTIFICATION
						</div>
					</div>
				</div>

				<div class="col-md-4 pr-30 pl-xs-15 pr-xs-15 mb-xs-30">
					<div class="wallet-card bg-gray1 pt-20" style="height:200px">
						<p style="color:#222;font-weight:600;font-size:17px!important;">Statistics</p>
						<div class="head mt--10">
							<p class="mt-10 mb-0">Our Total Staffs</p>
							<span>{{ $no_of_staff }}</span>

							<p class="mt-10 mt-xs-0 mb-0">Our Total Clients</p>
							<span>{{ $no_of_clients }}</span>
						</div>
					</div>
				</div>

				<div class="col-md-4 pl-0 pr-0 pl-xs-15 pr-xs-15">
					<div class="pl-0 pr-0 text-center profile_info">
						<img src="{{ asset('images/law.jpg') }}" id="img_photo">
					</div>	
				</div>

				<div class="col-md-4 pl-30 pl-sm-10 pr-sm-10 pl-xs-15 pr-xs-15 mt-xs-30 trans_details">
					<div class="wallet-card pl-10 pr-10 bg-gray1" style="width:100%; height:200px">
						<div class="titles pl-5 pt-10">Notification</div>
						<div class="pl-5" style="font-size:14px;color:#444;margin:-10px 0 12px 0">{{ $no_pics }} client(s) don't have their passport uploaded. The system is programmed to send them email every 3 days to have them submit their passport photograph.</div>
						</div>
					</div>
				</div>
			</div>


			<div class="row mt-10 mt-xs-15 pl-30 pr-30 pl-xs-15 pr-xs-15 mb-xs-100">
				<div class="col-sm-12 pl-xs-10 pr-xs-10">
					<div class="table-hover fs-14 mt-20 short_table short_table2">
						<div class="row mb-xs-15">
							<div class="col-lg-10 col-md-8 col-7 pr-xs-0">
								<h4 class="fs-18">Last 6 clients created</h4>
							</div>
							<div class="col-lg-2 col-md-4 col-5 pr-0 pr-sm-20 pl-xs-0">
								<h4><a href="{{ route('view-clients') }}/" style="font-size:14px">View more</a></h4>
							</div>
						</div>

						<div class="row pl-20 pr-20 pb-10 mt-10 mb-10 headers d-lg-flex d-none">
							<div class="col-sm-2 col-12 pr-0">Photo</div>
							<div class="col-sm-4 col-12 pl-0 pl-xs-10">Clients</div>
							<div class="col-sm-3 col-12">Phone</div>
							<div class="col-sm-3 col-12">Legal Counsel</div>
						</div>

						@if(count($clients) > 0)
							@foreach($clients as $client)
								@php
								if($client->pics == ""){
									$imgs1 = asset('images/no_passport.jpg');
								}else{
									$imgs1 = asset('clients_profile/'.$client->pics);
								}
								@endphp

								<div class="row pl-20 pb-10">
									<div class="col-lg-1 col-sm-2 col-2 pr-xs-0"><img src="{{ $imgs1 }}" class="img-thumbnail round"></div>
									<div class="col-lg-5 col-sm-4 col-5 mt-xs-5" style="line-height:20px">
										{{ ucfirst($client->firstname) }} {{ ucfirst($client->lastname) }}
									</div>
									<div class="col-sm-3 col-3 mt-xs-5">{{ $client->phone }}</div>
									<div class="col-sm-3 col-5 d-lg-block d-none">{{ ucwords($client->legal_counsel) }}</div>
								</div>
							@endforeach
						@endif
					</div>
				</div>
			</div>			
		</div>

	</div>

@include('auth.dashboard.footer')