<h4><?php echo $this->description; ?></h4>

<table class="table table-hover">
	<thead>
	<tr>
		<th>#</th>
		<th>Description</th>
		<th>Price</th>
	</tr>
	</thead>

	<tbody>

	<?php
	$a = 1;
	foreach ($this->params['payment_details']['items'] as $value) {
		echo '<tr>';
		echo '<td>' . $a++ . '</td>';
		echo '<td>' . $value['description'] . '</td>';
		echo '<td>' . $value['amount'] . '$</td>';
		echo '</tr>';
	}
	?>
</table>

<p><strong>Subtotal: </strong><?php echo $this->params['payment_details']['subtotal']; ?>$</p>
<p><strong>Tax: </strong><?php echo $this->params['payment_details']['tax']; ?>$</p>
<p><strong>Shipping: </strong><?php echo $this->params['payment_details']['shipping']; ?>$</p>
<p><strong>Phone number: </strong><?php echo $this->params['phone_number']; ?></p>
<p><strong>Total amount: </strong><?php echo $this->params['transaction_amount']; ?></p>

<form role="form" action="<?php echo AUTH_URL; ?>customer/authorizations" method="post">
	<input name="authorization[geopay_signature]" type="hidden" value="<?php echo $this->geopay_signature; ?>"/>
	<input name="authorization[return_url]" type="hidden" value="<?php echo $this->params['return_url']; ?>"/>
	<input name="authorization[abandon_url]" type="hidden" value="<?php echo $this->params['abandon_url']; ?>"/>
	<input name="authorization[reference_number]" type="hidden" value="<?php echo $this->params['reference_number']; ?>"/>
	<input name="authorization[transaction_amount]" type="hidden" value="<?php echo $this->params['transaction_amount']; ?>"/>
	<input name="authorization[geopay_id_token]" type="hidden" value="<?php echo $this->params['geopay_id_token']; ?>"/>
	<input name="authorization[phone_number]" type="hidden" value="<?php echo $this->params['phone_number']; ?>"/>
	<input name="authorization[locale]" type="hidden" value="<?php echo $this->params['locale']; ?>"/>

	<input name="authorization[payment_details[description]]" type="hidden" value="<?php echo $this->description; ?>"/>
	<input name="authorization[payment_details[subtotal]]" type="hidden" value="<?php echo $this->params['payment_details']['subtotal']; ?>"/>
	<input name="authorization[payment_details[tax]]" type="hidden" value="<?php echo $this->params['payment_details']['tax']; ?>"/>
	<input name="authorization[payment_details[shipping]]" type="hidden" value="<?php echo $this->params['payment_details']['shipping']; ?>"/>
	<?php
	foreach ($this->params['payment_details']['items'] as $value) {
		echo '<input name="authorization[payment_details][items][][description]" type="hidden" value="' . $value['description'] . '" />';
		echo '<input name="authorization[payment_details][items][][amount]" type="hidden" value="' . $value['amount'] . '" />';
	}
	?>
	<input type="submit" class="btn btn-primary btn-sm" value="Pay Now with GeoPay"/>
	<a href="<?php echo URL; ?>index" class="btn btn-primary btn-sm">Continue shopping</a>
</form>



