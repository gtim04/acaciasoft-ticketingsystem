@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">
					<h4 id="tcode"></h4>
				</div>
				<div class="card-body">
					<div>
						<h5 id="lastUp"></h5>
					</div>
					<hr>
					<div id="convo">
					<!-- card -->
					</div>
					<hr>
					<div>
						<form>
							@csrf
							<div class="form-group">
								<label for="comment">Type your comment here:</label>
								<textarea class="form-control" id="comment" rows="3" required></textarea>
							</div>
							<a href="{{ route('user.dashboard') }}" class="btn btn-danger">Back to your tickets</a>
							<button id="submit" class="btn btn-primary float-right">Submit</button>
						</form>
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
						<textarea class="form-control" id="pbody" rows="3" readonly></textarea>
						<small id="uplog"></small>
					</div>
				</form>
			</div>
		</div>
	</div>
</div><!-- modal end -->
<input type="hidden" id="ticketid" value="{{$data}}">
<input type="hidden" id="userid" value="{{auth()->user()->id}}">
@endsection

@push('scripts')
<script>
	$(document).ready(function () {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		var id = $('#ticketid').val(); 
		var userid = $("#userid").val();
		$.post('{!! route('admin.sthread') !!}',
		{
			id: id
		},
		function(data){
			var jsonData = data;
			var convo = '';

			//loading convo
			if(jsonData.length > 0){
				$.each(jsonData, function(index){
					convo += ('<div class="container col-md-12 p-2"> <div class="card"> <div class="'+ (jsonData[index]['user']['id'] == userid ? 'card-header bg-success' : 'card-header bg-info') +'"><h6>'+(jsonData[index]['user']['id'] == userid ? 'You said:' : ''+jsonData[index]['user']['name']+' said:')+'</h6></div> <div class="card-body">'+jsonData[index]['content']+' <footer class="mt-1 blockquote-footer">'+moment(jsonData[index]['created_at']).format('lll')+'</footer> </div></div></div>');
				});
				$("#convo").html(convo);
				$("#tcode").html('Thread for: <button id="view" class="bg-light">' +jsonData[0]['ticket'][0]['code']+'</button>');
				$("#lastUp").html('Last updated: ' +moment(jsonData[0]['created_at']).format('lll'));
			}  else {
				$("#tcode").html('No thread created yet for this ticket comment below to start.');
				$("#convo").html('No comments yet');
			}

			//testing if ticket is resolved
			if(jsonData[0]['ticket'][0]['isCompleted'] !== 0 || jsonData[0]['ticket'][0]['isDeleted'] !== 0){
				$('#resolved').html('Re-open this ticket?');
				$('#comment').prop('disabled', true);
				$('#submit').prop('disabled', true);
			}
		});

		$("#submit").on("click", function(e){
			var textarea = $('#comment').val();
			if(textarea !== ''){
				$(this).attr('disabled', true);
				$(this).html('submitting');
				$.post('{!! route('admin.comment') !!}',
				{
					comment: textarea,
					id: id
				},
				function(data){
					setTimeout(function(){ 
						location.reload();
					}, 700);
				});
				e.preventDefault();
			}
		});

		//viewing of ticket
		$('.card').on('click', '#view', function(){ 
            var ticketCode = $('#view').html();
            $.post('{!! route('admin.sticket') !!}',
            {
                code: ticketCode
            },
            function(data){
                var ticket = JSON.parse(data);
                $('#show_tic').modal({
                    show: true,
                    backdrop: 'static',
                    keyboard: false
                });
                $('.modal-title').html("Ticket: " +ticket['code']);
                $('#title').val(ticket['title']);
                $('#pbody').val(ticket['description']);
                $('#date').val(moment(ticket['issue_date']).format('YYYY-MM-DD'));
                $('#time').val(moment(ticket['issue_date']).format('HH:mm'));
                $('#tid').val(ticket['id']);
                $('#uplog').text("Last updated: " +moment(ticket['updated_at']).format('lll'));
            });
        });
	});
</script>
@endpush