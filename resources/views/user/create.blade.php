@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-9">
			<div class="card">
				<div class="card-header">Kindly fill in the information needed.</div>

				<div class="card-body">
					@if (session('status'))
					<div class="alert alert-success" role="alert">
						{{ session('status') }}
					</div>
					@endif

					<div>
						<form id="ticketForm">
							@csrf
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
								<label for="date">When did this happened?</label>
								<input type="date" class="form-control" id="date" required>
							</div>
							<div class="form-group">
								<label for="time">Around what time?</label>
								<input type="time" class="form-control" id="time" required>
							</div>
							<div class="form-group">
								<label for="pbody">Body</label>
								<textarea class="form-control" id="pbody" rows="3" placeholder="What can we help you with?" required></textarea>
							</div>
							<div class="form-group">
								<a href="{{ route('home') }}" class="btn btn-danger float-left">Discard Ticket</a>
								<button id="submit" class="btn btn-success float-right">Submit Ticket</button>
							</div>
						</form>
					</div>

					<!-- Modal -->
					<div id="success_tic" class="modal fade" role="dialog">
						<div class="modal-dialog">

							<!-- Modal content-->
							<div class="modal-content">
								<div class="page-body">
									<div class="head">  
										<h4>Ticket has been submitted!</h4>
										<h6>We are working on it, your patience is appreciated.</h6>
									</div>

									<h1 style="text-align:center;">
										<div class="checkmark-circle">
										<div class="background"></div>
										<div class="checkmark draw"></div>
										</div>
									<h1>
									</div>

									<div class="pb-3 text-center">
										<a href="{{ route('home') }}" class="btn btn-primary">Review your ticket/s.</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
@if (Route::has('store.ticket'))
<script>
	$(document).ready(function(){
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$("#submit").on("click", function(e){

			var title, date, importance, date, time, pbody;

			title = $('#title').val();
			importance = $('#importance').val();
			date = $('#date').val();
			time = $('#time').val();
			body = $('#pbody').val();

			if(title !== '' && importance !== '' && date!== '' && time !== '' && body !== ''){
				$.post('/submitTicket',
				{
					title: title,
					importance: importance,
					date: date,
					time: time,
					body: body
				},
				function(){
					$('#success_tic').modal({
						show: true,
						backdrop: 'static',
						keyboard: false
					});
				});

				e.preventDefault();
			}
		});
	});
</script>
@endif
@endpush
