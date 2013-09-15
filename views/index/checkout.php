<h4>Checkout</h4>

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
	        echo '<td>'. $value['id'] .'</td>';
	        echo '<td>'. $value['description'] .'</td>';
	        echo '<td>'. $value['amount'] .'$</td>';
	        echo '<td><a href="'.URL.'index/destroy/'.$value['id'].'" class="btn btn-xs btn-danger">Remove</a></td>';
	      echo '</tr>';
    	}
  	}
  ?>
  <tr>
    <td></td>
    <td></td>
    <td><?php echo $this->total_amount; ?>$</td>
    <td></td>
  </tr>
  </tbody>
</table>

<a data-toggle="modal" href="#modal" class="btn btn-primary btn-sm">Checkout</a>
<a href="<?php echo URL; ?>index" class="btn btn-primary btn-sm">Continue shopping</a>

<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="confirm" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Confirm page</h4>
      </div>
      <div class="modal-body">

        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>Description</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody>
            <?php 
              foreach ($this->items as $items) {
                foreach ($items as $value) {      
                  echo '<tr>';
                    echo '<td>'. $value['id'] .'</td>';
                    echo '<td>'. $value['description'] .'</td>';
                    echo '<td>'. $value['amount'] .'$</td>';
                  echo '</tr>';
                }
              }
            ?>
        </table>  

        <p><strong>Subtotal: </strong></p>
        <p><strong>Tax: </strong></p>
        <p><strong>Shipping: </strong></p>
        <p><strong>Total amount: </strong><?php echo $this->total_amount; ?>$</p>

        <form role="form">
          <div class="form-group">
            <label for="phone_number">Phone number</label>
            <input name="authorization[phone_number]" type="text" class="form-control" placeholder="Enter your phone number" />
          </div>

          <input name="authorization[geopay_signature]" type="hidden" value="" />
          <input name="authorization[return_url]" type="hidden" value="" />
          <input name="authorization[abandon_url]" type="hidden" value="" />
          <input name="authorization[reference_number]" type="hidden" value="" />
          <input name="authorization[transaction_amount]" type="hidden" value="<?php echo $this->total_amount; ?>" />
          <input name="authorization[geopay_id_token]" type="hidden" value="" />
          <input name="authorization[locale]" type="hidden" value="" />
          <input name="authorization[payment_details[description]]" type="hidden" value="" />
          <input name="authorization[payment_details[subtotal]]" type="hidden" value="" />
          <input name="authorization[payment_details[tax]]" type="hidden" value="" />
          <input name="authorization[payment_details[shipping]]" type="hidden" value="" />

          <?php 
            foreach ($this->items as $items) {
              foreach ($items as $value) {      
                echo '<input name="authorization[payment_details][items][][description]" type="hidden" value="'.$value['description'].'" />';
                echo '<input name="authorization[payment_details][items][][amount]" type="hidden" value="'.$value['amount'].'" />';
              }
            }
          ?>
        </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
      </div>
    </div>
  </div>
</div>