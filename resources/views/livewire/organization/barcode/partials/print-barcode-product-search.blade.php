<input type="text" id="productSearch" placeholder="Search product here ..." wire:model.live="searchTerm" autocomplete="off" class="pl-10 w-full px-4 py-2 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-primary-md focus:border-primary-md dark:bg-gray-800 dark:border-gray-600 dark:text-white" />
            @if($searchTerm)
                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <button wire:click="clearSearch" type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif
            @if($searchTerm && count($searchResults) > 0)
                <div
                    class="absolute z-10 w-full mt-1 bg-white rounded-md shadow-lg dark:bg-gray-800 border border-gray-300 dark:border-gray-600">
                    <ul class="max-h-60 py-1 overflow-auto text-sm">
                        @foreach($searchResults as $product)
                            <li wire:click="selectProduct({{ $product->id }})"
                                class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer flex items-center">
                                @if($product->image)
                                    @php    
                                                                $images = json_decode($product->image, true);
                                        $imagePath = is_array($images) && !empty($images) ? $images[0] : $product->image; 
                                    @endphp
                                        <img src="{{ asset('storage/' . $imagePath) }}" alt="{{ $product->name }}" class="w-8 h-8 mr-3 object-cover rounded">
                                @endif
                                <div>
                                    <p class="font-medium">({{ $product->product_code }}){{ $product->product_name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">${{ number_format($product->cost, 2) }}</p>
                                    </div>
                                </li>
                        @endforeach
                    </ul>
                </div>
            @elseif($searchTerm && count($searchResults) == 0)
                <div
                    class="absolute z-10 w-full mt-1 bg-white rounded-md shadow-lg dark:bg-gray-800 border border-gray-300 dark:border-gray-600">
                    <div class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                        No products found.
                    </div>
                </div>
            @endif