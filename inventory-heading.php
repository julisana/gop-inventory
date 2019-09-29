<div class="row">
    <div class="col-md-1">
        <div class="form-group text-nowrap">
            <label for="line">Line #</label>
            <span class="text-primary" data-toggle="popover" data-trigger="hover" title="Line #" data-content="Line numbers should start with 1 and increase incrementally with each new line added.">
                <i class="fa fa-info-circle" aria-hidden="true"></i>
            </span>
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group text-nowrap">
            <label for="is-new">New/Used</label>
            <span class="text-primary" data-toggle="popover" data-trigger="hover" title="New/Used" data-content="Select whether or not the item is New or Used.">
                <i class="fa fa-info-circle" aria-hidden="true"></i>
            </span>
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group text-nowrap">
            <label for="manufacturer">Mfr.</label>
            <span class="text-primary" data-toggle="popover" data-trigger="hover" title="Manufacturer" data-content="Select the manufacturer of the item you're entering. If the manufacturer isn't listed, ask an admin to have it entered.">
                <i class="fa fa-info-circle" aria-hidden="true"></i>
            </span>
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group text-nowrap">
            <label for="part">Part #</label>
            <span class="text-primary" data-toggle="popover" data-trigger="hover" title="Part #" data-content="Type in the part number of the item you're entering. If there is no part number, just use X.">
                <i class="fa fa-info-circle" aria-hidden="true"></i>
            </span>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group text-nowrap">
            <label for="product-description">Description</label>
            <span class="text-primary" data-toggle="popover" data-trigger="hover" title="Description" data-content="Describe the item you are entering. If you're creating a blank page, type in Blank Page.">
                <i class="fa fa-info-circle" aria-hidden="true"></i>
            </span>
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group text-nowrap">
            <label for="quantity">Quantity</label>
            <span class="text-primary" data-toggle="popover" data-trigger="hover" title="Quantity" data-content="Type in the quantity (number of items) for your line entry. If you're working on a blank page, just type in 0 for the quantity.">
                <i class="fa fa-info-circle" aria-hidden="true"></i>
            </span>
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group text-nowrap">
            <label for="list-price">List Price</label>
            <span class="text-primary" data-toggle="popover" data-trigger="hover" title="List Price" data-content="Type in the list price of the item you're entering. If you don't know the list price, do not enter anything.">
                <i class="fa fa-info-circle" aria-hidden="true"></i>
            </span>
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group text-nowrap">
            <label for="sell-price">Sell Price</label>
            <span class="text-primary" data-toggle="popover" data-trigger="hover" title="Sell Price" data-content="Type in the sell price of the item you're entering. If you don't know the sell price, do not enter anything.">
                <i class="fa fa-info-circle" aria-hidden="true"></i>
            </span>
        </div>
    </div>

    <?php if ( isset( $costCodes ) ) { ?>
        <div class="col-md-1">
            <div class="form-group text-nowrap">
                <label for="cost-code">Cost Code</label>
                <span class="text-primary" data-toggle="popover" data-trigger="hover" title="Cost Code" data-content="Select the cost code of the item you're entering. By default '20% of Sell' is selected.">
                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                </span>
            </div>
        </div>

        <div class="col-md-1">
            <div class="form-group text-nowrap">
                <label for="cost">Cost</label>
                <span class="text-primary" data-toggle="popover" data-trigger="hover" title="Cost" data-content="Type in the cost of the item you're entering. If you don't know the cost, do not enter anything.">
                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                </span>
            </div>
        </div>
    <?php } ?>
</div>
