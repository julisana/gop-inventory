<?php
if ( !isset( $row ) ) {
    $row = [
        'code' => '',
        'name' => '',
    ];
}

$index = 0;
if ( isset( $row[ 'id' ] ) ) {
    $index = $row[ 'id' ];
}

?>

<div class="row keyer-item">
    <div class="col-md-1">
        <div class="form-group">
            <input type="text" class="form-control code" name="keyers[<?php echo $index; ?>][code]"
                   value="<?php echo $row[ 'code' ]; ?>" />
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <div class="input-group">
                <input type="text" class="form-control name" name="keyers[<?php echo $index; ?>][name]"
                   value="<?php echo $row[ 'name' ]; ?>" />&nbsp;&nbsp;&nbsp;
                <span class="btn btn-danger remove-item <?php echo $index == 0 ? 'd-none' : '' ?>" data-row="<?php echo $index; ?>">X</span>
            </div>
        </div>
    </div>
</div>