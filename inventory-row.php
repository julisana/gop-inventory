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

    if ( !isset( $items ) ) {
        $items = [];
    }
?>

<div class="row inventory-item">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-1 pl-1 pr-1">
                <div class="form-group">
                    <input type="number" class="form-control form-control-sm line pl-1 pr-1" name="inventory[<?php echo $index; ?>][line_number]"
                           value="<?php echo $row[ 'line_number' ]; ?>" />
                    <?php if ( isset( $row[ 'id' ] ) ) { ?>
                        <input type="hidden" class="id" name="inventory[<?php echo $index; ?>][id]" value="<?php echo $row[ 'id' ]; ?>" />
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-1 pl-1 pr-1">
                <div class="form-group">
                    <select class="form-control form-control-sm is-new pl-1 pr-1" name="inventory[<?php echo $index; ?>][is_new]">
                        <option <?php echo $row[ 'is_new' ] == '' ? 'selected' : ''; ?> value="">Select One</option>
                        <option <?php echo $row[ 'is_new' ] == '1' ? 'selected' : ''; ?> value="1">New</option>
                        <option <?php echo $row[ 'is_new' ] == '0' ? 'selected' : ''; ?> value="0">Used</option>
                    </select>
                </div>
            </div>
            <div class="col-md-1 pl-1 pr-1">
                <div class="form-group">
                    <select class="form-control form-control-sm manufacturer pl-1 pr-1" name="inventory[<?php echo $index; ?>][manufacturer]"
                            required>
                        <option <?php echo $row[ 'manufacturer' ] == '' ? 'selected' : ''; ?> value="">Select One</option>
                        <?php foreach ( $manufacturers as $key => $value ) {
                            echo '<option value="' . $key . '"' . ( $row[ 'manufacturer' ] == $key ? ' selected' : '' ) . '>' . $value . '</option>' . "\n";
                        } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-1 pl-1 pr-1">
                <div class="form-group">
                    <input type="text" class="form-control form-control-sm product-id pl-1 pr-1" name="inventory[<?php echo $index; ?>][product_id]"
                           value="<?php echo $row[ 'product_id' ]; ?>" />
                </div>
            </div>
            <div class="col-md-2 pl-1 pr-1">
                <div class="form-group">
                    <input type="text" class="form-control form-control-sm product-description pl-1 pr-1"
                           name="inventory[<?php echo $index; ?>][product_description]" value="<?php echo $row[ 'product_description' ]; ?>" />
                </div>
            </div>
            <div class="col-md-1 pl-1 pr-1">
                <div class="form-group">
                    <input type="number" class="form-control form-control-sm quantity pl-1 pr-1" name="inventory[<?php echo $index; ?>][quantity]"
                           value="<?php echo $row[ 'quantity' ]; ?>" />
                </div>
            </div>
            <div class="col-md-1 pl-1 pr-1">
                <div class="form-group">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text pl-1 pr-1">$</span>
                        </div>
                        <input type="number" class="form-control form-control-sm list-price pl-1 pr-1"
                               name="inventory[<?php echo $index; ?>][list_price]" min="0.00" step="0.01" placeholder="0.00"
                               value="<?php echo $row[ 'list_price' ]; ?>" />
                    </div>
                </div>
            </div>
            <div class="col-md-1 pl-1 pr-1">
                <div class="form-group">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text pl-1 pr-1">$</span>
                        </div>
                        <input type="number" class="form-control form-control-sm sell-price pl-1 pr-1"
                               name="inventory[<?php echo $index; ?>][sell_price]" min="0.01" step="0.01" placeholder="0.00"
                               value="<?php echo $row[ 'sell_price' ]; ?>" />
                    </div>
                </div>
            </div>

            <?php if ( isset( $costCodes ) ) { ?>
                <div class="col-md-1 pl-1 pr-1">
                    <div class="form-group">
                        <select name="inventory[<?php echo $index; ?>][cost_code]" class="form-control form-control-sm cost-code pl-1 pr-1">
                            <option value="">Select One</option>
                            <?php foreach ( $costCodes as $key => $value ) {
                                echo '<option value="' . $key . '"' . ( $row[ 'cost_code' ] == $key ? ' selected' : '' ) . '>' . $value . '</option>' . "\n";
                            } ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-1 pl-1 pr-1">
                    <div class="form-group">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text pl-1 pr-1">$</span>
                            </div>
                            <input type="number" class="form-control form-control-sm cost pl-1 pr-1"
                                   name="inventory[<?php echo $index; ?>][cost]" min="0.00" step="0.01" placeholder="0.00"
                                   value="<?php echo $row[ 'cost' ]; ?>" />
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div class="col-md-1 pl-1 pr-1">
                <span class="btn btn-sm btn-danger remove-item ml-0 mr-0 <?php echo ( $index == 0 && count( $items ) < 2 ) ? 'd-none' : '' ?>" data-row="<?php echo $index; ?>">X</span>
            </div>
        </div>
    </div>
</div>
