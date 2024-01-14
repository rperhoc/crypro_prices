@props(['currencies'])
<div class="flex justify-center">
  <p>
    <select class="font-mono px-2" name="crypto" id="crypto_dropdown">
      @foreach($currencies as $currency)
        <option value={{$currency['code']}}><b>{{$currency['code']}}</b>&nbsp;&nbsp;{{$currency['name']}}</option>
      @endforeach
  </select>
</div>