<h4 class="text-primary">Checkout</h4>

<table class="table">
	<thead>
	<tr>
		<th>#</th>
		<th>Description</th>
		<th>Price</th>
		<th>Action</th>
	</tr>
	</thead>
	<tbody>
	<?php
	foreach ($this->items as $items) {
		foreach ($items as $value) {
			echo '<tr>';
			echo '<td>' . $value['id'] . '</td>';
			echo '<td>' . $value['description'] . '</td>';
			echo '<td>' . $value['amount'] . '$</td>';
			echo '<td><a href="' . URL . 'index/destroy/' . $value['id'] . '" class="btn btn-xs btn-danger">Remove</a></td>';
			echo '</tr>';
		}
	}
	?>
	<tr>
		<td></td>
		<td></td>
		<td><?php echo $this->subtotal; ?>$</td>
		<td></td>
	</tr>
	</tbody>
</table>

<form action="<?php echo URL; ?>index/confirm" method="post">
	<input name="authorization[return_url]" type="hidden" value="<?php echo $this->return_url; ?>"/>
	<input name="authorization[abandon_url]" type="hidden" value="<?php echo $this->abandon_url; ?>"/>
	<input name="authorization[reference_number]" type="hidden" value="<?php echo $this->reference_number; ?>"/>
	<input name="authorization[transaction_amount]" type="hidden" value="<?php echo $this->transaction_amount; ?>"/>
	<input name="authorization[geopay_id_token]" type="hidden" value="<?php echo $this->geopay_id_token; ?>"/>
	<div class="form-group">
		<label for="phone_number">Phone number</label>
		<input name="authorization[phone_number]" type="text" class="form-control" value="996000123456"/>
	</div>
	<input name="authorization[locale]" type="hidden" value="<?php echo $this->locale; ?>"/>
	<input name="authorization[payment_details][description]" type="hidden" value="<?php echo $this->description; ?>"/>
	<?php
	foreach ($this->items as $items) {
		foreach ($items as $value) {
			echo '<input name="authorization[payment_details][items]['.$value["id"].'][amount]" type="hidden" value="'.$value['amount'].'">';
			echo '<input name="authorization[payment_details][items]['.$value["id"].'][description]" type="hidden" value="'.$value['description'].'">';
		}
	}
	?>
	<input name="authorization[payment_details][shipping]" type="hidden" value="<?php echo $this->shipping; ?>"/>
	<input name="authorization[payment_details][subtotal]" type="hidden" value="<?php echo $this->subtotal; ?>"/>
	<input name="authorization[payment_details][tax]" type="hidden" value="<?php echo $this->tax; ?>"/>
	<input type="submit" class="btn btn-primary btn-sm" value="Checkout" />
	<a href="<?php echo URL; ?>index" class="btn btn-primary btn-sm">Continue shopping</a>
</form>