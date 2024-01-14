@props(['currencies'])
<div class="flex justify-center">
  <p>
  <select name="fiat" id="fiat_dropdown">
    @foreach($currencies as $currency)
      <option value={{$currency['code']}}><b>{{$currency['code']}}</b>&nbsp;&nbsp;{{$currency['name']}}</option>
    @endforeach
  </select>
</div>