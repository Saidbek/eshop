<h4 class="text-primary">Value Redemption / Show</h4>

<pre><?php var_dump($response); ?></pre>

<a href="<?php echo URL; ?>redemption/execute/<?php echo $transaction_number; ?>?amount=<?php echo json_decode($response)->debited_amount; ?>" class="btn btn-primary btn-sm">Execute</a>
<a href="<?php echo URL; ?>redemption/cancel/<?php echo $transaction_number; ?>" class="btn btn-primary btn-sm">Cancel</a>