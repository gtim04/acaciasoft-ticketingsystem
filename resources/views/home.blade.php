@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    <h4>Welcome! This is your current tickets.</h4>
                    <table class="table table-bordered" id="ticket-table">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Created by</th>
                                <th>Description</th>
                                <th>Handler</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function(){
    $('#ticket-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('datatables.ticket') !!}',
        columns: [
            { data: 'code', name: 'code' },
            { data: 'user.name', name: 'name' },
            { data: 'description', name: 'description' },
            { data: 'ticket_handler', name: 'ticket_handler' },
            { data: 'created_at', name: 'created_at' }
        ]
    });
});
</script>
@endpush