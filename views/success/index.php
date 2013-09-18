<h4 class="text-info">Checkout page</h4>

<form role="form" action="<?php echo URL; ?>index/execute" method="post">
	<input id="transaction_number" name="transaction_number" type="hidden" value="<?php echo $_GET['transaction_number'] ?>">

	<div class="form-group">
		<label for="amount">Amount</label>
		<input name="amount" type="text" class="form-control" placeholder="Enter amount" />
	</div>

	<input type="submit" class="btn btn-primary btn-sm" value="Checkout" />
</form>