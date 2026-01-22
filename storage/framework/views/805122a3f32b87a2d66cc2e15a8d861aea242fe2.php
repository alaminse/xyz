<style>
    .top_rw {
        background-color: #f4f4f4;
    }

    .td_w {}

    button {
        padding: 5px 10px;
        font-size: 14px;
    }

    /* .invoice-box {
        max-width: 890px;
        margin: auto;
        padding: 10px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        font-size: 14px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
    } */

    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
        /* border-bottom: solid 1px #ccc; */
    }

    .invoice-box table td {
        padding: 5px;
        vertical-align: middle;
    }

    .invoice-box table tr td:nth-child(2) {
        text-align: right;
    }

    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }

    .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
    }

    .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }

    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
        font-size: 12px;
    }

    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }

    .invoice-box table tr.item td {
        border-bottom: 1px solid #eee;
    }

    .invoice-box table tr.item.last td {
        border-bottom: none;
    }

    .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }

    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }

        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
    }

    /** RTL **/
    .rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }

    .rtl table {
        text-align: right;
    }

    .rtl table tr td:nth-child(2) {
        text-align: left;
    }
</style>

<div class="invoice-box">
    <table cellpadding="0" cellspacing="0">
        <tr class="">
            <td colspan="2">
                <img src="<?php echo e(asset('uploads/logo/logo.png')); ?>" style="background-color: black; width: 413px;">
            </td>
            <td style="width:30%; position: relative;">
                <div style="
                    position: absolute;
                    top: -50px;
                    right: -120px;
                    transform: rotate(35deg);
                    transform-origin: top right;
                    background-color: #bae85c;
                    color: white;
                    font-weight: bold;
                    text-align: center;
                    padding: 10px 120px;
                    font-size: 26px;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                ">
                    PAID
                </div>
            </td>
        </tr>

        <?php
            $contact = contact();
        ?>
        <tr class="information">
            <td colspan="3" style="text-align: right;">
                <b><?php echo e(env('APP_NAME')); ?> </b> <br>
                Dhaka Bangladesh <br>
                <?php echo e($contact['address'] ?? ''); ?><br>
                <?php echo e($contact['phone'] ?? ''); ?><br>
                <?php echo e($contact['email'] ?? ''); ?><br>
                <!-- <div style="margin-bottom: 20px"></div> -->
            </td>
        </tr>
        <tr class="top_rw">
            <td colspan="3">
                <h2 style="margin-bottom: 0px;"> Invoice #<?php echo e($invoice->slug); ?></h2>
                <span style=""> Date: <?php echo e(\Carbon\Carbon::parse($invoice->start_date)->format('F d, Y')); ?></span>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <div style="margin-top: 5px">Invoice TO:</div>
                <b> <?php echo e($invoice->bill?->firstName); ?><br>
                <?php echo e($invoice->bill?->address); ?> </b><br>
                <?php echo e($invoice->bill?->phone); ?><br>
                <?php echo e($invoice->bill?->email); ?>

            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table cellspacing="0px" cellpadding="2px" style="margin-top: 20px">
                    <tr class="heading">
                        <td style="width:45%;">ITEM</td>
                        <td style="width:30%; text-align:left;">Date</td>
                        <td style="width:10%; text-align:right;">Price</td>
                        <td style="width:15%; text-align:right;">Sell Price</td>
                    </tr>
                    <tr class="item">
                        <td style="width:45%;">
                            <img class="image-preview img-fluid" src="<?php echo e(getImageUrl($invoice->subscription?->banner)); ?>" alt="<?php echo e($invoice->subscription?->title); ?>" style="height: 40px; width: 40px">
                            <div><strong><?php echo e($invoice->course?->name); ?></strong></div>
                            <?php echo e($invoice->course?->detail?->duration); ?> <?php echo e($invoice->course?->detail?->type); ?>

                        </td>
                        <td style="width:30%; text-align:left;">
                            <span style="color: #4bf873">Purchase Date: </span> <?php echo e(\Carbon\Carbon::parse($invoice->start_date)->format('M d, Y')); ?> <br>
                            <span style="color: #f41f0b">Expiry Date: </span> <?php echo e(\Carbon\Carbon::parse($invoice->end_date)->format('M d, Y')); ?>

                        </td>
                        <td style="width:10%; text-align:right;">
                            <?php echo e($invoice->price); ?>

                        </td>
                        <td style="width:15%; text-align:right;">
                            <?php echo e($invoice->sell_price); ?>

                        </td>
                    </tr>
                    <tr class="item">
                        <td style="width:45%;"><b>Total</b></td>
                        <td style="width:30%;"></td>
                        <td style="width:10%; text-align:right;"></td>
                        <td style="width:15%; text-align:right;">
                            <b><?php echo e($invoice->sell_price > 0 ? $invoice->sell_price : $invoice->price); ?></b>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <h3 style="margin-top: 20px; margin-bottom: 0px">Transection</h3>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table cellspacing="0px" cellpadding="2px">
                    <tr class="heading">
                        <td style="width:30%;">Date</td>
                        <td style="width:30%; text-align:center;">Gateway</td>
                        <td style="width:20%; text-align:center;">Transection Id</td>
                        <td style="width:20%; text-align:right;">Amount</td>
                    </tr>
                    <tr class="item">
                        <td style="width:30%;"><?php echo e(\Carbon\Carbon::parse($invoice->created_at)->format('M d, Y')); ?></td>
                        <td style="width:30%; text-align:center;"><?php echo e($invoice->bill?->{'paymentMethod'} == 1 ? 'Bkash': 'Nagod'); ?></td>
                        <td style="width:20%; text-align:center;"><?php echo e($invoice->bill?->{'p-t_id'}); ?></td>
                        <td style="width:20%; text-align:right;"><?php echo e($invoice->bill?->{'p-amount'}); ?></td>
                    </tr>
                    <tr class="item">
                        <td colspan="3" style="text-align:right;">Total</td>
                        <td style="text-align:right;"><?php echo e($invoice->bill?->{'p-amount'}); ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>


<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/medimaniac/resources/views/frontend/settings/invoice.blade.php ENDPATH**/ ?>