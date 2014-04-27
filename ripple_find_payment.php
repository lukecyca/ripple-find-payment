<?php

/* rippled servers to connect to */
$RIPPLE_RPC_SERVERS = array(
    "https://s1.ripple.com:51234",
    "https://s-west.ripple.com:51234",
    "https://s-east.ripple.com:51234",
);

/* Number of requests to fetch per request.
   Lower number is faster for finding recent transactions.
   Higher number is faster for finding older transactions. */
$RIPPLE_TX_PER_REQUEST = 20;

/* Maximum number of failed rpc requests to the rippled before giving up */
$RIPPLE_MAX_FAILED_REQUESTS = 5;

/* Maximum number of historical ledgers to search.
   ~1000 ledgers per hour */
$RIPPLE_MAX_LEDGER_HISTORY = 1000 * 24 * 7;  // approx one week


/* (private)
   Makes an RPC request to a rippled server to get the last N
   transactions on the given $account. Repeated calls can set a
   $marker to request the next set of N transactions. */
function _ripple_request_account_tx($server, $account, $marker = false) {
    global $RIPPLE_TX_PER_REQUEST;

    $c = curl_init();
    curl_setopt($c, CURLOPT_URL, $server);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($c, CURLOPT_FAILONERROR, true);
    curl_setopt($c, CURLOPT_POST, true);
    curl_setopt($c, CURLOPT_POSTFIELDS, json_encode(array(
        "method" => "account_tx",
        "params" => array(array(
            "account" => $account,
            "ledger_index_min" => -1,
            "ledger_index_max" => -1,
            "limit" => $RIPPLE_TX_PER_REQUEST,
            "marker" => $marker
        ))
    )));
    curl_setopt($c, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "Accept: application/json"
    ));
    $response = curl_exec($c);

    if ($response === false) {
        print("CURL ERROR: " . curl_error($c) . "\n");
        return false;
    }
    curl_close($c);

    return json_decode($response, true);
}


/* (private)
   Checks a transaction to ensure it is valid and meets
   our payment criteria. */
function _ripple_check_tx($tx, $src_address, $dst_address, $value, $currency) {
    if ($tx["validated"] === true and
        $tx["meta"]["TransactionResult"] === "tesSUCCESS" and
        $tx["tx"]["TransactionType"] === "Payment" and
        $tx["tx"]["inLedger"] > 0 and
        $tx["tx"]["Account"] === $src_address and
        $tx["tx"]["Destination"] === $dst_address and

        // Currency is XRP and matches (in drips)
        ($currency !== "XRP" or $tx["tx"]["Amount"] == $value * 1000000) and

        // Currency is an IOU and matches
        ($currency === "XRP" or (
            $tx["tx"]["Amount"]["value"] == $value and
            $tx["tx"]["Amount"]["currency"] == $currency
        ))
    ) {

        return true;
    }

    return false;
}


/* Finds the most recent transaction meeting the given criteria.
   If found, the transaction is returned as an associative array containing
   the keys described on https://ripple.com/wiki/Transaction_Format.
   If no transaction is found, returns false.
   If an error is encountered, returns null. */
function ripple_find_payment($src_address, $dst_address, $value, $currency) {
    global $RIPPLE_RPC_SERVERS, $RIPPLE_MAX_FAILED_REQUESTS, $RIPPLE_MAX_LEDGER_HISTORY;

    $server_index = 0;
    $marker = null;
    $failures = 0;
    while ((!$resp or $marker)) {
        $resp = _ripple_request_account_tx($RIPPLE_RPC_SERVERS[$server_index], $dst_address, $resp["result"]["marker"]);
        if ($resp and $resp["result"]["status"] === "success") {
            $marker = $resp["result"]["marker"];
            foreach ($resp["result"]["transactions"] as $tx) {
                if (_ripple_check_tx($tx, $src_address, $dst_address, $value, $currency)) {
                    return $tx["tx"];
                }

                // If we haven't found a matching transaction in the last $RIPPLE_MAX_LEDGER_HISTORY
                // ledgers, give up.
                if ($tx["ledger_index"] < $resp["result"]["ledger_index_max"] - $RIPPLE_MAX_LEDGER_HISTORY) {
                    return false;
                }
            }
        } else {
            // Request failed.
            $failures++;
            if ($failures >= $RIPPLE_MAX_FAILED_REQUESTS) {
                return null;
            }
            $server_index = ($server_index + 1) % sizeof($RIPPLE_RPC_SERVERS);
            sleep(1);
        }
    }

    return false;
}

?>
