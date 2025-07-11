{{-- <div class="w-full px-3 py-1">
    @if($products_not_avaialable_list)
        <table class="w-full h-full rounded-lg text-xs">
            <!-- <thead class="border-b">
                    <tr class="text-left text-gray-500 font-semibold">
                        <th class="px-2 py-1 border-b w-2/4">Product</th>
                        <th class="px-2 py-1 border-b text-center w-1/6">On Hand</th>
                    </tr>
                </thead> -->
            <tbody class="h-full">
                @foreach($products_not_avaialable_list as $product)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-2 py-1 flex items-center gap-2">
                            <img src="https://via.placeholder.com/40" alt="Product" class="w-10 h-10 rounded">
                            <span
                                class="whitespace-normal break-words">{{ $product->product->product_name }}({{ $product->product->product_code }})</span>
                        </td>
                        <td class="px-2 py-1 text-center text-red-500 font-semibold">{{ $product->on_hand_quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="text-center text-gray-500">No products are out of stock.</div>
    @endif 
</div> --}}