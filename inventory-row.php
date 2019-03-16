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
            'sell_price' => '',
        ];
    }

    if ( !isset( $index ) ) {
        $index = 0;
    }
?>

<div class="row inventory-item">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-1">
                <div class="form-group">
                    <input type="number" class="form-control line" name="inventory[<?php echo $index; ?>][line_number]"
                           value="<?php echo $row[ 'line_number' ]; ?>" />
                    <?php if ( isset( $row[ 'id' ] ) ) { ?>
                        <input type="hidden" class="id" name="inventory[<?php echo $index; ?>][id]" value="<?php echo $row[ 'id' ]; ?>" />
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <select class="form-control is-new" name="inventory[<?php echo $index; ?>][is_new]">
                        <option <?php echo $row[ 'is_new' ] == '' ? 'selected' : ''; ?> value="">Select One</option>
                        <option <?php echo $row[ 'is_new' ] == '1' ? 'selected' : ''; ?> value="1">New</option>
                        <option <?php echo $row[ 'is_new' ] == '0' ? 'selected' : ''; ?> value="0">Used</option>
                    </select>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <select class="form-control manufacturer" name="inventory[<?php echo $index; ?>][manufacturer]"
                            required>
                        <option <?php echo $row[ 'manufacturer' ] == '' ? 'selected' : ''; ?> value="">Select One</option>
                        <?php foreach ( get_manufacturers( $year, $db ) as $key => $value ) {
                            echo '<option value="' . $key . '"' . ( $row[ 'manufacturer' ] == $key ? ' selected' : '' ) . '>' . $value . '</option>' . "\n";
                        } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <input type="text" class="form-control product-id" name="inventory[<?php echo $index; ?>][product_id]"
                           value="<?php echo $row[ 'product_id' ]; ?>" />
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <input type="text" class="form-control product-description"
                           name="inventory[<?php echo $index; ?>][product_description]" value="<?php echo $row[ 'product_description' ]; ?>" />
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <input type="number" class="form-control quantity" name="inventory[<?php echo $index; ?>][quantity]"
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
                               name="inventory[<?php echo $index; ?>][list_price]" min="0.00" step="0.01" placeholder="0.00"
                               value="<?php echo $row[ 'list_price' ]; ?>" />
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
                               name="inventory[<?php echo $index; ?>][sell_price]" min="0.01" step="0.01" placeholder="0.00"
                               value="<?php echo $row[ 'sell_price' ]; ?>" />&nbsp;&nbsp;&nbsp;
                        <span class="btn btn-danger remove-item" data-row="<?php echo $index; ?>">X</span>
                    </div>
                </div>
            </div>
        </div>
        <?php if ( isset( $row[ 'cost' ] ) ) { ?>
            <div class="row">
                <div class="offset-md-1 col-md-1">
                    <div class="form-group">
                        <label for="cost">Cost</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="number" class="form-control cost"
                                   name="inventory[<?php echo $index; ?>][cost]" min="0.00" step="0.01" placeholder="0.00"
                                   value="<?php echo $row[ 'cost' ]; ?>" />
                        </div>
                    </div>
                </div>

                <div class="col-md-1">
                    <div class="form-group">
                        <label for="cost-code">Cost Code</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="text" class="form-control cost-code" name="inventory[<?php echo $index; ?>][cost_code]"
                               value="<?php echo $row[ 'cost_code' ]; ?>" />
                    </div>
                </div>

                <div class="col-md-1">
                    <div class="form-group">
                        <label for="value">Value</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="text" class="form-control value" name="inventory[<?php echo $index; ?>][value]"
                               value="<?php echo $row[ 'value' ]; ?>" />
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
