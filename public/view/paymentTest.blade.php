@php
//Credentials
$apikey = '14521825885f1a9e18c1df14.88081706';
$cpm_site_id = '464717';

//Post Parameters
$cpm_amount = $money; //Le montant de la transaction
$cpm_version = 'V1';
$cpm_language = 'fr';
$cpm_currency = 'CFA';
$cpm_page_action = 'PAYMENT';
$cpm_custom = 'PAIEMENTTEST';
$cpm_payment_config = 'SINGLE';
$cpm_designation = $remark; //Le produit acheter
$cpSecure = "https://secure.cinetpay.com";

$cpm_trans_date = date("Y-m-d H:i:s");
$cpm_trans_id = $order_number; //J'ai ajouter 'Test-' pour eviter les duplication dans CP
$return_url = "http://sxwm.xundong.top/notify"; //Le client sera rediriger vers cette url apres son paiement
$notify_url = "http://sxwm.xundong.top/notifyUrl";
$cancel_url = "";
$signatureUrl = "https://api.cinetpay.com/v1/?method=getSignatureByPost";

//Data that will be send in the form
$getSignatureData = array(
    'apikey' => $apikey,
    'cpm_amount' => $cpm_amount,
    'cpm_custom' => $cpm_custom,
    'cpm_site_id' => $cpm_site_id,
    'cpm_version' => $cpm_version,
    'cpm_currency' => $cpm_currency,
    'cpm_trans_id' => $cpm_trans_id,
    'cpm_language' => $cpm_language,
    'cpm_trans_date' => $cpm_trans_date,
    'cpm_page_action' => $cpm_page_action,
    'cpm_designation' => $cpm_designation,
    'cpm_payment_config' => $cpm_payment_config
);
// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'method' => "POST",
        'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
        'content' => http_build_query($getSignatureData)
    )
);

$context = stream_context_create($options);
$result = file_get_contents($signatureUrl, false, $context);
if ($result === false) {
    /* Handle error */
    \header($return_url);
    exit();
}
//var_dump($getSignatureData);

$signature = json_decode($result);
@endphp

        <!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
    <style type="text/css">
        .loadding {
            background: rgba(255, 255, 255, 1);
            position: fixed;
            width: 100%;
            height: 100vh;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
        }



        .loading-column {

            width: 100%;
            height: 100%;

            margin: 5px;

            display: flex;
            justify-content: center;
            align-items: center;
        // margin-top: 50%;

        }

        .loading-container {
            width: 30px;
            height: 30px;
            position: relative;
        // left: calc(50% - 30px);
        // top: calc(50% - 30px);
        }

        .loading-container.animation-6 {
            -webkit-animation: rotation 1s infinite;
            animation: rotation 1s infinite;
        }

        .loading-container.animation-6 .shape {
            width: 12px;
            height: 12px;
            border-radius: 2px;
        }

        .loading-container .shape {
            position: absolute;
            width: 10px;
            height: 10px;
            border-radius: 1px;
        }

        .loading-container .shape.shape1 {
            left: 0;
            background-image: linear-gradient(252deg,
            #15c180 0%,
            #2fcf88 43%,
            #48dc90 100%),
            linear-gradient(#9a6e6e,
                    #9a6e6e);
        }

        .loading-container .shape.shape2 {
            right: 0;
            background-image: linear-gradient(252deg,
            #15c180 0%,
            #2fcf88 43%,
            #48dc90 100%),
            linear-gradient(#9a6e6e,
                    #9a6e6e);
        }

        .loading-container .shape.shape3 {
            bottom: 0;
            background-image: linear-gradient(252deg,
            #15c180 0%,
            #2fcf88 43%,
            #48dc90 100%),
            linear-gradient(#9a6e6e,
                    #9a6e6e);
        }

        .loading-container .shape.shape4 {
            bottom: 0;
            right: 0;
            background-image: linear-gradient(252deg,
            #15c180 0%,
            #2fcf88 43%,
            #48dc90 100%),
            linear-gradient(#9a6e6e,
                    #9a6e6e);
        }

        @-webkit-keyframes rotation {
            from {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            to {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @keyframes rotation {
            from {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            to {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        .animation-6 .shape1 {
            -webkit-animation: animation6shape1 2s linear 0s infinite normal;
            animation: animation6shape1 2s linear 0s infinite normal;
        }

        @-webkit-keyframes animation6shape1 {
            0% {
                -webkit-transform: translate(0, 0);
                transform: translate(0, 0);
            }

            25% {
                -webkit-transform: translate(0, 18px);
                transform: translate(0, 18px);
            }

            50% {
                -webkit-transform: translate(18px, 18px);
                transform: translate(18px, 18px);
            }

            75% {
                -webkit-transform: translate(18px, 0);
                transform: translate(18px, 0);
            }
        }

        @keyframes animation6shape1 {
            0% {
                -webkit-transform: translate(0, 0);
                transform: translate(0, 0);
            }

            25% {
                -webkit-transform: translate(0, 18px);
                transform: translate(0, 18px);
            }

            50% {
                -webkit-transform: translate(18px, 18px);
                transform: translate(18px, 18px);
            }

            75% {
                -webkit-transform: translate(18px, 0);
                transform: translate(18px, 0);
            }
        }

        .animation-6 .shape2 {
            -webkit-animation: animation6shape2 2s linear 0s infinite normal;
            animation: animation6shape2 2s linear 0s infinite normal;
        }

        @-webkit-keyframes animation6shape2 {
            0% {
                -webkit-transform: translate(0, 0);
                transform: translate(0, 0);
            }

            25% {
                -webkit-transform: translate(-18px, 0);
                transform: translate(-18px, 0);
            }

            50% {
                -webkit-transform: translate(-18px, 18px);
                transform: translate(-18px, 18px);
            }

            75% {
                -webkit-transform: translate(0, 18px);
                transform: translate(0, 18px);
            }
        }

        @keyframes animation6shape2 {
            0% {
                -webkit-transform: translate(0, 0);
                transform: translate(0, 0);
            }

            25% {
                -webkit-transform: translate(-18px, 0);
                transform: translate(-18px, 0);
            }

            50% {
                -webkit-transform: translate(-18px, 18px);
                transform: translate(-18px, 18px);
            }

            75% {
                -webkit-transform: translate(0, 18px);
                transform: translate(0, 18px);
            }
        }

        .animation-6 .shape3 {
            -webkit-animation: animation6shape3 2s linear 0s infinite normal;
            animation: animation6shape3 2s linear 0s infinite normal;
        }

        @-webkit-keyframes animation6shape3 {
            0% {
                -webkit-transform: translate(0, 0);
                transform: translate(0, 0);
            }

            25% {
                -webkit-transform: translate(18px, 0);
                transform: translate(18px, 0);
            }

            50% {
                -webkit-transform: translate(18px, -18px);
                transform: translate(18px, -18px);
            }

            75% {
                -webkit-transform: translate(0, -18px);
                transform: translate(0, -18px);
            }
        }

        @keyframes animation6shape3 {
            0% {
                -webkit-transform: translate(0, 0);
                transform: translate(0, 0);
            }

            25% {
                -webkit-transform: translate(18px, 0);
                transform: translate(18px, 0);
            }

            50% {
                -webkit-transform: translate(18px, -18px);
                transform: translate(18px, -18px);
            }

            75% {
                -webkit-transform: translate(0, -18px);
                transform: translate(0, -18px);
            }
        }

        .animation-6 .shape4 {
            -webkit-animation: animation6shape4 2s linear 0s infinite normal;
            animation: animation6shape4 2s linear 0s infinite normal;
        }

        @-webkit-keyframes animation6shape4 {
            0% {
                -webkit-transform: translate(0, 0);
                transform: translate(0, 0);
            }

            25% {
                -webkit-transform: translate(0, -18px);
                transform: translate(0, -18px);
            }

            50% {
                -webkit-transform: translate(-18px, -18px);
                transform: translate(-18px, -18px);
            }

            75% {
                -webkit-transform: translate(-18px, 0);
                transform: translate(-18px, 0);
            }
        }

        @keyframes animation6shape4 {
            0% {
                -webkit-transform: translate(0, 0);
                transform: translate(0, 0);
            }

            25% {
                -webkit-transform: translate(0, -18px);
                transform: translate(0, -18px);
            }

            50% {
                -webkit-transform: translate(-18px, -18px);
                transform: translate(-18px, -18px);
            }

            75% {
                -webkit-transform: translate(-18px, 0);
                transform: translate(-18px, 0);
            }
        }
    </style>
</head>
<body>
    <div id="loadding" class="loadding">
			<div class="loading-column">
				<div class="loading-container animation-6">
					<div class="shape shape1"></div>
					<div class="shape shape2"></div>
					<div class="shape shape3"></div>
					<div class="shape shape4"></div>
				</div>
            </div>
    </div>

<form action="@php echo $cpSecure; @endphp" method="post">
    <input type="hidden" value="@php echo $apikey; @endphp" name="apikey">
    <p><input type="text" value="@php echo $cpm_amount; @endphp" name="cpm_amount"></p>
    <input type="hidden" value="@php echo $cpm_custom; @endphp" name="cpm_custom">
    <input type="hidden" value="@php echo $cpm_site_id; @endphp" name="cpm_site_id">
    <input type="hidden" value="V1" name="cpm_version">
    <p><input type="text" value="CFA" name="cpm_currency"></p>
    <input type="hidden" value="@php echo $cpm_trans_id; @endphp" name="cpm_trans_id">
    <input type="hidden" value="fr" name="cpm_language">
    <input type="hidden" value="@php echo $getSignatureData['cpm_trans_date']; @endphp" name="cpm_trans_date">
    <input type="hidden" value="PAYMENT" name="cpm_page_action">
    <p><input type="text" value="@php echo $cpm_designation; @endphp" name="cpm_designation"> </p>
    <input type="hidden" value="SINGLE" name="cpm_payment_config">

    <input type="hidden" value="@php echo $signature; @endphp" name="signature">
    <input type="hidden" value="@php echo $return_url; @endphp" name="return_url">
    <input type="hidden" value="@php echo $cancel_url; @endphp" name="cancel_url">
    <input type="hidden" value="@php echo $notify_url; @endphp" name="notify_url">
    {{--<input type="hidden" value="1" name="debug">--}}
    <input id="button2" type="submit" value="Valider">
</form>

</body>

</html>
<script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
<script type="text/javascript">
    		setTimeout(()=>{
    			document.getElementById("button2").click();
    		},200)
</script>
