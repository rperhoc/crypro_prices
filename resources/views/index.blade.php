<x-layout>
  <div class="lg:grid lg:grid-cols-2 gap-1 space-y-4 md:space-y-0 mx-4">
      
    @auth 
    {{--Add star buttons for logged users--}}
    <div class="flex justify-center">
      <form action="/favourite" method="POST">
        @csrf
        <input 
          type="hidden" 
          name="currency_id"
          value={{$selected_crypto->id}}
        >
        <button type="submit">
          @if($is_crypto_favourite)
            <img src="{{ asset('images/gold_star.png') }}" alt="Favourite">
          @else
            <img src="{{ asset('images/white_star.png') }}" alt="Favourite">
          @endif
        </button>
      </form>
    </div>

    <div class="flex justify-center">
      <form action="/favourite" method="POST">
        @csrf
          <input 
            type="hidden"
            name="currency_id"
            value={{$selected_fiat->id}}
          >
          <button type="submit">
            @if($is_fiat_favourite)
              <img src="{{ asset('images/gold_star.png') }}" alt="Favourite">
            @else
              <img src="{{ asset('images/white_star.png') }}" alt="Favourite">
            @endif
          </button>
      </form>
    </div>
    @endauth
  </div>

  {{--Add drop-down menus--}}
  <form action="/price" method="GET">
    <div class="lg:grid lg:grid-cols-2 gap-1 space-y-4 md:space-y-0 mx-4">
      <div class="flex justify-center">
        <select name="crypto" id="crypto_dropdown">
          @foreach($crypto_currencies as $currency)
          <option value={{$currency->id}} 
            @if($currency->id == $selected_crypto->id) selected @endif>
            <b>{{$currency->code}}</b>&nbsp;&nbsp;{{$currency->name}}
            @if(in_array($currency->id, $favourite_currencies)) * @endif
          </option>
          @endforeach
        </select>
      </div>

      <div class="flex justify-center">
        <select name="fiat" id="fiat_dropdown">
          @foreach($fiat_currencies as $currency)
          <option value={{$currency->id}} 
            @if($currency->id == $selected_crypto->id) selected @endif>
            <b>{{$currency->code}}</b>&nbsp;&nbsp;{{$currency->name}}
            @if(in_array($currency->id, $favourite_currencies)) * @endif
          </option>
          @endforeach
        </select>
      </div>

      <div class="flex justify-center col-span-2">
        <button type="submit" class="bg-green-500 text-black border-solid">
          CONVERT
        </button>
      </div>
    </div>
  </form>
  
  {{--DISPLAY PRICE--}}
  @if(isset($exchange_rate))
    <div class="text-center mt-5">
      <p>{{$selected_crypto->code}} = {{$exchange_rate}} {{$selected_fiat->code}}</p>
    </div>
  @endif 

</x-layout>