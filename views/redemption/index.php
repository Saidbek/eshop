<h4 class="text-primary">Value Redemption / Create</h4>

<form action="<?php echo URL; ?>redemption/create" method="post">
	<div class="form-group">
		<label for="phone_number">Phone number</label>
		<input name="phone_number" type="text" class="form-control" placeholder="Enter your phone number" value="996000123456"/>
	</div>

	<div class="form-group">
		<label for="requested_amount">Amount</label>
		<input name="requested_amount" type="text" class="form-control" placeholder="Enter amount" value="10">
	</div>

	<div class="form-group">
		<label for="reference_number">Reference number</label>
		<input name="reference_number" type="text" class="form-control" value="<?php echo uniqid(); ?>">
	</div>

	<input type="submit" class="btn btn-primary btn-sm" value="Submit" />
</form>
