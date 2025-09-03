import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import Calendar from 'tui-calendar'; 
import 'tui-calendar/dist/tui-calendar.css';
import 'tui-date-picker/dist/tui-date-picker.css';
import 'tui-time-picker/dist/tui-time-picker.css';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';

window.tui = { Calendar };
window.flatpickr = flatpickr;


import AOS from 'aos';
import 'aos/dist/aos.css'; // You can also use <link> for styles
// ..
AOS.init();