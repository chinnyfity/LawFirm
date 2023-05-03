

<div class="mobile_menu d-sm-none d-block">
	<div class="row">
		<div class="col-2 border-left {{ $page_name == 'add_clients' || $page_name == 'edit_clients' ? 'active' : '' }}"><a href="{{ route('add-clients') }}/"><i class="fa fa-plus-circle"></i> Add</a></div>
		<div class="col-2"><a href="#"><i class="fa fa-bell"></i> Notify</a></div>
		<div class="col-4 {{ $page_name == 'dashboard' ? 'active' : '' }}"><a href="{{ route('dashboard') }}"><i class="fa fa-home"></i> Home</a></div>
		<div class="col-2 {{ $page_name == 'view_clients' ? 'active' : '' }}"><a href="{{ route('view-clients') }}/"><i class="fa fa-eye"></i> View</a></div>
		<div class="col-2 border-right"><a href="#"><i class="fa fa-power-off"></i> Logout</a></div>
	</div>
</div>


<div class="footer d-lg-block d-none">
	<div class="copyright">
		<p>Copyright © CompanyName 2023</p>
	</div>
</div>

</div>
	
	<script src="{{ asset('js/jquery.min.js') }}"></script>
	
    <script src="{{ asset('vendors/global/global.min.js') }}"></script>
    <script src="{{ asset('js/custom.min.js') }}"></script>
	<script src="{{ asset('js/deznav-init.js') }}"></script>

	<script src="{{ asset('js/jquery.dataTables1.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.responsive.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
	
	<script src="{{ asset('js/jscripts.js') }}"></script>
	

@php
	$columns = "";
	$columns2 = "";
	$second_route = "";

	if($page_name == "view_clients"){
		$columns = "
		{data: 'firstname', name: 'firstname'},
		{data: 'email', name: 'email'},
		{data: 'phone', name: 'phone'},
		{data: 'dob', name: 'dob'},
		{data: 'staff_id', name: 'staff_id'},
		{data: 'pics', name: 'pics'},
		{data: 'legal_counsel', name: 'legal_counsel'},
		{data: 'date_profiled', name: 'date_profiled'},
		{data: 'case_details', name: 'case_details'},
		{data: 'updated_at', name: 'updated_at'},
		{data: 'created_at', name: 'created_at'},
		{data: 'action', name: 'action'},";
	}
	
@endphp


<script>
	var page_names1 = page_name+"_";
	var table = $('#view_clients').DataTable({
		processing: true,
		serverSide: false,
		ordering: false,
		paging: true,
		orderClasses: false,
		pageLength: 20,
		ajax: site_url + "/dashboard/"+page_names1,
		columns: [
			{data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
			<?=$columns?>
		],
	});
</script>

</body>

</html>