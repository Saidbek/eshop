<h4 class="text-primary">Value Redemption / Show</h4>

<pre><?php print_r($response); ?></pre>

<a href="<?php echo URL; ?>redemption/execute/<?php echo $transaction_number; ?>?amount=<?php echo $response['debited_amount']; ?>" class="btn btn-primary btn-sm">Execute</a>
<a href="<?php echo URL; ?>redemption/cancel/<?php echo $transaction_number; ?>" class="btn btn-primary btn-sm">Cancel</a>