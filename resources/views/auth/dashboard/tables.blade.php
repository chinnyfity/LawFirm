@include('auth.dashboard.header')

<div class="content-body mt--40 mt-sm-0 mt-xs--20">
	<div class="container-fluid pl-xs-15 pr-xs-15">
		<div class="row">
			<div class="col-xl-12 pl-0 pr-0 pl-xs-5 pr-xs-5">
				<div class="card_">
					<div class="card-body_ pl-0 pr-0 pt-xs-20">
						@if($page_name == "view_clients")
							<div class="col-lg-12">
								<div class="card card-body pl-xs-5 pr-xs-5" style="border-radius:10px">
									<p style="font-size:16px;color:#AE653E;">Touch the blue <b>+</b> circle to bring out all details</p>
									<div class="table-responsive">
										<table id="view_clients" class="table table-striped table-bordereds display responsive wrap all_table" cellspacing="0">
											<thead>
												<tr>
													<th>#</th>
													<th>Fullnames</th>
													<th>Email</th>
													<th>Phone</th>
													<th>DOB</th>
													<th class="none">Created By</th>
													<th class="none">Profile Photo</th>
													<th>Legal Counsel</th>
													<th class="none">Date Profiled</th>
													<th class="none">Case Details</th>
													<th class="none">Date Updated</th>
													<th>Date Created</th>
													<th>Action</th>
												</tr>
											</thead>

											<tbody>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@include('auth.dashboard.footer')