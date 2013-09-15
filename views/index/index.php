<h4>Our products</h4>

<table class="table table-hover">
  <thead>
    <tr>
      <th>#</th>
      <th>Description</th>
      <th>Price</th>
      <th>Images</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
  <?php 
    foreach ($this->products as $product) {
      echo '<tr>';
        echo '<td>'. $product['id'] .'</td>';
        echo '<td>'. $product['description'] .'</td>';
        echo '<td>'. $product['amount'] .'$</td>';
        echo '<td><a href="http://images.google.kg/search?tbm=isch&q='.$product['description'].'" class="btn btn-success btn-sm" target="_blank">More pics</a></td>';
        echo '<td><a href="'.URL.'index/add/'.$product['id'].'" class="btn btn-primary btn-sm">Add to cart</a></td>';
      echo '</tr>';
    }
  ?>
  </tbody>
</table>