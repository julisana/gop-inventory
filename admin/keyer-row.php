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

$used = false;
if ( isset( $row[ 'id' ] ) && isset( $usedItems ) && in_array( $row[ 'id' ], $usedItems ) ) {
    $used = true;
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
                <?php if ( isset( $row[ 'id' ] ) ) { ?>
                    <input type="hidden" class="id" name="keyers[<?php echo $index; ?>][id]"
                           value="<?php echo $row[ 'id' ]; ?>" />
                <?php } ?>
                <input type="text" class="form-control name" name="keyers[<?php echo $index; ?>][name]"
                       value="<?php echo $row[ 'name' ]; ?>" />
            </div>
        </div>
    </div>
    <div class="col-md-1">
        <span class="btn <?php echo $used ? 'btn-secondary' : 'btn-danger' ?> remove-item <?php echo ( $index == 0 && count( $keyers ) < 2 ) ? 'd-none' : '' ?>"
              data-row="<?php echo $index; ?>" <?php if ( $used ) { ?>data-toggle="popover"<?php } ?>>X</span>
    </div>
</div>
