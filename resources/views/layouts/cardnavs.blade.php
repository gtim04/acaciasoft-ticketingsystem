@if (session('status'))
<div class="alert alert-success" role="alert">
	{{ session('status') }}
</div>
@endif

@if(auth()->user()->role == 'admin')
<div class="card-header">
	<ul class="nav nav-tabs card-header-tabs">
		<li class="nav-item">
			<a class="{{ Request::path() === 'admin' ? 'nav-link active' : 'nav-link'}}" href="{{ route('admin.dashboard') }}">Open tickets</a>
		</li>
		<li class="nav-item">
			<a class="{{ Request::path() === 'admin/showassigned' ? 'nav-link active' : 'nav-link'}}" href="{{ route('admin.sassignment') }}">Assigned tickets</a>
		</li>
		<li class="nav-item">
			<a class="{{ Request::path() === 'admin/showresolved' ? 'nav-link active' : 'nav-link'}}" href="{{ route('admin.sresolved') }}">Resolved tickets</a>
		</li>
		<li class="nav-item">
			<a class="{{ Request::path() === 'admin/showdeleted' ? 'nav-link active' : 'nav-link'}}" href="{{ route('admin.sdeleted') }}">Deleted Pool</a>
		</li>
	</ul>
</div>
@elseif(auth()->user()->role == 'client')
<div class="card-header">
	<ul class="nav nav-tabs card-header-tabs">
		<li class="nav-item">
			<a class="{{ Request::path() === 'user' ? 'nav-link active' : 'nav-link'}}" href="{{ route('user.dashboard') }}">Open tickets</a>
		</li>
		<li class="nav-item">
			<a class="{{ Request::path() === 'user/showpending' ? 'nav-link active' : 'nav-link'}}" href="{{ route('user.spending') }}">Pending tickets</a>
		</li>
		<li class="nav-item">
			<a class="{{ Request::path() === 'user/showresolved' ? 'nav-link active' : 'nav-link'}}" href="{{ route('user.sresolved') }}">Resolved tickets</a>
		</li>
		<li class="nav-item">
			<a class="{{ Request::path() === 'user/showdeleted' ? 'nav-link active' : 'nav-link'}}" href="{{ route('user.sdeleted') }}">Deleted tickets</a>
		</li>
	</ul>
</div>
@endif