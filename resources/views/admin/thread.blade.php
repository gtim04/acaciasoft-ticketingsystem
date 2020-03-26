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
							<div id="texteditor">
								<div class="form-group">
									<label for="editor">Type your comment here:</label>
									<div id="editor" style="height: 200px"></div>
								</div>
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
						<div class="card-body border " id="pbody"></div>
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
			// alert('test');
			var jsonData = data;
			var convo = '';
			// console.log(jsonData);
			// // loading convo
			if(jsonData.length > 0){
				$.each(jsonData, function(index){
					convo += ('<div class="container col-md-12 p-2"> <div class="card"> <div class="'+ (jsonData[index]['user']['id'] == userid ? 'card-header bg-success' : 'card-header bg-info') +'"><h6>'+(jsonData[index]['user']['id'] == userid ? 'You said:' : ''+jsonData[index]['user']['name']+' said:')+'</h6></div> <div class="card-body">'+jsonData[index]['content']+' <footer class="mt-1 blockquote-footer">'+moment(jsonData[index]['created_at']).format('lll')+'</footer> </div></div></div>');
				});
				$("#convo").html(convo);
				$("#tcode").html('Thread for: <button id="view" class="bg-light">' +jsonData[0]['ticket']['code']+'</button>');
				$("#lastUp").html('Last updated: ' +moment(jsonData[0]['created_at']).format('lll'));
			}  else {
				$("#tcode").html('No thread created yet for this ticket comment below to start.');
				$("#convo").html('No comments yet');
			}
			
			if(jsonData[0]['ticket']['isCompleted'] !== 0 || jsonData[0]['ticket']['isDeleted'] !== 0){
				$('#resolved').html('Re-open this ticket?');
				$('#texteditor').remove();
				$('#submit').remove();
			}
		});

		//submitting comment
		$("#submit").on("click", function(e){
			var textarea = quill.root.innerHTML;
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
                $('#pbody').html(ticket['description']);
                $('#date').val(moment(ticket['issue_date']).format('YYYY-MM-DD'));
                $('#time').val(moment(ticket['issue_date']).format('HH:mm'));
                $('#tid').val(ticket['id']);
                $('#uplog').text("Last updated: " +moment(ticket['updated_at']).format('lll'));
            });
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