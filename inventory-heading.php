<div class="row">
    <div class="col-md-1">
        <div class="form-group">
            <label for="line">Line #</label>
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            <label for="is-new">New/Used</label>
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            <label for="manufacturer">Manufacturer</label>
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            <label for="part">Part #</label>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="product-description">Description</label>
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            <label for="quantity">Quantity</label>
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            <label for="list-price">List Price</label>
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            <label for="sell-price">Sell Price</label>
        </div>
    </div>

    <?php if ( isset( $costCodes ) ) { ?>
        <div class="col-md-1">
            <div class="form-group">
                <label for="cost-code">Cost Code</label>
            </div>
        </div>

        <div class="col-md-1">
            <div class="form-group">
                <label for="cost">Cost</label>
            </div>
        </div>
    <?php } ?>
</div>
