<h4>Online Store</h4>

<table class="table table-hover">
  <thead>
    <tr>
      <th>#</th>
      <th>Description</th>
      <th>Amount</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
  <?php 
    foreach ($this->products as $product) {
      echo '<tr>';
        echo '<td>'. $product['id'] .'</td>';
        echo '<td>'. $product['description'] .'</td>';
        echo '<td>'. $product['amount'] .'</td>';
        echo '<td><a href="'.URL.'index/add" class="btn btn-xs btn-success">Add to cart</a></td>';
      echo '</tr>';
    }
  ?>
  </tbody>
</table>   