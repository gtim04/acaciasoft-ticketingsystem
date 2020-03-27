@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                @include('layouts.cardnavs')
                <div class="card-body">
                    <h5>Assignment Pool</h5>
                    <hr>
                    @include('layouts.table')
                    <hr>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="show_tic" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="ticketForm">
                    @csrf
                    <input type="hidden" id="tid">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" placeholder="e.g: [HELP] Server is down" required>
                    </div>
                    <div class="form-group">
                        <label for="importance">Importance</label>
                        <select class="form-control" id="importance" disabled>
                            <option>Urgent</option>
                            <option>High</option>
                            <option>Low</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date">Date:</label>
                        <input type="date" class="form-control" id="date" readonly>
                    </div>
                    <div class="form-group">
                        <label for="time">Time:</label>
                        <input type="time" class="form-control" id="time" readonly>
                    </div>
                    <div class="form-group">
                        <label for="pbody">Description</label>
                        <div class="card-body border " id="pbody"></div>
                        <small id="uplog"></small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a href="#" class="thread btn btn-dark">Show Thread</a>
            </div>
        </div>
    </div>
</div><!-- modal end -->

@endsection

@push('scripts')
<!-- Data Tables -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.20/b-1.6.1/b-print-1.6.1/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/b-1.6.1/b-print-1.6.1/datatables.min.js"></script>

<script>
    $(document).ready(function(){

        $('#table-tickets').DataTable({
            processing: true,
            serverSide: true,
            "ajax": {
                "url": "{!! route('admin.assignment') !!}",
                "type": "POST"
            },
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
                data: 'viewBtn', name: 'viewBtn' 
            }],

        });

        $('#table-tickets').on('click', '.view', function(){ 
            var ticketCode = $(this).closest('tr').find('.sorting_1').text();

            $.post('{!! route('admin.sticket') !!}',
            {
                code: ticketCode
            },
            function(data){
                var ticket = JSON.parse(data);
                // alert(ticket['code']);
                $('#show_tic').modal({
                    show: true,
                    backdrop: 'static',
                    keyboard: false
                });
                $('.modal-title').html("Ticket: " +ticket['code']);
                $('#title').val(ticket['title']);
                $('#pbody').html(ticket['description']);
                $('#date').val(moment(ticket['issue_date']).format('YYYY-MM-DD'));
                $('#time').val(moment(ticket['issue_date']).format('HH:mm'));
                $('#tid').val(ticket['id']);
                $('#uplog').text("Last updated: " +moment(ticket['updated_at']).format('lll'));
            });
        });

        $("#show_tic").on('click', '.thread', function(){
            $(this).attr('href', '/admin/showthread/' + $('#tid').val());
        });
    });
</script>
@endpush