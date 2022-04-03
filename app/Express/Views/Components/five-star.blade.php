<div>
    <svg class="hidden" xmlns="http://www.w3.org/2000/svg" >
        <defs>
            <symbol id="star-full">
                <path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/>
            </symbol>
            <symbol id="star-blank">
                <path d="M15.668 8.626l8.332 1.159-6.065 5.874 1.48 8.341-7.416-3.997-7.416 3.997 1.481-8.341-6.064-5.874 8.331-1.159 3.668-7.626 3.669 7.626zm-6.67.925l-6.818.948 4.963 4.807-1.212 6.825 6.068-3.271 6.069 3.271-1.212-6.826 4.964-4.806-6.819-.948-3.002-6.241-3.001 6.241z"/>
            </symbol>
            <linearGradient id="half">
                <stop offset="0%" stop-color="blue" />
                <stop offset="33.333%" stop-color="blue" />
                <stop offset="33.666%" stop-color="" />
                <stop offset="66.666%" stop-color="yellow" />
                <stop offset="66.666%" stop-color="red" />
                <stop offset="100%" stop-color="red" />
            </linearGradient>
        </defs>
    </svg>
    <div class="block">
        <div class="flex flex-row px-2 py-2">
            <div class="flex flex-row" >
                @for ($i = 0; $i < 5; $i++)
                    <form action="{{ route('web.expressions.store') }}" method="POST">
                        {{ csrf_field() }}
                        <label onclick="this.form.submit()">
                            <fieldset>
                                <input class="hidden" type="input" id="expressable_type"  name="expressable_type"   value="{{ $object['expressable_type'] }}"/>
                                <input class="hidden" type="input" id="expressable_id"    name="expressable_id"     value="{{ $object['expressable_object']['id'] }}"/>
                                <input class="hidden" type="input" id="expressable_type"  name="expression_type_id" value="7"/>
                                <input class="hidden" type="input" id="expression"        name="expression"         value="{{ $i + 1 }}" />
                                @if ( isset($object['user_expressions']) && isset($object['user_expressions'][7]) )
                                    @if($i < $object['user_expressions'][7][0]['expression'])
                                        <svg class="{{ $starClass }} h-8 text-green-500 fill-current transition duration-500 ease-in-out hover: transform hover:-translate-y-1 hover:rotate-12 hover:scale-110" viewBox="0 0 24 24" ><use xlink:href="#star-full"></use></svg>
                                    @else
                                        <svg class="{{ $starClass }} h-8 text-green-500 fill-current transition duration-500 ease-in-out hover: transform hover:-translate-y-1 hover:rotate-12 hover:scale-110" viewBox="0 0 24 24" ><use xlink:href="#star-blank"></use></svg>
                                    @endif
                                @else
                                    @if($i < floor($object['stats'][7]['avg']))
                                        <svg class="h-8 text-green-300 fill-current transition duration-500 ease-in-out hover: transform hover:-translate-y-1 hover:rotate-12 hover:scale-110" viewBox="0 0 24 24" ><use xlink:href="#star-full"></use></svg>
                                    @else
                                        <svg class="h-8 text-green-300 fill-current transition duration-500 ease-in-out hover: transform hover:-translate-y-1 hover:rotate-12 hover:scale-110" viewBox="0 0 24 24" ><use xlink:href="#star-blank"></use></svg>
                                    @endif
                                @endif
                            </fieldset>
                        </label>
                    </form>
                @endfor
            </div>
            <div class="flex flex-row pt-2 pl-1 text-green-600">
                @if ( isset($object['stats'][7]['avg']))
                    {{ $formatAvg($object['stats'][7]['avg']) }}
                @endif
                @if ( isset($object['stats'][7]['count']))
                    ({{ $formatCount($object['stats'][7]['count']) }})
                @endif
            </div>
        </div>
    </div>

    <!-- for Debugging -->
    <div class="block">
        @if ($errors->any())
            <div class="border border-red-400 rounded bg-red-100 px-2 py-2 text-red-700">Error Bro
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>
