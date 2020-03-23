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
                        <h5>Ticket Pool</h5>
                        <hr>

                        <div>
                            <table class="table table-bordered bg-light" id="ticket-table">
                                <thead>
                                    <tr>
                                        <th>Ticket code</th>
                                        <th>Created by</th>
                                        <th>Importance</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Submitted At</th>
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
                        <select class="form-control" id="importance">
                            <option>Urgent</option>
                            <option>High</option>
                            <option selected>Low</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date">Date:</label>
                        <input type="date" class="form-control" id="date" required>
                    </div>
                    <div class="form-group">
                        <label for="time">Time:</label>
                        <input type="time" class="form-control" id="time" required>
                    </div>
                    <div class="form-group">
                        <label for="pbody">Description</label>
                        <textarea class="form-control" id="pbody" rows="3" required></textarea>
                        <small id="uplog"></small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="resolved btn btn-success">Issue resolved</button>
                <button type="button" class="edit btn btn-primary">Save changes</button>
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#ticket-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.tickets') !!}',
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
                data: 'pickBtn', name: 'pickBtn' 
            }],

        });

        $('#ticket-table').on('click', '.pick', function(){ 
            var ticketCode = $(this).closest('tr').find('.sorting_1').text();
            alert(ticketCode);
            // $.post('{!! route('user.sticket') !!}',
            // {
            //     code: ticketCode
            // },
            // function(data){
            //     var ticket = JSON.parse(data);
            //     // alert(ticket['code']);
            //     $('#show_tic').modal({
            //         show: true,
            //         backdrop: 'static',
            //         keyboard: false
            //     });
            //     $('.modal-title').html("Ticket: " +ticket['code']);
            //     $('#title').val(ticket['title']);
            //     $('#pbody').val(ticket['description']);
            //     $('#date').val(moment(ticket['issue_date']).format('YYYY-MM-DD'));
            //     $('#time').val(moment(ticket['issue_date']).format('HH:mm'));
            //     $('#tid').val(ticket['id']);
            //     $('#uplog').text("Last updated: " +moment(ticket['updated_at']).format('lll'));
            // });
        });

        // $('#show_tic').on('click', '.edit', function(){
        //     var title, date, importance, date, time, pbody, id;

        //     title = $('#title').val();
        //     importance = $('#importance').val();
        //     date = $('#date').val();
        //     time = $('#time').val();
        //     body = $('#pbody').val();
        //     id = $('#tid').val();

        //     $('#show_tic').modal('hide');

        //     if(title !== '' && importance !== '' && date!== '' && time !== '' && body !== ''){
        //         $.post('{!! route('user.edit') !!}',
        //         {
        //             title: title,
        //             importance: importance,
        //             date: date,
        //             time: time,
        //             body: body,
        //             id: id
        //         },
        //         function(){
        //             $('#success_tic').modal({
        //                 show: true,
        //                 backdrop: 'static',
        //                 keyboard: false
        //             });
        //         });
        //         e.preventDefault();
        //     }
        // });

        // $('#show_tic').on('click', '.resolved', function(){

        //     var id = $('#tid').val();

        //     $('#show_tic').modal('hide');


        //         $.post('{!! route('user.resolved') !!}',
        //         {
        //             id: id
        //         },
        //         function(){
        //             $('#success_tic').modal({
        //                 show: true,
        //                 backdrop: 'static',
        //                 keyboard: false
        //             });
        //         });
        //         e.preventDefault();
        // });
    });
</script>
@endpush