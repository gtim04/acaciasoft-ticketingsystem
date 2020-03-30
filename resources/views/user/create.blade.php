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
							<div class="title form-group">
								<label for="title">Title</label>
								<input type="text" class="form-control" id="title" placeholder="e.g: [HELP] Server is down">
							</div>
							<div class="importance form-group">
								<label for="importance">Importance</label>
								<select class="form-control" id="importance">
									<option>Urgent</option>
									<option>High</option>
									<option selected>Low</option>
								</select>
							</div>
							<div class="date form-group">
								<label for="date">When did this happened?</label>
								<input type="date" class="form-control" id="date">
							</div>
							<div class="time form-group">
								<label for="time">Around what time?</label>
								<input type="time" class="form-control" id="time">
							</div>
							<div class="description form-group">
								<label for="description">Body</label>
								<div id="description" style="height: 200px"></div>
							</div>
							<div class="form-group mt-4">
								<a href="{{ route('user.dashboard') }}" class="btn btn-danger float-left">Discard Ticket</a>
								<button id="submit" class="btn btn-success float-right">Submit Ticket</button>
							</div>
						</form>
					</div>
					
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
	$.fn.findOrAppend = function(selector, content) {
	    var elements = this.find(selector);
	    return elements.length ? elements : $(content).appendTo(this);
	}

	$(document).ready(function(){
		//setmax dates
		$('#date').attr('max', moment().format("YYYY-MM-DD"));
		$('#date').attr('min', moment().subtract(1, 'months').format("YYYY-MM-DD"));

		$("#submit").on("click", function(e){
			e.preventDefault();
			var fields = ['title', 'date', 'importance', 'time', 'description'];
			title = $('#title').val();
			importance = $('#importance').val();
			date = $('#date').val();
			time = $('#time').val();
			body = quill.root.innerHTML;

			$.post('{!! route('user.submit') !!}',
			{
				title: title,
				importance: importance,
				date: date,
				time: time,
				description: body
			},
			function(data){
				$('#success_tic').modal({
					show: true,
					backdrop: 'static',
					keyboard: false
				});
			})
			.done(function(data){
				setTimeout(function(){ 
					window.location = data; 
				}, 3000);
			})
			.fail(function(err){
				$('.errors').remove();
				$.each(fields, function(index, value){
					$('#'+value).removeClass('border border-danger');
				});
				$.each(err.responseJSON.errors, function(key, value){
					$('.'+key).findOrAppend('.errors', '<small class="errors alert-danger">'+value+'</small>');
					$('#'+key).addClass('border border-danger');
				});
			});

		});
	});

	//texteditor
	var quill = new quill('#description', {
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
