@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <div class="container-fluid">
                        <div class="pb-2">
                            <a href="{{ route('index.ticket') }}" class="float-right btn btn-xs btn-success pull-right">Create a ticket</a>
                        </div>
                        <h5>Welcome {{ Auth::user()->name }}! This is your current tickets.</h5>
                        <hr>

                        <div>
                            <table class="table table-bordered" id="ticket-table">
                                <thead>
                                    <tr>
                                        <th>Ticket code</th>
                                        <th>Created by</th>
                                        <th>Importance</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if (Route::has('home'))
<!-- Data Tables -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.20/b-1.6.1/b-print-1.6.1/datatables.min.css"/>

<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/b-1.6.1/b-print-1.6.1/datatables.min.js"></script>
<script>
    $('#ticket-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('datatables.ticket') !!}',
        columns: [{ 
            data: 'code', name: 'code' 
        }, { 
            data: 'user.name', name: 'user.name' 
        }, { 
            data: 'importance', name: 'importance' 
        }, { 
            data: 'title', name: 'title' 
        }, { 
            data: 'status', name: 'status' 
        }, { 
            data: 'created_at', name: 'created_at' 
        }, { 
            defaultContent: '<input type="button" class="view btn-primary" value="View Ticket"/>'
        }],

    });

    $('#ticket-table').on('click', '.view', function(){
        var ticketCode = $(this).closest('tr').find('.sorting_1').text();
        alert(ticketCode);
    });
</script>
@endif
@endpush