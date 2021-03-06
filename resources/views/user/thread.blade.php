@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">
					@foreach ($data as $ticket)
						<h4><b>Thread for:</b> <button id="view" class="bg-light">{{$ticket->ticket->code}}</button></h4>
						<input type="hidden" id="ticketid" value="{{$ticket->ticket->id}}">
						</div>
						<div class="card-body">
							<div>
								<h5><b>Ticket Title: {{$ticket->ticket->title}}</b></h5>
						</div>
						<hr>
						@break
					@endforeach
				
					<div id="convo">
						@foreach ($data as $comment)
							@if(count($data) > 0)
								@if($comment->isLog == 1)
									<div class="{{$comment->user->id === auth()->user()->id ? 'p-1 container text-right' : 'p-1 container'}}"><small class="bg-light"><b><i>*{{$comment->content}} at {{$comment->created_at}}</i></b></small></div>
								@else
									<div class="container col-md-12 p-2"> <div class="card"> <div class="{{$comment->user->id === auth()->user()->id ? 'card-header bg-success' : 'card-header bg-info'}}"><h6>{{$comment->user->id === auth()->user()->id ? 'You said:' : $comment->user->name.' said:'}}</h6></div><div class="card-body">{!! $comment->content !!}<footer class="mt-1 blockquote-footer">{{$comment->created_at}}</footer></div></div></div>
								@endif
							@endif
						@endforeach
					</div>
					<div class="row justify-content-center">{{ $data->links() }}</div>
					<div class="text-center">
						@foreach ($data as $ticket)
							@if($ticket->ticket->status == 'deleted' || $ticket->ticket->status == 'resolved')
								<button id="resolved" class="mt-3 btn btn-success">Re-open ticket?</button>
							@else
								<button id="resolved" class="mt-3 btn btn-success">Issue Resolved?</button>
							@endif
							@break
						@endforeach
					</div>
					<hr>
					<div>
						<form>
							@foreach ($data as $ticket)
								@if($ticket->ticket->status == 'deleted' || $ticket->ticket->status == 'resolved')
								@else
									<div id="texteditor">
										<div class="form-group">
											<label for="editor">Type your comment here:</label>
											<div id="editor" style="height: 200px"></div>
										</div>
									</div>
									<button id="submit" class="btn btn-primary float-right">Submit</button>
								@endif
								@break
							@endforeach
							<a href="{{ route('user.dashboard') }}" class="btn btn-danger">Back to your tickets</a>
						</form>
					</div>
					<div class="text-center">
						<a href="#" class="link">Back to top</a>
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
						<div class="card-body border " id="pbody"></div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div><!-- modal end -->

<input type="hidden" id="userid" value="{{auth()->user()->id}}">

@endsection

@push('scripts')
<script>
	$(document).ready(function () {
		//variable dec
		var id = $('#ticketid').val(); 
		var userid = $("#userid").val();

		//submit comment
		$("#submit").on("click", function(e){
			var textarea = quill.root.innerHTML;
			if(textarea !== ''){
				$(this).attr('disabled', true);
				$(this).html('submitting');
				$.post('{!! route('user.comment') !!}',
				{
					comment: textarea,
					id: id
				},
				function(data){
					setTimeout(function(){ 
						window.location.replace(data);
					}, 900);
				});
				e.preventDefault();
			}
		});

		//viewing of ticket
		$('.card').on('click', '#view', function(){ 
			var ticketCode = $('#view').html();
			$.post('{!! route('user.sticket') !!}',
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
				$('#pbody').html(ticket['description']);
				$('#date').val(moment(ticket['issue_date']).format('YYYY-MM-DD'));
				$('#time').val(moment(ticket['issue_date']).format('HH:mm'));
				$('#tid').val(ticket['id']);
				$('#uplog').text("Last updated: " +moment(ticket['updated_at']).format('lll'));
			});
		});
		//resolving the issue
		$('#resolved').on('click', function(){ 
			if($(this).html() == 'Issue Resolved?'){
				$.post('{!! route('user.complete') !!}',
				{
					id: id
				},
				function(data){
					$('#success_tic').modal({
						show: true,
						backdrop: 'static',
						keyboard: false
					});
				});
			} else {
				$.post('{!! route('user.reopen') !!}',
				{
					id: id
				},
				function(data){
					$('#success_tic').modal({
						show: true,
						backdrop: 'static',
						keyboard: false
					});
				});
			}
		});
	});

	//texteditor
	var quill = new quill('#editor', {
		modules: {
			toolbar: [

			[{ header: [1, 2, false] }],
			['bold', 'italic', 'underline', 'link', 'strike']

			]
		},
		placeholder: 'What can we help you with?',
		theme: 'snow'
	});
</script>
@endpush