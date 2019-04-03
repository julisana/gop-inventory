<?php
    if ( !isset( $row ) ) {
        $row = [
            'code' => '',
            'name' => '',
            'percentage' => '',
            'is_decrease' => '',
            'field' => '',
        ];
    }

    if ( !isset( $index ) ) {
        $index = 0;
    }

    $used = false;
    if ( isset( $row[ 'id' ] ) && isset( $usedItems ) && in_array( $row[ 'id' ], $usedItems ) ) {
        $used = true;
    }
?>

<div class="row cost-code-item">
    <div class="col-md-1">
        <div class="form-group">
            <input type="text" class="form-control code" name="cost_code[<?php echo $index; ?>][code]"
                   value="<?php echo $row[ 'code' ]; ?>" />
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <div class="input-group">
                <input type="text" class="form-control name" name="cost_code[<?php echo $index; ?>][name]"
                       value="<?php echo $row[ 'name' ]; ?>" />
            </div>
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            <div class="input-group">
                <input type="text" class="form-control name" name="cost_code[<?php echo $index; ?>][percentage]"
                       value="<?php echo $row[ 'percentage' ]; ?>" />
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <div class="input-group">
                <?php if ( isset( $row[ 'id' ] ) ) { ?>
                    <input type="hidden" class="id" name="cost_code[<?php echo $index; ?>][id]"
                           value="<?php echo $row[ 'id' ]; ?>" />
                <?php } ?>
                <select name="cost_code[<?php echo $index; ?>][field]" class="form-control name" required>
                    <option value="">Select One</option>
                    <option value="list_price" <?php echo $row[ 'field' ] == 'list_price' ? 'selected' : ''; ?>>List Price</option>
                    <option value="sell_price" <?php echo $row[ 'field' ] == 'sell_price' ? 'selected' : ''; ?>>Sell Price</option>
                    <option value="cost" <?php echo $row[ 'field' ] == 'cost' ? 'selected' : ''; ?>>Cost</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-1">
        <span class="btn <?php echo $used ? 'btn-secondary' : 'btn-danger' ?> remove-item <?php echo ( $index == 0 && count( $costCodes ) == 1 ) ? 'd-none' : '' ?>"
              data-row="<?php echo $index; ?>" <?php if ( $used ) { ?>data-toggle="popover"<?php } ?>>X</span>
    </div>
</div>
