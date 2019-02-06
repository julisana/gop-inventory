<?php
    if ( !isset( $row ) ) {
        $row = [
            'line_number' => '',
            'is_new' => '',
            'manufacturer' => '',
            'product_id' => '',
            'product_description' => '',
            'quantity' => '',
            'list_price' => '',
            'sale_price' => '',
        ];
    }
?>

<div class="row inventory-item">
    <div class="col-md-1">
        <div class="form-group">
            <input type="number" class="form-control line" name="inventory[0][line_number]"
                   value="<?php echo $row[ 'line_number' ]; ?>" />
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            <select class="form-control is-new" name="inventory[0][is_new]">
                <option <?php echo $row[ 'is_new' ] == '' ? 'selected' : ''; ?> value="">Select One</option>
                <option <?php echo $row[ 'is_new' ] == '1' ? 'selected' : ''; ?> value="1">New</option>
                <option <?php echo $row[ 'is_new' ] == '0' ? 'selected' : ''; ?> value="0">Used</option>
            </select>
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            <select class="form-control manufacturer" name="inventory[0][manufacturer]"
                    required>
                <option <?php echo $row[ 'manufacturer' ] == '' ? 'selected' : ''; ?> value="">Select One</option>
                <?php foreach ( get_manufacturers( $year, $db ) as $key => $value ) {
                    echo '<option value="' . $key . '">' . $value . '</option>' . "\n";
                } ?>
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <input type="text" class="form-control product-id" name="inventory[0][product_id]"
                   value="<?php echo $row[ 'product_id' ]; ?>" />
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <input type="text" class="form-control product-description"
                   name="inventory[0][product_description]" value="<?php echo $row[ 'product_description' ]; ?>" />
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            <input type="number" class="form-control quantity" name="inventory[0][quantity]"
                   value="<?php echo $row[ 'quantity' ]; ?>" />
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                </div>
                <input type="number" class="form-control list-price"
                       name="inventory[0][list_price]" min="0.00" step="0.01" placeholder="0.00"
                       value="<?php echo $row[ 'line_number' ]; ?>" />
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">$</span>
                </div>
                <input type="number" class="form-control sell-price"
                       name="inventory[0][sell_price]" min="0.01" step="0.01" placeholder="0.00"
                       value="<?php echo $row[ 'line_number' ]; ?>" />&nbsp;&nbsp;&nbsp;<span
                    class="btn btn-danger remove-item d-none"
                    data-row="0">X</span>
            </div>
        </div>
    </div>
</div>