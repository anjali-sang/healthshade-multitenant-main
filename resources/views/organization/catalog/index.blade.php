<x-app-layout>
  <!-- Navigation Tabs -->
  <div class="px-1 lg:px-4 pt-4 lg:pt-8">
    <div class="bg-white p-4 dark:bg-gray-800 shadow sm:rounded-lg">
      <!-- <div class="border-b border-gray-200 dark:border-gray-700">
        <nav class="flex space-x-8" aria-label="Catalog Navigation">
          <button id="toggle-master-catalog-btn"
            class="inline-flex items-center px-3 py-2 text-sm font-medium border-b-2 border-transparent hover:border-primary-dk hover:text-primary-dk dark:hover:text-primary-dk transition-colors"
            onclick="toggleView('master-catalog')">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linejoin="round" stroke-width="2"
                d="M10 12v1h4v-1m4 7H6a1 1 0 0 1-1-1V9h14v9a1 1 0 0 1-1 1ZM4 5h16a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z" />
            </svg>
            {{ auth()->user()->organization->name }} {{ __('Catalog') }}
          </button>

          <button id="toggle-my-catalog-btn"
            class="inline-flex items-center px-3 py-2 text-sm font-medium border-b-2 border-transparent hover:border-primary-dk hover:text-primary-dk dark:hover:text-primary-dk transition-colors"
            onclick="toggleView('my-catalog')">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                d="M9 8h10M9 12h10M9 16h10M4.99 8H5m-.02 4h.01m0 4H5" />
            </svg>
            {{ __('Master Catalog') }}
          </button>
        </nav>
      </div> -->

      <!-- Content Sections -->
      <div id="my-catalog-section">
        <livewire:organization.products.master-catalog-components />
      </div>
      <div id="master-catalog-section" hidden>
        <livewire:organization.catalog-component />
      </div>
    </div>
  </div>

  <!-- <script>
    document.addEventListener("DOMContentLoaded", function () {
      var catalog = {!! json_encode($catalog) !!};
      if (catalog > 0) {
        toggleView('master-catalog');
      } else {
        toggleView('my-catalog');
      }
    });

    function toggleView(section) {
      const sections = ['my-catalog', 'master-catalog'];
      const activeClasses = ['border-primary-md', 'text-primary-md', 'dark:border-primary-md', 'dark:text-primary-md', 'border-b-2'];
      const inactiveClasses = ['border-transparent', 'text-gray-500', 'dark:text-gray-400'];

      sections.forEach((sec) => {
        const sectionElement = document.getElementById(`${sec}-section`);
        const buttonElement = document.getElementById(`toggle-${sec}-btn`);

        if (sec === section) {
          sectionElement.hidden = false;
          buttonElement.classList.remove(...inactiveClasses);
          buttonElement.classList.add(...activeClasses);
        } else {
          sectionElement.hidden = true;
          buttonElement.classList.remove(...activeClasses);
          buttonElement.classList.add(...inactiveClasses);
        }
      });
    }
  </script> -->
</x-app-layout>