@extends('layouts.app')

@section('content')

    <div class='container my-5'>
        <h1>Dues for full grove year</h1>

        <div style="max-width: 700px;">
            <p class="mt-4">
            Member: $70 including grove and ADF membership renewal
            </p>

            <div class="alert alert-info my-4">
                <p>If you have access to Zelle, you can send your membership fee or donation by Zelle to finance@ravenscrygrove.org (to save us a few dollars in fees that Paypal charges).</p>
            </div>

            <h3 class="mt-5 mb-3">To use PayPal instead use one of these buttons:</h3>

            <p class="my-4"></p>

            {{-- Section for the preset $70 button (Option 1) --}}
            <div class="card p-3 mb-2">
                <p class="fw-bold"> Option 1: Use PayPal preset to $72.10 (Membership Renewal plus PayPal fee):</p>
                <form action="https://www.paypal.com/ncp/payment/XVEQTFTFVFWM4" method="post" target="_blank" style="display:inline-grid;justify-items:center;align-content:start;gap:0.5rem;">
                    <input
                        type="submit"
                        value="Buy Now"
                        class="pp-XVEQTFTFVFWM4"
                        style="text-align:center;border:none;border-radius:0.25rem;min-width:11.625rem;padding:0 2rem;height:2.625rem;font-weight:bold;background-color:#FFD140;color:#000000;font-family:'Helvetica Neue',Arial,sans-serif;font-size:1rem;line-height:1.25rem;cursor:pointer;"
                    />
                    <img src="https://www.paypalobjects.com/images/Debit_Credit_APM.svg" alt="cards" />
                    <section style="font-size: 0.75rem;"> Powered by <img src="https://www.paypalobjects.com/paypal-ui/logos/svg/paypal-wordmark-color.svg" alt="paypal" style="height:0.875rem;vertical-align:middle;"/></section>
                </form>
            </div>

            {{-- Section for the generic donation button (Option 2) --}}
            <div class="card p-3">
                <p class="fw-bold">Option 2: Use PayPal to make a payment/donation of any amount:</p>
                    <form action="https://www.paypal.com/ncp/payment/9LBHB5F4288G4" method="post" target="_blank" style="display:inline-grid;justify-items:center;align-content:start;gap:0.5rem;">
                        <input
                            class="pp-9LBHB5F4288G4"
                            type="submit"
                            value="Buy Now"
                            style="text-align:center;border:none;border-radius:0.25rem;min-width:11.625rem;padding:0 2rem;height:2.625rem;font-weight:bold;background-color:#FFD140;color:#000000;font-family:'Helvetica Neue',Arial,sans-serif;font-size:1rem;line-height:1.25rem;cursor:pointer;"
                        />
                        <img src=https://www.paypalobjects.com/images/Debit_Credit_APM.svg alt="cards" />
                        <section style="font-size: 0.75rem;"> Powered by <img src="https://www.paypalobjects.com/paypal-ui/logos/svg/paypal-wordmark-color.svg" alt="paypal" style="height:0.875rem;vertical-align:middle;"/></section>
                    </form>
            </div>

            <div class="mt-5 mb-3">Or you can scan this PayPal QR code and put in your own amount:
            <img alt="PayPal QR Code" src="/img/qrcode.png" class="d-block mb-4" style="max-width: 200px;">
            </div>
        </div>
    </div>

@endsection
