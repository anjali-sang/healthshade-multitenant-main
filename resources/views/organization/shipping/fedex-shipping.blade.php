<x-app-layout>
    <div class="p-3">
        <div class="bg-white">
            <!-- Header -->

            <header class="border-b-2 p-2 ml-3 mr-3">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-16">
                        <div class="flex items-center">
                            <button onclick="history.back()"
                                class="mr-4 p-2 text-gray-400 hover:text-gray-600 transition-colors">
                                <i class="fas fa-arrow-left text-lg"></i>
                            </button>
                            <h1 class="text-xl font-semibold text-gray-900">FedEx Shipping Order</h1>
                        </div>
                        <div class="text-sm text-gray-500">
                            Order #<span class="text-black" id="shipment-number">{{$shipment->shipment_number}}</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Progress Steps -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Form -->
                    <div class="lg:col-span-2">
                        <form id="fedex-shipping-form">
                            @csrf
                            <!-- From Section -->
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 fade-in">
                                <div class="px-6 py-4 border-b border-gray-200">
                                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                        <i class="fas fa-warehouse text-blue-600 mr-2"></i>
                                        Ship From
                                    </h3>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Clinic
                                                *</label>
                                            <div name="from_warehouse" required
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                {{$location->name}}
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact
                                                Person</label>
                                            <select type="text" name="from_contact"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                @foreach ($users as $user)
                                                    <option value="{{$user->id}}">{{$user->name}}</option>

                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div id="warehouse-details" class="mt-4 p-4 bg-gray-50 rounded-md">
                                        <div class="space-y-2">
                                            <p class="text-sm text-gray-600">{{$location->address}}</p>
                                            <p class="text-sm text-gray-600">{{$location->city}}</p>
                                            <p class="text-sm text-gray-600">
                                                {{$location->state}},{{$location->country}}
                                            </p>
                                            <p class="text-sm text-gray-600">Contact: {{$location->phone}}</p>
                                            <p class="text-sm text-gray-600">Email: {{$location->email}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- To Section -->
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 fade-in">
                                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                        Ship To
                                    </h3>
                                    <div name="customer">
                                        {{$customer->customer_name}}
                                    </div>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Country
                                                *</label>
                                            <select name="to_country" id="to_country"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                required onchange="updateStates()">
                                                <option value="">Select Country</option>
                                                <option value="US" {{ $customer->customer_country == 'US' || $customer->customer_country == 'United States' || $customer->customer_country == 'USA' ? 'selected' : '' }}>United
                                                    States</option>
                                                <option value="IN" {{ $customer->customer_country == 'IN' || $customer->customer_country == 'India' ? 'selected' : '' }}>India
                                                </option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Address
                                                *</label>
                                            <input type="text" name="to_address"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                required value="{{$customer->customer_address}}">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">City *</label>
                                            <input type="text" name="to_city"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                required value="{{$customer->customer_city}}">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">State *</label>
                                            <select name="to_state" id="to_state"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                required>
                                                <option value="">Select State</option>
                                                <!-- States will be populated by JavaScript based on country selection -->
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">ZIP Code
                                                *</label>
                                            <input type="text" name="to_zip"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                required value="{{$customer->customer_pin_code}}">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone
                                                *</label>
                                            <input type="text" name="to_phone"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                required value="{{$customer->customer_phone}}">
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="residential_address"
                                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span class="ml-2 text-sm text-gray-700">This is a residential
                                                address</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Package Details -->
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 fade-in">
                                <div class="px-6 py-4 border-b border-gray-200">
                                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                        <i class="fas fa-box text-orange-600 mr-2"></i>
                                        Package & Shipment Details
                                    </h3>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Shipping
                                                Date
                                                *</label>
                                            <input type="date" name="shipping_date"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Number of
                                                Packages</label>
                                            <input type="number" name="package_number" min="1" max="45"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                value="1">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Weight per
                                                Package
                                                (lbs) *</label>
                                            <input type="number" name="weight" min="0.1" step="0.1"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Declared Value
                                                per Package (USD)</label>
                                            <input type="number" name="declared_Value" min="0" step="0.01"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                required>
                                        </div>
                                    </div>

                                    <div class="mb-6">
                                        <label class="block text-sm font-medium text-gray-700 mb-3">Pricing
                                            Option</label>
                                        <div class="space-y-2">
                                            <label class="flex items-center">
                                                <input type="radio" name="pricing_option" value="standard"
                                                    class="text-blue-600 focus:ring-blue-500" checked>
                                                <span class="ml-2 text-sm text-gray-700">FedEx Standard Rate</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" name="pricing_option" value="one_rate"
                                                    class="text-blue-600 focus:ring-blue-500">
                                                <span class="ml-2 text-sm text-gray-700">FedEx One Rate</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Service Type
                                                *</label>
                                            <select name="service_type" required
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                @foreach($servicetype as $value)
                                                    <option value="{{$value['code']}}">{{$value['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Package Type
                                                *</label>
                                            <select name="package_type" required
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                <option value="">Select Package Type</option>
                                                <option value="FEDEX_ENVELOPE">FedEx Envelope</option>
                                                <option value="FEDEX_PAK">FedEx Pak</option>
                                                <option value="FEDEX_BOX">FedEx Box</option>
                                                <option value="FEDEX_TUBE">FedEx Tube</option>
                                                <option value="YOUR_PACKAGING">Your Packaging</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Signature
                                                Type</label>
                                            <select name="signature_type"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                <option value="SERVICE_DEFAULT">Service Default</option>
                                                <option value="NO_SIGNATURE_REQUIRED">No Signature Required</option>
                                                <option value="INDIRECT">Indirect Signature</option>
                                                <option value="DIRECT">Direct Signature</option>
                                                <option value="ADULT">Adult Signature</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Shipment
                                            Notes</label>
                                        <textarea name="shipment_note" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="Additional notes or special instructions..."></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Rate Calculation Results -->
                            <div id="rate-results" class="bg-white rounded-lg shadow-sm border border-gray-200 fade-in">
                                <div class="px-6 py-4 border-b border-gray-200">
                                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                        <i class="fas fa-calculator text-purple-600 mr-2"></i>
                                        Rates & Transit Times
                                    </h3>
                                </div>
                                <div class="p-6">
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-sm text-left">
                                            <thead class="bg-gray-50 text-gray-700">
                                                <tr>
                                                    <th class="px-4 py-3">Service</th>
                                                    <th class="px-4 py-3">Transit Time</th>
                                                    <th class="px-4 py-3">FedEx Rate</th>
                                                    <th class="px-4 py-3">Your Rate</th>
                                                    <th class="px-4 py-3">Select</th>
                                                </tr>
                                            </thead>
                                            <tbody id="rate-tbody">
                                                <!-- Rates will be populated here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-between items-center pt-6">
                                <button type="button" onclick="history.back()"
                                    class="px-6 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                    <i class="fas fa-arrow-left mr-2"></i>Back
                                </button>
                                <div class="space-x-3">
                                    <button type="button" id="calculate-rates" data-id={{$shipment->id}}
                                        class="px-6 py-2 text-sm font-medium text-white bg-orange-600 border border-transparent rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-colors">
                                        <i class="fas fa-calculator mr-2"></i>Calculate Rates
                                    </button>
                                    <button type="submit" id="create-shipment"
                                        class="px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                                        disabled>
                                        <i class="fas fa-shipping-fast mr-2"></i>Create Shipment
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1">
                        <!-- Shipment Summary -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 fade-in">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900">Shipment Summary</h3>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Shipment Number:</span>
                                        <span class="font-medium">{{$shipment->shipment_number}}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Customer:</span>
                                        <span class="font-medium"> {{$customer->customer_name}}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Location:</span>
                                        <span class="font-medium"> {{$location->name}}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Total items:</span>
                                        <span class="font-medium">{{$shipment->total_quantity}}</span>
                                    </div>
                                    <div class="flex justify-between text-sm border-t pt-4">
                                        <span class="text-gray-500">Total cost:</span>
                                        <span class="font-bold text-lg">${{$shipment->total_price}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Products -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 fade-in">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900">Products</h3>
                            </div>
                            <div class="p-6">
                                <div class="space-y-3">
                                    @foreach($shipmentProducts as $product)
                                        <div class="bg-gray-50 rounded-md">
                                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                                                <p class="text-sm font-medium text-gray-900 text-wrap">
                                                    {{$product['product_search']}}
                                                </p>
                                            </div>
                                            <div class="flex items-center justify-between px-3">
                                                <p class="text-xs text-gray-500">
                                                    Batch:{{$product['batch_number'] ?? 'N/A'}}
                                                </p>
                                                <div>
                                                    <p class="text-sm font-medium text-nowrap">{{$product['quantity']}}x
                                                        ${{$product['net_unit_price']}}</p>
                                                    <p class="text-xs text-gray-500">${{$product['total_price']}}</p>
                                                </div>

                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading Modal -->
            <div id="loading-modal"
                class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50"
                style="display: none;">
                <div class="bg-white rounded-lg p-6 max-w-sm mx-4">
                    <div class="flex items-center space-x-3">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        <span class="text-gray-700">Processing...</span>
                    </div>
                </div>
            </div>
            <script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>
            <script>
                $('#calculate-rates').on('click', function (e) {
                    const form = document.getElementById('fedex-shipping-form');
                    const formData = new FormData(form);

                    // Validate required fields
                    if (!form.checkValidity()) {
                        form.reportValidity();
                        return;
                    }

                    // Show loading
                    showLoading(true);

                    var url = "delivery/"
                    var id = $(this).data('id').toString();
                    url = url.concat(id).concat("/calculate/shipment");

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: $('#fedex-shipping-form').serialize(),
                        complete: function (xhr, status) {
                            console.log('Request completed with status:', status);
                            console.log('Response status:', xhr.status);
                            console.log('Response text:', xhr.responseText);

                            showLoading(false);

                            let data = null;

                            // Try to parse response
                            try {
                                if (xhr.responseText) {
                                    data = JSON.parse(xhr.responseText);
                                }
                            } catch (e) {
                                console.error('Failed to parse response:', e);
                                alert('Invalid response from server. Please try again.');
                                return;
                            }

                            // Handle based on success flag, not HTTP status
                            if (data && data.success === true) {
                                if (data.length > 0) {
                                    $('.rate-div').show();
                                    $('.rate-tbody').html('');
                                    data.forEach(value => {
                                        //console.log('value,',value);
                                        let content = '<tr><td>' + value.serviceName + '</td><td>' + value.time + '</td><td>' + value.totalNetFedExCharge + '</td><td>' + value.totalNetCharge + '</td></tr>';
                                        $('.rate-tbody').append(content);
                                    });
                                } else {
                                    $('.rate-div').hide();
                                }
                            } else {
                                // Handle error response
                                handleErrorResponse(data);
                            }
                        }
                    });
                    function handleErrorResponse(data) {
                        console.log('Handling error response:', data);

                        let msg = 'An unknown error occurred. Please contact support.';
                        let title = 'Error';

                        if (data && data.error) {
                            // Set appropriate title based on error type
                            switch (data.error.type) {
                                case 'FedExError':
                                    title = 'Shipping Error';
                                    break;
                                case 'ValidationError':
                                    title = 'Validation Error';
                                    break;
                                case 'NetworkError':
                                    title = 'Connection Error';
                                    break;
                                default:
                                    title = 'Error';
                            }

                            // Get the best available error message
                            if (Array.isArray(data.error.friendly_messages) && data.error.friendly_messages.length > 0) {
                                const messages = data.error.friendly_messages;
                                msg = messages.length > 1 ? '• ' + messages.join('\n• ') : messages[0];
                                console.log('Using friendly_messages:', messages);
                            } else if (data.error.message && typeof data.error.message === 'string') {
                                msg = data.error.message;
                                console.log('Using error.message:', msg);

                                // Add details if available
                                if (data.error.details && Array.isArray(data.error.details)) {
                                    const details = data.error.details
                                        .map(d => typeof d === 'string' ? d : (d.message || d.code))
                                        .filter(Boolean)
                                        .join('\n• ');
                                    if (details) {
                                        msg += '\n\nDetails:\n• ' + details;
                                    }
                                }
                            } else if (typeof data.error === 'string') {
                                msg = data.error;
                                console.log('Using error as string:', msg);
                            }
                        }

                        console.log('Final error message:', msg);
                        console.log('Final error title:', title);

                        alert(title + ':\n\n' + msg);
                    }
                });

                // Enhanced loading function
                function showLoading(show) {
                    document.getElementById('loading-modal').style.display = show ? 'flex' : 'none';
                    document.getElementById('calculate-rates').disabled = show;
                }
            </script>
            <script>
                // State codes data from your PHP controller
                const stateData = {
                    "US": [
                        { name: 'Alabama', code: 'AL' },
                        { name: 'Alaska', code: 'AK' },
                        { name: 'Arizona', code: 'AZ' },
                        { name: 'Arkansas', code: 'AR' },
                        { name: 'California', code: 'CA' },
                        { name: 'Colorado', code: 'CO' },
                        { name: 'Connecticut', code: 'CT' },
                        { name: 'Delaware', code: 'DE' },
                        { name: 'District of Columbia', code: 'DC' },
                        { name: 'Florida', code: 'FL' },
                        { name: 'Georgia', code: 'GA' },
                        { name: 'Hawaii', code: 'HI' },
                        { name: 'Idaho', code: 'ID' },
                        { name: 'Illinois', code: 'IL' },
                        { name: 'Indiana', code: 'IN' },
                        { name: 'Iowa', code: 'IA' },
                        { name: 'Kansas', code: 'KS' },
                        { name: 'Kentucky', code: 'KY' },
                        { name: 'Louisiana', code: 'LA' },
                        { name: 'Maine', code: 'ME' },
                        { name: 'Maryland', code: 'MD' },
                        { name: 'Massachusetts', code: 'MA' },
                        { name: 'Michigan', code: 'MI' },
                        { name: 'Minnesota', code: 'MN' },
                        { name: 'Mississippi', code: 'MS' },
                        { name: 'Missouri', code: 'MO' },
                        { name: 'Montana', code: 'MT' },
                        { name: 'Nebraska', code: 'NE' },
                        { name: 'Nevada', code: 'NV' },
                        { name: 'New Hampshire', code: 'NH' },
                        { name: 'New Jersey', code: 'NJ' },
                        { name: 'New Mexico', code: 'NM' },
                        { name: 'New York', code: 'NY' },
                        { name: 'North Carolina', code: 'NC' },
                        { name: 'North Dakota', code: 'ND' },
                        { name: 'Ohio', code: 'OH' },
                        { name: 'Oklahoma', code: 'OK' },
                        { name: 'Oregon', code: 'OR' },
                        { name: 'Pennsylvania', code: 'PA' },
                        { name: 'Rhode Island', code: 'RI' },
                        { name: 'South Carolina', code: 'SC' },
                        { name: 'South Dakota', code: 'SD' },
                        { name: 'Tennessee', code: 'TN' },
                        { name: 'Texas', code: 'TX' },
                        { name: 'Utah', code: 'UT' },
                        { name: 'Vermont', code: 'VT' },
                        { name: 'Virginia', code: 'VA' },
                        { name: 'Washington State', code: 'WA' },
                        { name: 'West Virginia', code: 'WV' },
                        { name: 'Wisconsin', code: 'WI' },
                        { name: 'Wyoming', code: 'WY' },
                        { name: 'Puerto Rico', code: 'PR' }
                    ],
                    "IN": [
                        { name: "Andaman & Nicobar (U.T)", code: "AN" },
                        { name: "Andhra Pradesh", code: "AP" },
                        { name: "Arunachal Pradesh", code: "AR" },
                        { name: "Assam", code: "AS" },
                        { name: "Bihar", code: "BR" },
                        { name: "Chattisgarh", code: "CG" },
                        { name: "Chandigarh (U.T.)", code: "CH" },
                        { name: "Daman & Diu (U.T.)", code: "DD" },
                        { name: "Delhi (U.T.)", code: "DL" },
                        { name: "Dadra and Nagar Haveli (U.T.)", code: "DN" },
                        { name: "Goa", code: "GA" },
                        { name: "Gujarat", code: "GJ" },
                        { name: "Haryana", code: "HR" },
                        { name: "Himachal Pradesh", code: "HP" },
                        { name: "Jammu & Kashmir", code: "JK" },
                        { name: "Jharkhand", code: "JH" },
                        { name: "Karnataka", code: "KA" },
                        { name: "Kerala", code: "KL" },
                        { name: "Lakshadweep (U.T)", code: "LD" },
                        { name: "Madhya Pradesh", code: "MP" },
                        { name: "Maharashtra", code: "MH" },
                        { name: "Manipur", code: "MN" },
                        { name: "Meghalaya", code: "ML" },
                        { name: "Mizoram", code: "MZ" },
                        { name: "Nagaland", code: "NL" },
                        { name: "Orissa", code: "OR" },
                        { name: "Punjab", code: "PB" },
                        { name: "Puducherry (U.T.)", code: "PY" },
                        { name: "Rajasthan", code: "RJ" },
                        { name: "Sikkim", code: "SK" },
                        { name: "Tamil Nadu", code: "TN" },
                        { name: "Tripura", code: "TR" },
                        { name: "Uttaranchal", code: "UA" },
                        { name: "Uttar Pradesh", code: "UP" },
                        { name: "West Bengal", code: "WB" }
                    ]
                };

                // Customer's current state from PHP
                const customerState = "{{$customer->customer_state}}";

                function updateStates() {
                    const countrySelect = document.getElementById('to_country');
                    const stateSelect = document.getElementById('to_state');
                    const selectedCountry = countrySelect.value;

                    // Clear existing options
                    stateSelect.innerHTML = '<option value="">Select State</option>';

                    if (selectedCountry && stateData[selectedCountry]) {
                        stateData[selectedCountry].forEach(state => {
                            const option = document.createElement('option');
                            option.value = state.code;
                            option.textContent = state.name;

                            // Check if this matches the customer's current state
                            if (state.name === customerState || state.code === customerState) {
                                option.selected = true;
                            }

                            stateSelect.appendChild(option);
                        });
                    }
                }

                // Initialize states on page load
                document.addEventListener('DOMContentLoaded', function () {
                    updateStates();
                });
            </script>
        </div>
    </div>
</x-app-layout>