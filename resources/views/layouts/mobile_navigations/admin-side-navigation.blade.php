{{-- <!-- Sidebar -->
<div class="hidden md:block  w-0 sm:w-20 z-10 fixed top-16 start-0 bottom-0 z-10 w-20 bg-gradient-to-b from-primary-md to-primary-dk border-e border-primary-lt dark:border-primary-dk lg:translate-x-0 lg:end-auto lg:bottom-0"
    role="dialog" tabindex="-1" aria-label="Mini Sidebar">
    <div class="flex flex-col justify-center items-center gap-y-4 py-4">
        @php
            $menuItems = [
                [
                    'route' => 'dashboard',
                    'title' => 'Dashboard',
                    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"> <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /> </svg>'
                ],
                [
                    'route' => 'subscriptions.index',
                    'title' => 'Subscriptions',
                    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                                                stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                                            </svg>'
                ],
                [
                    'route' => 'unit.index',
                    'title' => 'Units',
                    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                                                stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M7.58209 8.96025 9.8136 11.1917l-1.61782 1.6178c-1.08305-.1811-2.23623.1454-3.07364.9828-1.1208 1.1208-1.32697 2.8069-.62368 4.1363.14842.2806.42122.474.73509.5213.06726.0101.1347.0133.20136.0098-.00351.0666-.00036.1341.00977.2013.04724.3139.24069.5867.52125.7351 1.32944.7033 3.01552.4971 4.13627-.6237.8375-.8374 1.1639-1.9906.9829-3.0736l4.8107-4.8108c1.0831.1811 2.2363-.1454 3.0737-.9828 1.1208-1.1208 1.3269-2.80688.6237-4.13632-.1485-.28056-.4213-.474-.7351-.52125-.0673-.01012-.1347-.01327-.2014-.00977.0035-.06666.0004-.13409-.0098-.20136-.0472-.31386-.2406-.58666-.5212-.73508-1.3294-.70329-3.0155-.49713-4.1363.62367-.8374.83741-1.1639 1.9906-.9828 3.07365l-1.7788 1.77875-2.23152-2.23148-1.41419 1.41424Zm1.31056-3.1394c-.04235-.32684-.24303-.61183-.53647-.76186l-1.98183-1.0133c-.38619-.19746-.85564-.12345-1.16234.18326l-.86321.8632c-.3067.3067-.38072.77616-.18326 1.16235l1.0133 1.98182c.15004.29345.43503.49412.76187.53647l1.1127.14418c.3076.03985.61628-.06528.8356-.28461l.86321-.8632c.21932-.21932.32446-.52801.2846-.83561l-.14417-1.1127ZM19.4448 16.4052l-3.1186-3.1187c-.7811-.781-2.0474-.781-2.8285 0l-.1719.172c-.7811.781-.7811 2.0474 0 2.8284l3.1186 3.1187c.7811.781 2.0474.781 2.8285 0l.1719-.172c.7811-.781.7811-2.0474 0-2.8284Z" />
                                            </svg>',
                ],
                [
                    'route' => 'admin.products.index',
                    'title' => 'Products',
                    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                                                stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-line-join="round" stroke-width="0.8"
                                                    d="M7 24h-5v-9h5v1.735c.638-.198 1.322-.495 1.765-.689.642-.28 1.259-.417 1.887-.417 1.214 0 2.205.499 4.303 1.205.64.214 1.076.716 1.175 1.306 1.124-.863 2.92-2.257 2.937-2.27.357-.284.773-.434 1.2-.434.952 0 1.751.763 1.751 1.708 0 .49-.219.977-.627 1.356-1.378 1.28-2.445 2.233-3.387 3.074-.56.501-1.066.952-1.548 1.393-.749.687-1.518 1.006-2.421 1.006-.405 0-.832-.065-1.308-.2-2.773-.783-4.484-1.036-5.727-1.105v1.332zm-1-8h-3v7h3v-7zm1 5.664c2.092.118 4.405.696 5.999 1.147.817.231 1.761.354 2.782-.581 1.279-1.172 2.722-2.413 4.929-4.463.824-.765-.178-1.783-1.022-1.113 0 0-2.961 2.299-3.689 2.843-.379.285-.695.519-1.148.519-.107 0-.223-.013-.349-.042-.655-.151-1.883-.425-2.755-.701-.575-.183-.371-.993.268-.858.447.093 1.594.35 2.201.52 1.017.281 1.276-.867.422-1.152-.562-.19-.537-.198-1.889-.665-1.301-.451-2.214-.753-3.585-.156-.639.278-1.432.616-2.164.814v3.888zm3.79-19.913l3.21-1.751 7 3.86v7.677l-7 3.735-7-3.735v-7.719l3.784-2.064.002-.005.004.002zm2.71 6.015l-5.5-2.864v6.035l5.5 2.935v-6.106zm1 .001v6.105l5.5-2.935v-6l-5.5 2.83zm1.77-2.035l-5.47-2.848-2.202 1.202 5.404 2.813 2.268-1.167zm-4.412-3.425l5.501 2.864 2.042-1.051-5.404-2.979-2.139 1.166z" />
                                            </svg>'
                ],
                [
                    'route' => 'supplier.index',
                    'title' => 'Suppliers',
                    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                                                stroke="currentColor" class="size-6">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-line-join="round" stroke-width="1"
                                                    d="M13 7h6l2 4m-8-4v8m0-8V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v9h2m8 0H9m4 0h2m4 0h2v-4m0 0h-5m3.5 5.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Zm-10 0a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z" />
                                            </svg>'
                ],
                [
                    'route' => 'admin-organization.index',
                    'title' => 'Organizations',
                    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                            </svg>
                        '
                ],
                [
                    'route' => 'users.index',
                    'title' => 'Users',
                    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 7.5a4.5 4.5 0 10-9 0 4.5 4.5 0 009 0zM21 20.25v-1.5a4.5 4.5 0 00-4.5-4.5h-9a4.5 4.5 0 00-4.5 4.5v1.5" />
                                            </svg>'
                ],
                [
                    'route' => 'billing_shipping.index',
                    'title' => 'Billing and Shipping',
                    'svg' => '<svg viewBox="0 0 32 32" id="svg5" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg" fill="currentColor"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <defs id="defs2"></defs> <g id="layer1" transform="translate(36,-148)"> <path d="m -28,150 c -2.749574,0 -5,2.25042 -5,5 v 22 a 1.0001,1.0001 0 0 0 1.832031,0.55469 L -30,175.80273 l 1.167969,1.75196 a 1.0001,1.0001 0 0 0 1.664062,0 L -26,175.80273 l 1.167969,1.75196 a 1.0001,1.0001 0 0 0 1.664062,0 L -22,175.80273 l 1.167969,1.75196 a 1.0001,1.0001 0 0 0 1.664062,0 L -18,175.80273 l 1.167969,1.75196 A 1.0001,1.0001 0 0 0 -15,177 v -12 h 7 a 1.0001,1.0001 0 0 0 1,-1 v -9 c 0,-2.74958 -2.2504259,-5 -5,-5 z m 0,2 h 12.007812 C -16.624083,152.83731 -17,153.87659 -17,155 v 1 8 9.69727 l -0.167969,-0.25196 a 1.0001,1.0001 0 0 0 -1.664062,0 L -20,175.19727 l -1.167969,-1.75196 a 1.0001,1.0001 0 0 0 -1.664062,0 L -24,175.19727 l -1.167969,-1.75196 a 1.0001,1.0001 0 0 0 -1.664062,0 L -28,175.19727 l -1.167969,-1.75196 a 1.0001,1.0001 0 0 0 -1.664062,0 L -31,173.69727 V 155 c 0,-1.6687 1.331303,-3 3,-3 z m 16,0 c 1.668697,0 3,1.3313 3,3 v 8 h -6 v -7 -1 c 0,-1.6687 1.331303,-3 3,-3 z" id="rect1587" style="color:currentColor;fill:currentColor;fill-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4.1;-inkscape-stroke:none"></path> <path d="m -28,162 a 1,1 0 0 0 -1,1 1,1 0 0 0 1,1 h 4 a 1,1 0 0 0 1,-1 1,1 0 0 0 -1,-1 z" id="path6019" style="color:#000000;fill:currentColor;fill-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4.1;-inkscape-stroke:none"></path> <path d="m -28,165 a 1,1 0 0 0 -1,1 1,1 0 0 0 1,1 h 8 a 1,1 0 0 0 1,-1 1,1 0 0 0 -1,-1 z" id="path6021" style="color:currentColor;fill:currentColor;fill-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4.1;-inkscape-stroke:none"></path> <path d="m -28,168 a 1,1 0 0 0 -1,1 1,1 0 0 0 1,1 h 8 a 1,1 0 0 0 1,-1 1,1 0 0 0 -1,-1 z" id="path6023" style="color:currentColor;fill:currentColor;fill-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4.1;-inkscape-stroke:none"></path> <path d="m -28,155 a 1.0001,1.0001 0 0 0 -1,1 v 4 a 1.0001,1.0001 0 0 0 1,1 h 4 a 1.0001,1.0001 0 0 0 1,-1 v -4 a 1.0001,1.0001 0 0 0 -1,-1 z m 1,2 h 2 v 2 h -2 z" id="rect6025" style="color:currentColor;fill:currentColor;fill-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4.1;-inkscape-stroke:none"></path> </g> </g></svg>'
                ],
                [
                'route' => 'report.index',
                'title' => 'Reports',
                'svg' => '<svg class="shrink-0 size-6" xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z" /></svg>',
                ],
                [
                    'route' => 'ticket.index',
                    'title' => 'Tickets',
                    'svg' => '
                    <svg class="shrink-0 size-6" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="4" width="20" height="16" rx="2"/>
            <path d="M7 9h10M7 13h5"/>
            </svg>'

                ],
            ];
        @endphp
        <div class="flex flex-col justify-center items-center gap-y-4 py-4">
            @foreach ($menuItems as $item)
                <div class="relative flex flex-col items-center group">
                    <a href="{{ route($item['route']) }}">
                        <button type="button"
                            class="p-2 flex justify-center items-center w-10 h-10 rounded-full
                                            {{ request()->routeIs($item['route']) ? 'bg-white text-black' : 'text-white' }}
                                            border border-transparent focus:outline-none focus:bg-primary-lt
                                            disabled:opacity-50 disabled:pointer-events-none hover:bg-primary-lt hover:text-black">
                            {!! $item['svg'] !!}
                        </button>
                    </a>
                    <!-- Tooltip  -->
                    <span class="absolute left-full ml-1 top-1/2 transform -translate-y-1/2 
                    z-20 bg-primary-lt text-black px-3 py-2 text-sm rounded shadow-md 
                    opacity-0 group-hover:opacity-100 
                    transition-all duration-500 ease-in-out 
                    translate-x-2 group-hover:translate-x-0
                    min-w-max whitespace-nowrap">
                        {{ __($item['title']) }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- End Sidebar --> --}}