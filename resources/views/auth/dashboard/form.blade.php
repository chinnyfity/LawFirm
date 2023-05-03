@include('auth.dashboard.header')

<div class="content-body mt--40 mt-sm-0 mt-xs--20">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xl-11 col-lg-11 col-sm-12 pl-xs-5 pr-xs-5">
				<div class="card">
					<div class="card-body pl-xs-10 pr-xs-10">
						<div class="tab-content">
							<div class="div_profile pb-30">
								<form class="form_clients" autocomplete="off">
									{{ csrf_field() }}

									@if($client_details)
										<a href="{{ route('view-clients') }}/" class="goback">Go Back</a>
									@endif
									
									<h3 class="mt-xs-20">{{ $client_details ? 'Edit' : 'Enter' }} Clients Profile</h3>
									<p class="mb-30" style="font-size:15px;margin-top:-6px;">All fields marked star <span class="star">*</span> are compulsory</p>

									@php
									$imgs = asset('images/no_passport.jpg');
									$imgs2 = $imgs;
									$isFile=0;

									if($client_details){
										if($client_details->pics != NULL){
											$imgs = asset('clients_profile/'.$client_details->pics);
											$isFile=1;
										}
									}
									@endphp

									<input type="hidden" name="client_id" class="client_id" value="{{ $client_details ? $client_details->uuid : '' }}">

									<div class="row mt-5">
										<div class="col-md-3 col-sm-12 mb-30" style="text-align:center;">
											<p style="color:#915433;line-height:18px;font-size:14px;margin:0 0 6px 0">Client's profile photo is optional</p>
											<div id="img_preview" class="profile_pic">
												<span>remove</span>

												<div class="col-12 text-center img_photo">
													<img src="{{ $imgs }}" src1="{{ $imgs2 }}" id="img_photo">
												</div>

												<div class="col-12">
													<input type="file" name="img_picture" id="img_picture" accept=".jpg, .jpeg" />
												</div>

												<p id="hide_basic_uploader">Click to hide this</p>
											</div>

											<input type="hidden" id="isPhotos" name="isPhotos" value="{{ $isFile }}">

											<input type="hidden" id="txt_file" name="txt_file" value="{{ $client_details ? $client_details->pics : '' }}">

											<p class="pic_info">
												<span class="basic_uploader">Touch the circle above Or <span style="color:#915433">click here</span> to try the simple uploader</span>
											</p>
										</div>
										
										<div class="col-md-9 mb-xs-40">
											<div class="row">
												<div class="mb-3 col-md-6 pr-10 pr-xs-15">
													<label class="form-label">Firstname <span class="star">*</span></label> 
													<input type="text" placeholder="Enter clients firstname" class="form-control fname" name="fname" style="text-transform: capitalize" value="{{ $client_details ? ucwords($client_details->firstname) : '' }}">
												</div>

												<div class="mb-3 col-md-6 pl-10 pl-xs-15">
													<label class="form-label">Lastname <span class="star">*</span></label>
													<input type="text" placeholder="Enter clients lastname" class="form-control lname" name="lname" style="text-transform: capitalize" value="{{ $client_details ? ucwords($client_details->lastname) : '' }}">
												</div>
											</div>

											<div class="row">
												<div class="mb-3 col-md-6 pr-10 pr-xs-15">
													<label class="form-label">Email <span class="star">*</span></label>
													<input type="email" placeholder="Enter clients email" class="form-control email" name="email" value="{{ $client_details ? strtolower($client_details->email) : '' }}">
												</div>
												<div class="mb-3 col-md-6 pl-10 pl-xs-15">
													<label class="form-label">Phone </label>
													<input type="tel" placeholder="Enter clients phone" class="form-control phone" name="phone" value="{{ $client_details ? ucwords($client_details->phone) : '' }}">
												</div>
											</div>

											<div class="row">
												<div class="mb-3 col-md-6 pr-10 pr-xs-15">
													<label class="form-label">Primary Legal Counsel <span class="star">*</span></label>
													<input type="text" placeholder="Enter Legal Counsel" class="form-control legal_counsel" name="legal_counsel" style="text-transform: capitalize" value="{{ $client_details ? ucwords($client_details->legal_counsel) : '' }}">
												</div>
												<div class="mb-3 col-md-6 pl-10 pl-xs-15">
													<label class="form-label">Date of Birth <span class="star">*</span></label>
													<input type="date" class="form-control dob" name="dob" value="{{ $client_details ? date('Y-m-d', strtotime($client_details->dob)) : '' }}">
												</div>
											</div>

											<div class="row">
												<div class="mb-3 col-md-12">
													<label class="form-label">Case Details <span class="star">*</span></label>
													<textarea class="form-control case_details" name="case_details" placeholder="Enter the client's case details" style="height:12em!important">{{ $client_details ? nl2br($client_details->case_details) : '' }}</textarea>
												</div>
											</div>

											@php
											$current_date = date("Y-m-d", time());
											if($client_details){
												$current_date = date('Y-m-d', strtotime($client_details->date_profiled));
											}
											@endphp

											<div class="row">
												<div class="mb-3 col-md-12">
													<label class="form-label">Date Profiled <span class="star">*</span></label>
													<input type="date" class="form-control profiled" name="profiled" value="{{ $current_date }}">
												</div>
											</div>
											
											<button class="mt-10 btn-pad btn btn-md btn-primary add_clients" type="submit">{{ $client_details ? 'Update' : 'Add' }} Client Profile</button>
										</div>
									</div>											
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@include('auth.dashboard.footer')