@props(['crypto_data', 'fiat_data'])
<div>
  <form action="/show_price">
    <div>
      <label for="crypto">
        Crypto Currency
      </label>
      <p>
        <select name="crypto" id="crypto_currency">
          @foreach($crypto_data as $currency)
            <option value={{$currency['name']}}>{{$currency['code']}}</option>
          @endforeach
      </select>
      </p>
    </div>
  </form>
</div>