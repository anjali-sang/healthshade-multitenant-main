import './bootstrap';
import './../../vendor/power-components/livewire-powergrid/dist/powergrid'
import './../../vendor/power-components/livewire-powergrid/dist/tailwind.css'
import jQuery from 'jquery';
import flatpickr from "flatpickr"; 
window.$ = jQuery;


if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
  document.documentElement.classList.add('dark')
} else {
  document.documentElement.classList.remove('dark')
}


// import Alpine from 'alpinejs';

// window.Alpine = Alpine;

// Alpine.start();
