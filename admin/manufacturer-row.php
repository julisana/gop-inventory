<?php
if ( !isset( $row ) ) {
    $row = [
        'code' => '',
        'name' => '',
    ];
}

if ( !isset( $index ) ) {
    $index = 0;
}

?>

<div class="row manufacturer-item">
    <div class="col-md-1">
        <div class="form-group">
            <input type="text" class="form-control code" name="manufacturers[<?php echo $index; ?>][code]"
                   value="<?php echo $row[ 'code' ]; ?>" />
            <?php if ( isset( $row[ 'id' ] ) ) { ?>
                <input type="hidden" class="id" name="manufacturers[<?php echo $index; ?>][id]" value="<?php echo $row[ 'id' ]; ?>" />
            <?php } ?>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <div class="input-group">
                <input type="text" class="form-control name" name="manufacturers[<?php echo $index; ?>][name]"
                       value="<?php echo $row[ 'name' ]; ?>" />&nbsp;&nbsp;&nbsp;
                <span class="btn btn-danger remove-item <?php echo $index == 0 ? 'd-none' : '' ?>" data-row="<?php echo $index; ?>">X</span>
            </div>
        </div>
    </div>
</div>