<x-layout>
  <div class="lg:grid lg:grid-cols-2 gap-1 space-y-4 md:space-y-0 mx-4">
      
    @auth 
    {{--Add star buttons for logged users--}}
    <div class="flex justify-center">
      <form action="/favourite" method="POST">
        @csrf
        {{--Hidden inputs for selected currencies--}}
        <input
          type="hidden" 
          name="selected_crypto" 
          value={{$selected_crypto}} 
        >

        <input 
          type="hidden" 
          name="selected_fiat" 
          value={{$selected_fiat}} 
        >

      <div class="flex justify-center">
        <input 
          type="hidden" 
          name="favourite_add_crypto"
          value={{$selected_crypto}}
        >
        <button type="submit">
          <img src="{{ asset('images/gold_star.png') }}" alt="Favourite">
        </button>
      </div>
      </form>
    </div>

    <div class="flex justify-center">
      <form action="/favourite" method="POST">
        @csrf
        
        <button type="submit">
          <img src="{{ asset('images/gold_star.png') }}" alt="Favourite">
        </button>
      </form>
    </div>
    @endauth

    {{--Add drop-down menus--}}
    <form action="price" method="GET">
      <div class="flex justify-center">
        <x-crypto-dropdown :currencies="$crypto_currencies" />
      </div>

      <div class="flex justify-center">
        <x-fiat-dropdown :currencies="$fiat_currencies" />
      </div>

      <div class="flex justify-center">
        <button type="submit">
          CONVERT
        </button>
      </div>
    </form>

    <div class="text-center">
      <p>{{ $selected_crypto }} = {{ $exchange_rate }} {{ $selected_fiat }}</p>
    </div>

</x-layout>
