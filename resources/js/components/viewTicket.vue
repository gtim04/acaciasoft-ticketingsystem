<template>
	<div>
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

							<div class="form-group">
								<label for="title">Title</label>
								<input type="text" class="form-control" id="title" placeholder="e.g: [HELP] Server is down" required readonly>
							</div>
							<div class="form-group">
								<label for="importance">Importance</label>
								<select class="form-control" id="importance" readonly>
									<option>Urgent</option>
									<option>High</option>
									<option selected>Low</option>
								</select>
							</div>
							<div class="form-group">
								<label for="date">Date:</label>
								<input type="date" class="form-control" id="date" required readonly>
							</div>
							<div class="form-group">
								<label for="time">Time:</label>
								<input type="time" class="form-control" id="time" required readonly>
							</div>
							<div class="form-group">
								<label for="pbody">Description</label>
								<textarea class="form-control" id="pbody" rows="3" placeholder="What can we help you with?" required readonly></textarea>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary">Save changes</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div><!-- modal end -->
	</div>
</template>

<script>
	export default {
		mounted() {
			console.log('Component mounted.')
		}
	}
</script>