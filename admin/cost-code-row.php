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
        <?php if ( isset( $row[ 'id' ] ) ) { ?>
            <div class="form-group">
                <span class="form-control disabled"><?php echo $row[ 'id' ]; ?></span>
                <input type="hidden" class="id" name="cost_code[<?php echo $index; ?>][id]"
                       value="<?php echo $row[ 'id' ]; ?>" />
            </div>
        <?php } ?>
    </div>
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
                <select name="cost_code[<?php echo $index; ?>][is_decrease]" class="form-control" required>
                    <option value="" <?php echo $row[ 'is_decrease' ] == '' ? 'selected' : ''; ?>>Select One</option>
                    <option value="0" <?php echo $row[ 'is_decrease' ] == '0' ? 'selected' : ''; ?>>Increase Value</option>
                    <option value="1" <?php echo $row[ 'is_decrease' ] == '1' ? 'selected' : ''; ?>>Decrease Value</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <div class="input-group">
                <input type="text" class="form-control name" name="cost_code[<?php echo $index; ?>][field]"
                       value="<?php echo $row[ 'field' ]; ?>" />&nbsp;&nbsp;&nbsp;
                <span class="btn <?php echo $used ? 'btn-secondary' : 'btn-danger' ?> remove-item <?php echo ( $index == 0 && count( $keyers ) == 1 ) ? 'd-none' : '' ?>"
                      data-row="<?php echo $index; ?>" <?php if ( $used ) { ?>data-toggle="popover"<?php } ?>>X</span>
            </div>
        </div>
    </div>
</div>
